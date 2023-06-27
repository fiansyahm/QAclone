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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('no')->default(NULL)->nullable();
            $table->string('title')->default(NULL)->nullable();
            $table->string('publication')->default(NULL)->nullable();
            $table->string('index')->default(NULL)->nullable();
            $table->string('quartile')->default(NULL)->nullable();
            $table->integer('year')->default(NULL)->nullable();
            $table->string('authors')->default(NULL)->nullable();
            $table->text('abstracts')->default(NULL)->nullable();
            $table->text('keywords')->default(NULL)->nullable();
            $table->string('language')->default(NULL)->nullable();
            $table->string('type')->default(NULL)->nullable();
            $table->string('publisher')->default(NULL)->nullable();
            $table->text('references_ori')->default(NULL)->nullable();
            $table->text('references_filter')->default(NULL)->nullable();
            $table->string('cited')->default(NULL)->nullable();
            $table->string('cited_gs')->default(NULL)->nullable();
            $table->string('citing')->default(NULL)->nullable();
            $table->string('citing_new')->default(NULL)->nullable();
            $table->string('keyword')->default(NULL)->nullable();
            $table->string('edatabase')->default(NULL)->nullable();
            $table->string('edatabase_2')->default(NULL)->nullable();
            $table->string('nation_first_author')->nullable();
            $table->string('file')->nullable();
            $table->string('link_articles')->nullable();
            $table->foreignId('project_id');
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
        Schema::dropIfExists('articles');
    }
};
