<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouplesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('couples', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->uuid('sender_uuid')->nullable();
            $table->uuid('receiver_uuid')->nullable();
            $table->tinyInteger('status');
            $table->dateTime('start_time');
            $table->string('nickname')->nullable();
            $table->string('header_title')->nullable();
            $table->uuid('saved_sender_uuid');
            $table->uuid('saved_receiver_uuid');
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
        Schema::dropIfExists('couples');
    }
}
