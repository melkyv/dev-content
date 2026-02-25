<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Content extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'is_premium',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_premium' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    public function metrics(): HasMany
    {
        return $this->hasMany(ContentMetric::class);
    }

    public function getTotalViewsAttribute(): int
    {
        return $this->metrics()->sum('views');
    }

    public function getTotalDownloadsAttribute(): int
    {
        return $this->metrics()->sum('downloads');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function scopePremium(Builder $query): Builder
    {
        return $query->where('is_premium', true);
    }

    public function scopeFree(Builder $query): Builder
    {
        return $query->where('is_premium', false);
    }

    public function recordView(): void
    {
        $today = now()->toDateString();

        $metric = $this->metrics()->whereDate('date', $today)->first();

        if ($metric) {
            $metric->increment('views');
        } else {
            $this->metrics()->create([
                'views' => 1,
                'downloads' => 0,
                'date' => $today,
            ]);
        }
    }

    public function recordDownload(): void
    {
        $today = now()->toDateString();

        $metric = $this->metrics()->whereDate('date', $today)->first();

        if ($metric) {
            $metric->increment('downloads');
        } else {
            $this->metrics()->create([
                'views' => 0,
                'downloads' => 1,
                'date' => $today,
            ]);
        }
    }
}
