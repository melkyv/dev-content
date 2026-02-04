<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Stripe\Customer;
use Stripe\Exception\SignatureVerificationException;
use Stripe\StripeClient;
use Stripe\Webhook;

class StripeService
{
    private StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    public function handleWebhook(string $payload, string $signature): array
    {
        $webhookSecret = config('services.stripe.webhook_secret');

        if (! $webhookSecret) {
            Log::error('Stripe webhook secret not configured');
            throw new \Exception('Webhook secret not configured');
        }

        try {
            $event = Webhook::constructEvent($payload, $signature, $webhookSecret);
        } catch (SignatureVerificationException $e) {
            Log::error('Stripe webhook signature verification failed', ['error' => $e->getMessage()]);
            throw $e;
        }

        return $this->processEvent($event);
    }

    private function processEvent(object $event): array
    {
        $type = $event->type;
        $data = $event->data->object;

        switch ($type) {
            case 'checkout.session.completed':
                return $this->handleCheckoutCompleted($data);

            case 'customer.subscription.updated':
                return $this->handleSubscriptionUpdated($data);

            case 'customer.subscription.deleted':
                return $this->handleSubscriptionDeleted($data);

            case 'invoice.payment_succeeded':
                return $this->handlePaymentSucceeded($data);

            default:
                Log::info('Unhandled Stripe webhook event', ['type' => $type]);

                return [
                    'message' => 'Event type not handled',
                    'code' => 422,
                    'status' => 'warning',
                    'event_type' => $type,
                ];
        }
    }

    public function createBillingPortalSession(User $user): string
    {
        if (! $user->stripe_customer_id) {
            throw new \Exception('User does not have a Stripe customer ID');
        }

        $session = $this->stripe->billingPortal->sessions->create([
            'customer' => $user->stripe_customer_id,
            'return_url' => route('subscription'),
        ]);

        return $session->url;
    }

    public function createCheckoutSession(User $user, Plan $plan): string
    {
        $customer = $this->createCustomer($user);

        $session = $this->stripe->checkout->sessions->create([
            'customer' => $customer->id,
            'payment_method_types' => ['card'],
            'mode' => 'subscription',
            'line_items' => [[
                'price' => $plan->stripe_price_id,
                'quantity' => 1,
            ]],
            'success_url' => route('subscription.success'),
            'cancel_url' => route('subscription.cancel'),
            'metadata' => [
                'plan_id' => $plan->id,
                'user_id' => $user->id,
            ],
        ]);

        return $session->url;
    }

    public function createCustomer(User $user): Customer
    {
        if ($user->stripe_customer_id) {
            return $this->stripe->customers->retrieve($user->stripe_customer_id);
        }

        $customer = $this->stripe->customers->create([
            'email' => $user->email,
            'name' => $user->name,
            'metadata' => [
                'user_id' => $user->id,
            ],
        ]);

        $user->stripe_customer_id = $customer->id;
        $user->save();

        return $customer;
    }

    private function handleCheckoutCompleted(object $session): array
    {
        $userId = $session->metadata->user_id ?? null;
        $planId = $session->metadata->plan_id ?? null;
        $stripeCustomerId = $session->customer ?? null;

        if (! $userId || ! $planId || ! $stripeCustomerId) {
            Log::error('Invalid checkout session metadata', ['session_id' => $session->id]);

            return [
                'error' => 'Invalid metadata',
                'code' => 400,
                'status' => 'error',
                'session_id' => $session->id,
            ];
        }

        $user = User::find($userId);
        $plan = Plan::find($planId);

        if (! $user || ! $plan) {
            Log::error('User or plan not found', ['user_id' => $userId, 'plan_id' => $planId]);

            return [
                'error' => 'User or plan not found',
                'code' => 404,
                'status' => 'error',
                'user_id' => $userId,
                'plan_id' => $planId,
            ];
        }

        $subscription = $this->stripe->subscriptions->retrieve($session->subscription);

        $subscriptionItem = $subscription->items->data[0] ?? null;
        $price = $subscriptionItem?->price ?? null;

        Subscription::updateOrCreate(
            ['user_id' => $user->id],
            [
                'plan_id' => $plan->id,
                'stripe_subscription_id' => $subscription->id,
                'status' => $subscription->status,
                'ends_at' => $subscriptionItem->current_period_end ? now()->setTimestamp($subscriptionItem->current_period_end) : null,
                'currency' => $subscription->currency ?? 'brl',
                'amount' => $price?->unit_amount ?? ($price?->amount ?? 0),
            ]
        );

        Log::info('Checkout completed', ['user_id' => $userId, 'subscription_id' => $subscription->id]);

        return [
            'message' => 'Checkout processed successfully',
            'code' => 200,
            'status' => 'success',
            'subscription_id' => $subscription->id,
            'user_id' => $userId,
        ];
    }

