<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'content_id',
        'views',
        'downloads',
        'date',
    ];

    protected function casts(): array
    {
        return [
            'views' => 'integer',
            'downloads' => 'integer',
            'date' => 'date',
        ];
    }

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }
}
