<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVendorToPharmacyDrugsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pharmacy_drugs', function (Blueprint $table) {
            //
            $table->string('vendor')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pharmacy_drugs', function (Blueprint $table) {
            //
            $table->dropColumn('vendor');
        });
    }
}
