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
            $table->uuid('first_user_uuid')->nullable();
            $table->uuid('second_user_uuid')->nullable();
            $table->tinyInteger('status');
            $table->date('start_date');
            $table->string('nickname')->nullable();
            $table->string('header_title')->nullable();
            $table->uuid('saved_first_user_uuid');
            $table->uuid('saved_second_user_uuid');
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
