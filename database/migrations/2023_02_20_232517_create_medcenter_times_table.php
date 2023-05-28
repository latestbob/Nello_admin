<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedcenterTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medcenter_times', function (Blueprint $table) {
            $table->id();
            $table->string("date");
            $table->string("date_word")->nullable();
            $table->string("time");
            $table->string("specialization")->nullable();
            $table->string("center_uuid");
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
        Schema::dropIfExists('medcenter_times');
    }
}
