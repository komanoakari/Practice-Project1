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
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function transactionMessages()
    {
        return $this->hasMany(TransactionMessage::class);
    }

    public function unreadMessagesCount()
    {
        return $this->transactionMessages()
            ->where('user_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->count();
    }

    public function partner()
    {
        return $this->user_id === auth()->id()
            ? $this->product->user->profile
            : $this->user->profile;
    }
}
