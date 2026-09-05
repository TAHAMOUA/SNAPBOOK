<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $primaryKey = 'id_service';

    protected $fillable = [
        'title',
        'description',
        'price',
        'duration',
        'id_profile',
        'id_category',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'duration' => 'integer',
        ];
    }

    public function photographerProfile(): BelongsTo
    {
        return $this->belongsTo(
            PhotographerProfile::class,
            'id_profile',
            'id_profile'
        );
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            Category::class,
            'id_category',
            'id_category'
        );
    }
    public function bookings(): HasMany
    {
        return $this->hasMany(
            Booking::class,
            'id_service',
            'id_service'
        );
    }
}