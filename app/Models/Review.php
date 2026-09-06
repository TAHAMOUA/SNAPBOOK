<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id_review';
    public $incrementing = false;
    protected $keyType = 'string';


    protected $fillable = [
        'rating',
        'comment',
        'review_date',
        'id_user',
        'id_profile',
    ];

    protected static function booted(): void
    {
        static::creating(function (Review $review) {
            do {
                $id = 'REV_' . strtoupper(\Illuminate\Support\Str::random(16));
            } while (Review::where('id_review', $id)->exists());

            $review->id_review = $id;
        });
    }
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