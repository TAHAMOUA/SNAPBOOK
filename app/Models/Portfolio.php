<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Portfolio extends Model
{
    use HasFactory, SoftDeletes;

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
                $id = 'PHT_'.strtoupper(Str::random(16));
            } while (Portfolio::where('id_photo', $id)->exists());

            $portfolio->id_photo = $id;
        });

        // Only remove the physical image when the record is permanently deleted.
        // A normal soft delete keeps the image file.
        static::deleting(function (Portfolio $portfolio) {
            if ($portfolio->isForceDeleting()) {
                Storage::disk('public')->delete($portfolio->image);
            }
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
