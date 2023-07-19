<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MoviesController;
use App\Http\Controllers\Api\TmdbMoviesController;
use App\Models\TmdbMovies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::controller(AuthController::class)->group(function(){
    Route::post('/login','login');
    Route::post('/register','register');
});

Route::group(['middleware'=>['auth:sanctum']], function(){
   
    Route::controller(AuthController::class)->group(function(){
        Route::get('/user','index');
    });

    Route::controller(MoviesController::class)->group(function(){
        Route::post('/insert_films','insert_film');
        Route::get('/all_movies','view_all');
        Route::get('/search/{title}','search_by_title');
        Route::post('/update_movie/{id}','update');
        Route::get('/delete_movie/{film_id}','delete_film');
    });

    Route::controller(TmdbMoviesController::class)->group(function(){
        Route::post('/tmdb/movies/saveApiData','saveApiData');
        Route::get('/tmdb/movies/viewMovies','viewAllMovies');
        Route::get('/tmdb/movies/viewMovies/{title}','viewMoviesBytitle');
        Route::post('/tmdb/movies/deleteRecord/{tmdb_movie_id}','deleteRecord');
    });
    
});