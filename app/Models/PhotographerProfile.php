<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PhotographerProfile extends Model
{
    protected $primaryKey = 'id_profile';

    protected $fillable = [
        'bio',
        'city',
        'experience',
        'validation_status',
        'id_user',
    ];


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
} 