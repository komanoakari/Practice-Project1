<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluator_id',
        'evaluated_user_id',
        'order_id',
        'rating',
    ];

    public function evaluator() {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    public function evaluatedUser() {
        return $this->belongsTo(User::class, 'evaluated_user_id');
    }

    public function order() {
        return $this->belongsTo(Order::class);
    }
}