    private function handleSubscriptionUpdated(object $subscription): array
    {
        $localSubscription = Subscription::where('stripe_subscription_id', $subscription->id)->first();

        if (! $localSubscription) {
            Log::warning('Local subscription not found', ['stripe_subscription_id' => $subscription->id]);

            return [
                'warning' => 'Local subscription not found',
                'code' => 404,
                'status' => 'warning',
                'stripe_subscription_id' => $subscription->id,
            ];
        }

        $subscriptionItem = $subscription->items->data[0] ?? null;
        $price = $subscriptionItem?->price ?? null;

        $updateData = [
            'status' => $subscription->status,
            'ends_at' => $subscriptionItem->current_period_end ? now()->setTimestamp($subscriptionItem->current_period_end) : null,
            'cancel_at_period_end' => $subscription->cancel_at_period_end ?? false,
            'currency' => $subscription->currency ?? 'brl',
            'amount' => $price?->unit_amount ?? ($price?->amount ?? 0),
        ];

        if (isset($subscription->canceled_at)) {
            $updateData['canceled_at'] = $subscription->canceled_at ? now()->setTimestamp($subscription->canceled_at) : null;
        }

        if (isset($subscription->cancel_at)) {
            $updateData['cancel_at'] = now()->setTimestamp($subscription->cancel_at);
        }

        $localSubscription->update($updateData);

        Log::info('Subscription updated', ['subscription_id' => $subscription->id]);

        return [
            'message' => 'Subscription updated successfully',
            'code' => 200,
            'status' => 'success',
            'subscription_id' => $subscription->id,
            'new_status' => $subscription->status,
        ];
    }

    private function handleSubscriptionDeleted(object $subscription): array
    {
        $localSubscription = Subscription::where('stripe_subscription_id', $subscription->id)->first();

        if (! $localSubscription) {
            Log::warning('Local subscription not found for deletion', ['stripe_subscription_id' => $subscription->id]);

            return [
                'warning' => 'Local subscription not found',
                'code' => 404,
                'status' => 'warning',
                'stripe_subscription_id' => $subscription->id,
            ];
        }

        $updateData = [
            'status' => 'canceled',
            'ends_at' => now(),
            'canceled_at' => now(),
        ];

        if (isset($subscription->canceled_at)) {
            $updateData['canceled_at'] = now()->setTimestamp($subscription->canceled_at);
        }

        $localSubscription->update($updateData);

        Log::info('Subscription deleted', ['subscription_id' => $subscription->id]);

        return [
            'message' => 'Subscription deleted successfully',
            'code' => 200,
            'status' => 'success',
            'subscription_id' => $subscription->id,
        ];
    }

    private function handlePaymentSucceeded(object $invoice): array
    {
        if (! isset($invoice->customer)) {
            return [
                'message' => 'Invoice does not contain customer information',
                'code' => 400,
                'status' => 'error',
                'invoice_id' => $invoice->id ?? null,
            ];
        }

        $subscriptionId = $invoice->parent->subscription_details->subscription;

        $localSubscription = Subscription::where('stripe_subscription_id', $subscriptionId)->first();

        if (! $localSubscription) {
            Log::warning('Local subscription not found for payment', ['stripe_subscription_id' => $invoice->subscription]);

            return [
                'warning' => 'Local subscription not found',
                'code' => 404,
                'status' => 'warning',
                'stripe_subscription_id' => $invoice->subscription,
            ];
        }

        $localSubscription->payments()->create([
            'user_id' => $localSubscription->user_id,
            'stripe_payment_intent_id' => $invoice->id ?? null,
            'amount' => $invoice->subtotal,
            'currency' => $invoice->currency,
            'status' => $invoice->status,
        ]);

        Log::info('Payment succeeded', ['invoice_id' => $invoice->id, 'customer_id' => $invoice->customer, 'amount' => $invoice->subtotal]);

        return [
            'message' => 'Payment processed successfully',
            'code' => 200,
            'status' => 'success',
            'invoice_id' => $invoice->id,
            'amount' => $invoice->subtotal,
            'currency' => $invoice->currency,
            'subscription_id' => $invoice->subscription,
        ];
    }
}
