<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'payment_method',
        'shipping_postal_code',
        'shipping_address',
        'shipping_building',
        'amount',
        'status',
        'stripe_session_id',
        'paid_at',
    ];

    public function user() {
        return $this->belongTo(User::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
