<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
class Booking extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id_booking';
    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'booking_date',
        'event_date',
        'event_address',
        'total_price',
        'status',
        'id_user',
        'id_service',
    ];

    protected static function booted(): void
    {
        static::creating(function (Booking $booking) {
            do {
                $id = 'BKG_' . strtoupper(\Illuminate\Support\Str::random(16));
            } while (Booking::where('id_booking', $id)->exists());

            $booking->id_booking = $id;
        });
    }
    protected function casts(): array
    {
        return [
            'booking_date' => 'datetime',
            'event_date' => 'date',
            'total_price' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'id_user',
            'id_user'
        );
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(
            Service::class,
            'id_service',
            'id_service'
        );
    }
}