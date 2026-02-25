<?php

namespace App\Livewire\Contents;

use App\Models\Content;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public string $type = '';

    public string $sortBy = 'recent';

    protected $queryString = [
        'search' => ['except' => ''],
        'type' => ['except' => ''],
        'sortBy' => ['except' => 'recent'],
    ];

    public function updating($name): void
    {
        if (in_array($name, ['search', 'type', 'sortBy'])) {
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'type', 'sortBy']);
        $this->resetPage();
    }

    public function render()
    {
        $query = Content::published()
            ->with(['user', 'files', 'metrics']);

        if ($this->search) {
            $query->where('title', 'like', "%{$this->search}%");
        }

        if ($this->type === 'premium') {
            $query->where('is_premium', true);
        } elseif ($this->type === 'free') {
            $query->where('is_premium', false);
        }

        if ($this->sortBy === 'views') {
            $query->withCount(['metrics as total_views' => function ($q) {
                $q->selectRaw('COALESCE(SUM(views), 0)');
            }])->orderByDesc('total_views');
        } elseif ($this->sortBy === 'downloads') {
            $query->withCount(['metrics as total_downloads' => function ($q) {
                $q->selectRaw('COALESCE(SUM(downloads), 0)');
            }])->orderByDesc('total_downloads');
        } else {
            $query->latest('published_at');
        }

        $contents = $query->paginate(10);
        $hasActiveSubscription = Auth::user()?->hasActiveSubscription() ?? false;

        return view('livewire.contents.index', [
            'contents' => $contents,
            'hasActiveSubscription' => $hasActiveSubscription,
        ]);
    }
}
