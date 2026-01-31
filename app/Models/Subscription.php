<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'stripe_subscription_id',
        'status',
        'ends_at',
        'cancel_at_period_end',
        'canceled_at',
        'currency',
        'amount',
    ];

    protected function casts(): array
    {
        return [
            'ends_at' => 'datetime',
            'canceled_at' => 'datetime',
            'cancel_at_period_end' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function active(): bool
    {
        return $this->status === 'active' && $this->amount > 0 && (! $this->ends_at || $this->ends_at->isFuture());
    }

    public function cancelled(): bool
    {
        return $this->status === 'canceled' || $this->cancel_at_period_end;
    }

    public function onGracePeriod(): bool
    {
        return $this->cancelled() && $this->ends_at?->isFuture();
    }
}
