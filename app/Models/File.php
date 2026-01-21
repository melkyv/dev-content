<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'content_id',
        'path',
        'original_name',
        'mime_type',
        'size',
        'disk',
        'is_processed',
    ];

    protected function casts(): array
    {
        return [
            'is_processed' => 'boolean',
        ];
    }

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }
}
