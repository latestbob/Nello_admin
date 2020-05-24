<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePharmaciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pharmacies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid')->unique('pharmacy_uuid');
            $table->string('name');
            $table->string('address');
            $table->string('email');
            $table->string('phone', 16);
            $table->string('picture', 500)->nullable();
            $table->unsignedBigInteger('location_id')->nullable();
            $table->foreign('location_id')->references('id')
                ->on('locations')->onDelete('cascade')->onUpdate('cascade');
            $table->boolean('is_pick_up_location')->default(false);
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
        Schema::dropIfExists('pharmacies');
    }
}
