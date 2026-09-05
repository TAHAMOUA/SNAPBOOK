<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    protected $primaryKey = 'id_booking';

    protected $fillable = [
        'booking_date',
        'event_date',
        'event_address',
        'total_price',
        'status',
        'id_user',
        'id_service',
    ];

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