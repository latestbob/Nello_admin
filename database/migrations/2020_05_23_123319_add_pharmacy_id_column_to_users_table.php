<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPharmacyIdColumnToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('pharmacy_id')->after('vendor_id')->nullable();
            $table->foreign('pharmacy_id', 'users_pharmacy_id_foreign')->references('id')
                ->on('pharmacies')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_pharmacy_id_foreign');
            $table->dropColumn('pharmacy_id');
        });
    }
}
