<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_path',
        'provider',
        'provider_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->avatar_path) {
                    return asset('images/default-avatar.png');
                }
                
                if (filter_var($this->avatar_path, FILTER_VALIDATE_URL)) {
                    return $this->avatar_path;
                }
                
                return Storage::url($this->avatar_path);
            }
        );
    }

    protected function initials(): Attribute
    {
        return Attribute::make(
            get: function () {
                $name = $this->name;

                $firstName = Str::before($name, ' ');
                $lastName = Str::contains($name, ' ') ? Str::afterLast($name, ' ') : '';

                return Str::upper(
                    Str::substr($firstName, 0, 1).Str::substr($lastName, 0, 1)
                );
            },
        );
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function contents(): HasMany
    {
        return $this->hasMany(Content::class);
    }
}
