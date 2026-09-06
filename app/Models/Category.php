<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id_category';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'category_name',
    ];

    protected static function booted(): void
    {
        static::creating(function (Category $category) {
            do {
                $id = 'CAT_' . strtoupper(\Illuminate\Support\Str::random(16));
            } while (Category::where('id_category', $id)->exists());

            $category->id_category = $id;
        });
    }

    public function services(): HasMany
    {
        return $this->hasMany(
            Service::class,
            'id_category',
            'id_category'
        );
    }
}