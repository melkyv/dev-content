<?php

namespace App\Livewire;

use App\Models\Content;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public $user;

    public $plan;

    public $isPremium;

    public $myContents;

    public $totalViews;

    public $totalDownloads;

    public $hasContents;

    public $totalPlatformContents;

    public $premiumContents;

    public $processingFiles;

    public $dashboardState;

    public function mount(): void
    {
        $this->user = Auth::user()->load('subscription.plan');
        $this->plan = $this->user->subscription?->plan;
        $this->isPremium = $this->plan && $this->plan->slug === 'premium';

        $this->myContents = $this->user->contents()
            ->with('metrics')
            ->latest()
            ->take(5)
            ->get();

        $this->hasContents = $this->myContents->isNotEmpty();

        $contentIds = $this->myContents->pluck('id');
        $metrics = \App\Models\ContentMetric::whereIn('content_id', $contentIds)->get();
        $this->totalViews = $metrics->sum('views');
        $this->totalDownloads = $metrics->sum('downloads');

        $this->processingFiles = \App\Models\File::whereIn('content_id', $contentIds)
            ->where('is_processed', false)
            ->count();

        $this->totalPlatformContents = Content::whereNotNull('published_at')->count();

        $this->premiumContents = Content::where('is_premium', true)
            ->whereNotNull('published_at')
            ->latest()
            ->take(3)
            ->get();

        if ($this->isPremium) {
            $this->dashboardState = 'premium';
        } else {
            $this->dashboardState = $this->hasContents ? 'with-content' : 'new-user';
        }
    }

    public function logout(): void
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        $this->redirect(route('login'), navigate: true);
    }
}
