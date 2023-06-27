<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_user_questionaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_user_id')->references('id')->on('article_users');
            $table->foreignId('questionaire_id')->references('id')->on('questionaires');
            $table->integer('score')->nullable();
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
        Schema::dropIfExists('article_user_questionaires');
    }
};
