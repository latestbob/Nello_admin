<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFollowupDoctorIdToHealthreportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('healthreports', function (Blueprint $table) {
            //
            $table->string("followup_doctorId")->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('healthreports', function (Blueprint $table) {
            //

            $table->dropColumn("followup_doctorId");
        });
    }
}
