<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable();
                $table->json('description')->nullable();
                    $table->string('logo')->nullable();
                        $table->integer('order_by')->nullable();
                             $table->integer('country_id')->nullable();
                                $table->integer('state_id')->nullable();
                                    $table->integer('city_id')->nullable();
                                        $table->string('address')->nullable();
                                            $table->string('latitude')->nullable();
                                                $table->string('longitude')->nullable();
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
        Schema::dropIfExists('partners');
    }
}
