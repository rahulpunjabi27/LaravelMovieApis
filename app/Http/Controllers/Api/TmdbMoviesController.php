<?php

namespace App\Http\Controllers\Api;

use GuzzleHttp\Client;
use App\Models\TmdbMovies;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TmdbMoviesController extends Controller
{
    
    public function GetApiData(){
        $clinet = new Client();
        $response = $clinet->request('GET','https://api.themoviedb.org/3/discover/movie',array(
            'headers' => [
                'Authorization' => 'Bearer '.env('TMDB_API_KEY'),
                'accept' => 'application/json',
              ],
        ));
        return response()->json($response->getBody());
    }

    public function SaveApiData(Request $request){
        $validator = Validator::make($request->all(),[
            'page' => ['required', 'integer','unique:tmdb_movies'],
        ]);
        if($validator->fails()){
            $response = [
                'code' => 401,
                'error' => true,
                'data' => $validator->messages()
            ];
            return response()->json($response);
        }
        $page = $request->page;
        $clinet = new Client();
        $response = $clinet->request('GET',"https://api.themoviedb.org/3/discover/movie?page=$page",array(
            'headers' => [
                'Authorization' => 'Bearer '.env('TMDB_API_KEY'),
                'accept' => 'application/json',
              ],
        ));
        $data = json_decode($response->getBody(),true);
        foreach($data['results'] as $value){    
            TmdbMovies::create([
                'movie_id'          => $value['id'],
                'page'              => $data['page'],
                'adult'             => $value['adult'],
                'backdrop_path'     => $value['backdrop_path'],
                'genre_ids'         => implode("",$value['genre_ids']),
                'original_language' => $value['original_language'],
                'original_title'    => $value['original_title'],
                'overview'          => $value['overview'],
                'popularity'        => $value['popularity'],
                'poster_path'       => $value['poster_path'],
                'release_date'      => $value['release_date'],
                'title'             => $value['title'],
                'video'             => $value['video'],
                'vote_average'      => $value['vote_average'],
                'vote_count'        => $value['vote_count']
            ]);

        }
        $response= [
            'status' => 200,
            'success' => true,
            'data'  => array('message' => 'Movie Insert Successfully...!')
        ];

        return response()->json($response);

    }

    public function viewAllMovies(){
        $Movies = TmdbMovies::select('*')->get();
        if(count($Movies) > 0 ){
            $response= [
                'status'    => 200,
                'success'   => true,
                'data'      => $Movies,
            ];
            return response()->json($response);
        }
        $response = [
            'status'    => 401,
            'error'     => true,
            'data'      => array('message' => 'No Record Found..!')
        ];
        return response()->json($response);
    }

    public function viewMoviesBytitle(Request $request, $title){
        $title = isset($request->title)?$request->title:$title;
        $Movies = TmdbMovies::whereRaw("title LIKE '%$title%'")->get();
        if(count($Movies) > 0 ){
            $response= [
                'status'    => 200,
                'success'   => true,
                'data'      => $Movies,
            ];
            return response()->json($response);
        }
        $response = [
            'status'    => 401,
            'error'     => true,
            'data'      => array('message' => 'No Record Found..!')
        ];
        return response()->json($response);
    }

    public function deleteRecord(Request $request,$tmdb_movie_id){
        $tmdb_movie_id = isset($request->tmdb_movie_id)?$request->tmdb_movie_id:$tmdb_movie_id;
        $Movies = TmdbMovies::where('film_id',$tmdb_movie_id)->get();
        if(count($Movies) > 0){
            TmdbMovies::where('film_id',$tmdb_movie_id)->delete();
            $response = [
                'status'    => 200,
                'success'     => true,
                'data'      => array('message' => 'Record Deleted Successfully...!')
            ];
            return response()->json($response);    
        }
        $response = [
            'status'    => 401,
            'error'     => true,
            'data'      => array('message' => 'Record Not Found...!')
        ];
        return response()->json($response);
    }

    
}
