<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Availability extends Model
{
    use SoftDeletes;
    
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
            'available_date' => 'date',
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
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
}