<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_reports', function (Blueprint $table) {
            $table->id();
            $table->string("ref");
            $table->text("symptoms");
            $table->string("other_symptoms")->nullable();
            $table->text("histor_of_compliants");
            $table->string("allergies");
            $table->text("diagnosis");
            $table->string("other_diagnosis")->nullable();
            $table->text("procedures")->nullable();
            $table->text("comments");
            $table->text("prescriptions")->nullable();
            $table->string("followup_date")->nullable();
            $table->string("followup_time")->nullable();
            $table->string("outcome")->nullable("");
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
        Schema::dropIfExists('medical_reports');
    }
}
