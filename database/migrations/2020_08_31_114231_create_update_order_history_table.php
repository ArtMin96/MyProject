<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpdateOrderHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_histories', function (Blueprint $table) {
         $table->json('description')->after('price')->nullable();
        });
    }

    /**
     *
     */
    public function down()
    {
        Schema::table('order_histories',function (Blueprint $table){

        });
    }
}
