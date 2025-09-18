<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Profile;

class User extends Authenticatable implements MustVerifyEmailContract
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::created(function ($user) {
                $user->profile()->create([
                    'user_name'   => $user->name, 
                    'postal_code' => '',
                    'address'     => '',
                    'building'    => '',
            ]);
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class)
            ->withDefault([
                'user_name' => $this->name,
                'postal_code' => '',
                'address' => '',
                'building' => '',
            ]);
    }

    public function mylistProducts()
    {
        return $this->belongsToMany(Product::class, 'mylist', 'user_id', 'product_id')->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
