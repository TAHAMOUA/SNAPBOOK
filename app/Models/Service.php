<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id_service';
    public $incrementing = false;

    protected $keyType = 'string';
    protected $fillable = [
        'title',
        'description',
        'price',
        'duration',
        'id_profile',
        'id_category',
    ];
    protected static function booted(): void
    {
        static::creating(function (Service $service) {
            do {
                $id = 'SRV_' . strtoupper(\Illuminate\Support\Str::random(16));
            } while (Service::where('id_service', $id)->exists());

            $service->id_service = $id;
        });
    }

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