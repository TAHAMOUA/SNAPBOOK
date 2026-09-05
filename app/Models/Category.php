<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $primaryKey = 'id_category';

    protected $fillable = [
        'category_name',
    ];

    public function services(): HasMany
    {
        return $this->hasMany(
            Service::class,
            'id_category',
            'id_category'
        );
    }
}