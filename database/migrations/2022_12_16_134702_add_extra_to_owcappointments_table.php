<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraToOwcappointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('owcappointments', function (Blueprint $table) {
            //

            $table->string("type")->nullable();
            $table->string("doctor")->nullable();
            $table->string("link")->nullable();
            $table->string("payment_ref")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('owcappointments', function (Blueprint $table) {
            //
        });
    }
}
