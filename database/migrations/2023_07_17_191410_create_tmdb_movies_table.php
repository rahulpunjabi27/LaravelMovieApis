<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tmdb_movies', function (Blueprint $table) {
            $table->id('tmdb_movie_id');
            $table->integer('movie_id');
            $table->integer('page');
            $table->string('adult');
            $table->text('backdrop_path');
            $table->string('genre_ids');
            $table->string('original_language');
            $table->string('original_title');
            $table->text('overview');
            $table->double('popularity');
            $table->string('poster_path');
            $table->date('release_date');
            $table->string('title');
            $table->string('video');
            $table->double('vote_average');
            $table->integer('vote_count');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tmdb_movies');
    }
};
