<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('method','500')->nullable();
            $table->string('shipping','500')->nullable();
            $table->string('pickup_location','500')->nullable();
            $table->integer('total_count')->nullable();
            $table->double('amount')->nullable();
            $table->double('shipping_cost')->nullable();
            $table->json('delivery_address')->nullable();
            $table->string('delivery_time',255)->nullable();
            $table->string('delivered_on',255)->nullable();
            $table->string('delivery_status',255)->nullable();
            $table->text('order_note')->nullable();
            $table->string('coupon_code')->nullable();
            $table->string('coupon_discount')->nullable();
            $table->string('currency')->nullable();
            $table->integer('affiliate_user')->nullable();
            $table->double('affiliate_charge')->nullable();
            $table->double('currency_rate')->nullable();
            $table->integer('tax')->nullable();
            $table->string('status','500')->nullable();
            $table->text('details')->nullable();
            $table->string('payment_id')->nullable();
            $table->string('order_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
