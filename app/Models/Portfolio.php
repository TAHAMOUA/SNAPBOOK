<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Portfolio extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id_photo';
    public $incrementing = false;

    protected $keyType = 'string';
    protected $fillable = [
        'image',
        'description',
        'id_profile',
    ];

    protected static function booted(): void
    {
        static::creating(function (Portfolio $portfolio) {
            do {
                $id = 'PHT_' . strtoupper(\Illuminate\Support\Str::random(16));
            } while (Portfolio::where('id_photo', $id)->exists());

            $portfolio->id_photo = $id;
        });
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