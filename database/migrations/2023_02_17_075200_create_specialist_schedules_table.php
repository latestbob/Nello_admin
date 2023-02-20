<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialistSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specialist_schedules', function (Blueprint $table) {
            $table->id();
            
            

            /////////
            $table->string('doc_uuid');
            $table->string("specialization");
            $table->string("center");
            $table->string("date");
            $table->integer("fee")->nullable();
            $table->string("month");
            $table->string("monthstring");


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
        Schema::dropIfExists('specialist_schedules');
    }
}
