<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsReadyColumnToCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->boolean('is_ready')->default(false)->after('status');
            $table->unsignedBigInteger('is_ready_by')->after('is_ready')->nullable();
            $table->foreign('is_ready_by', 'carts_is_ready_by_foreign')->references('id')
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
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign('carts_is_ready_by_foreign');
            $table->dropColumn(['is_ready', 'is_ready_by']);
        });
    }
}
