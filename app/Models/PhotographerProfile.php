<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class PhotographerProfile extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id_profile';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'bio',
        'city',
        'experience',
        'validation_status',
        'id_user',
    ];
    protected static function booted(): void
    {
        static::creating(function (PhotographerProfile $profile) {
            do {
                $id = 'PRO_' . strtoupper(Str::random(16));
            } while (PhotographerProfile::where('id_profile', $id)->exists());

            $profile->id_profile = $id;
        });
    }

    protected function casts(): array
    {
        return [
            'experience' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
    public function services(): HasMany
    {
        return $this->hasMany(
            Service::class,
            'id_profile',
            'id_profile'
        );
    }

    public function portfolios(): HasMany
    {
        return $this->hasMany(
            Portfolio::class,
            'id_profile',
            'id_profile'
        );
    }

    public function availabilities(): HasMany
    {
        return $this->hasMany(
            Availability::class,
            'id_profile',
            'id_profile'
        );
    }
    public function reviews(): HasMany
{
    return $this->hasMany(
        Review::class,
        'id_profile',
        'id_profile'
    );
}
} 