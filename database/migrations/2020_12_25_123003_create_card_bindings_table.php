<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardBindingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_bindings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('expiration',255)->nullable();
            $table->string('cardholderName',255)->nullable();
            $table->string('approvalCode',255)->nullable();
            $table->string('pan',255)->nullable();
            $table->string('clientId',255)->nullable();
            $table->string('bindingId',255)->nullable();
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
        Schema::dropIfExists('card_bindings');
    }
}
