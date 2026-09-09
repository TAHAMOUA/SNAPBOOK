<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Availability extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $primaryKey = 'id_availability';
    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'available_date',
        'start_time',
        'end_time',
        'id_profile',
    ];
    protected static function booted(): void
    {
        static::creating(function (Availability $availability) {
            do {
                $id = 'AVL_' . strtoupper(\Illuminate\Support\Str::random(16));
            } while (Availability::where('id_availability', $id)->exists());

            $availability->id_availability = $id;
        });
    }

    protected function casts(): array
    {
        return [
            'available_date' => 'date:Y-m-d',
            'start_time' => 'string',
            'end_time' => 'string',
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

    public function bookings(): HasMany
    {
        return $this->hasMany(
            Booking::class,
            'id_availability',
            'id_availability'
        );
    }
}