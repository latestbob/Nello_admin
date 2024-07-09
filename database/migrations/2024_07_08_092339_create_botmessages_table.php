<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotmessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('botmessages', function (Blueprint $table) {
            $table->id();
            $table->string("wa_id")->nullable();
            $table->string('profile')->nullable();
            $table->boolean("isAuth")->nullable();
            $table->string("next_step")->nullable();
            $table->json("parent_param")->nullable();
            $table->string("quick_reply")->nullable();
            $table->integer("error")->default(0);


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
        Schema::dropIfExists('botmessages');
    }
}
