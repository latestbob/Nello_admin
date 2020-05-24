<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('admins');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique('admin_uuid')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 15)->unique();
            $table->string('address')->nullable();
            $table->string('picture', 500);
            $table->unsignedBigInteger('vendor_id');
            $table->string('password');
            $table->string('admin_type', 20)->default('admin');
            $table->foreign('vendor_id')->references('id')
                ->on('vendors')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('location_id')->nullable();
            $table->foreign('location_id')->references('id')
                ->on('locations')->onDelete('cascade')->onUpdate('cascade');
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
        });
    }
}
