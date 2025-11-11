<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'body',
        'created_at',
        'image',
        'read_at',
    ];

    public function isUnread()
    {
        return is_null($this->read_at);
    }

    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function order() {
        return $this->belongsTo(Order::class);
    }
}
