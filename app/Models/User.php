<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['first_name','last_name','email','phone','password','role',])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $primaryKey = 'id_user';

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            do {
                $id = 'USR_' . strtoupper(\Illuminate\Support\Str::random(16));
            } while (User::where('id_user', $id)->exists());

            $user->id_user = $id;
        });
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function photographerProfile(): HasOne
    {
        return $this->hasOne(PhotographerProfile::class, 'id_user', 'id_user');
    }
    public function bookings(): HasMany
    {
        return $this->hasMany(
            Booking::class,
            'id_user',
            'id_user'
        );
    }
    public function reviews(): HasMany
    {
        return $this->hasMany(
            Review::class,
            'id_user',
            'id_user'
        );
    }
}
