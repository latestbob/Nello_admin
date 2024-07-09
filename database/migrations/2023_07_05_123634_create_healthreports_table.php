<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHealthreportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('healthreports', function (Blueprint $table) {
            $table->id();
            // personal info

            $table->string("firstname");
            $table->string("lastname");
            $table->string("email");
            $table->string("dob");
            $table->string("gender");
            $table->timestamps();

            // vital signs


            $table->string("weight")->nullable();
            $table->string("height")->nullable();
            $table->string("temperature")->nullable();
            $table->string("pulse_rate")->nullable();
            $table->string("blood_pressure")->nullable();
            $table->string("respiratory_rate")->nullable();

            // symptoms

       
            $table->text("symptoms")->nullable();
            $table->text("history_of_complaints")->nullable();
            $table->string("allergies")->nullable();

            // diagnosis

            $table->string("diagnosis")->nullable();
            $table->string("other_diagnosis")->nullable();

            // procedures


            $table->text("laboratory")->nullable();
            $table->text("xray")->nullable();
            $table->text("ultrasound")->nullable();


            //prescription

            $table->text("prescriptions")->nullable();
            $table->string("prescribed_by")->nullable();


            //followup

            $table->boolean("require_followup")->nullable();
            $table->string("followup_date")->nullable();
            $table->string("followup_time")->nullable();


            //appointmentt ref

            $table->string("appointment_ref")->nullable();

            // doctor

            $table->string("doctor_uuid")->nullable();
            $table->string("doctor_firstname")->nullable();
            $table->string("doctor_lastname")->nullable();
            $table->string("doctor_title")->nullable();





        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('healthreports');
    }
}
