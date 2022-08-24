<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_appointments', function (Blueprint $table) {
            $table->id();
            $table->string('temp_id');
            $table->string('phone');
            $table->string('doc_uuid');
            $table->string('reason');
            $table->string('date');
            $table->string('time');
            $table->string('fee');
            
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
        Schema::dropIfExists('temp_appointments');
    }
}
