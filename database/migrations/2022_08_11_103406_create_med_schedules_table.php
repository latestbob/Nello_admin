<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('med_schedules', function (Blueprint $table) {
            $table->id();

            $table->string('med_uuid');
            $table->string('specialization');
            $table->string('day');
            $table->string('time');
            $table->string('state');
            $table->string('lga');
            $table->boolean('isbooked')->nullable();
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
        Schema::dropIfExists('med_schedules');
    }
}
