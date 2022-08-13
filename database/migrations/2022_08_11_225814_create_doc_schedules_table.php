<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doc_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('doc_uuid');
            $table->string('specialization');
            $table->string('day');
            $table->string('time');
           
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
        Schema::dropIfExists('doc_schedules');
    }
}
