<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorsPrescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctors_prescriptions', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique('doctors_prescriptions_uuid');
            $table->string('cart_uuid')->unique('doctors_prescriptions_cart_uuid');
            $table->bigInteger('drug_id');
            $table->string('dosage');
            $table->string('note');
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('vendor_id');
            $table->foreign('doctor_id')->references('id')
                ->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('vendor_id')->references('id')
                ->on('vendors')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('doctors_prescriptions');
    }
}
