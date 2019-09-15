<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->string('title');
            $table->text('description');
            $table->string('address');
            $table->string('city');
            $table->string('country');
            $table->string('state');
            $table->string('zip_code');
            $table->point('geolocation');

            // Add geolocation index
            $table->spatialIndex('geolocation');
            $table->index('title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
}
