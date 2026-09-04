<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhotographerProfile extends Model
{
    protected $primaryKey = 'id_profile';

    protected $fillable = [
        'bio',
        'city',
        'experience',
        'validation_status',
        'id_user',
    ];

    protected function casts(): array
    {
        return [
            'experience' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}