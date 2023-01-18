<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable();
            $table->double('rating')->nullable();
            $table->bigInteger('price')->nullable();
            $table->bigInteger('stock')->default(0)->nullable();
            $table->json('slug')->nullable();
            $table->string('SKU')->nullable();
            $table->json('description')->nullable();
            $table->json('details')->nullable();
            $table->json('how_to_use')->nullable();
            $table->string('main_image')->nullable();
            $table->longText('additional')->nullable();
            $table->integer('brand_id')->nullable();
            $table->bigInteger('orders')->default(0);
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
        Schema::dropIfExists('products');
    }
}
