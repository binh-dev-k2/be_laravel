<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoupleNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('couple_notes', function (Blueprint $table) {
            $table->id();
            $table->uuid('couple_uuid');
            $table->string('title');
            $table->text('content');
            $table->dateTime('notice_time')->nullable();
            $table->tinyInteger('is_complete');
            $table->tinyInteger('is_repeat');
            $table->json('repeat_days')->nullable();
            $table->tinyInteger('repeat_hour')->nullable();
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
        Schema::dropIfExists('couple_notes');
    }
}
