<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOwcappointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('owcappointments', function (Blueprint $table) {
            $table->id();
            $table->string('date');
            $table->string('time');
            $table->string('caretype');
            $table->string('user_firstname');
            $table->string('user_lastname');
            $table->string('email');
            $table->string('title');
            $table->string('dob');
            $table->string('phone');
            $table->string('gender');
            $table->string('duration')->nullable();
            $table->string('amount')->nullable();
            $table->string('completed')->nullable();
            $table->string('ref');
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
        Schema::dropIfExists('owcappointments');
    }
}
