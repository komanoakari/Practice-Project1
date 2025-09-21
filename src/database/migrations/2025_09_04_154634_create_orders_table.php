<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete()->unique();
            $table->integer('amount');
            $table->string('payment_method');
            $table->string('shipping_postal_code');
            $table->string('shipping_address');
            $table->string('shipping_building')->nullable();
            $table->string('status')->default('pending');
            $table->string('stripe_session_id')->nullable();
            $table->timestamps();
            $table->timestamp('paid_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
