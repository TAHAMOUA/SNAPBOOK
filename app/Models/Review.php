<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $primaryKey = 'id_review';

    protected $fillable = [
        'rating',
        'comment',
        'review_date',
        'id_user',
        'id_profile',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'review_date' => 'datetime',
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

    public function photographerProfile(): BelongsTo
    {
        return $this->belongsTo(
            PhotographerProfile::class,
            'id_profile',
            'id_profile'
        );
    }
}