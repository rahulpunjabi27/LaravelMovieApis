<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Characters;
use App\Models\Movies;
use App\Models\Planets;
use App\Models\Species;
use App\Models\Starships;
use App\Models\Vehicles;
use Illuminate\Support\Facades\Validator;

class MoviesController extends Controller
{
    
    public function insert_film(Request $request){
        $validator = Validator::make($request->all(),[
            'film_id' => ['required', 'integer','unique:movies'],
        ]);
        if($validator->fails()){
            $response = [
                'code' => 401,
                'error' => true,
                'data' => $validator->messages()
            ];
            return response()->json($response);
        }
        $url            = env('FILMS_URL').$request->film_id;
        $fetch_url      = file_get_contents($url);
        $url_response   = json_decode($fetch_url,true);

        $Movies = Movies::create([
            'film_id'       => $request->film_id,
            'title'         => $url_response['title'],
            'episode_id'    => $url_response['episode_id'],
            'opening_crawl' => $url_response['opening_crawl'],
            'director'      => $url_response['director'],
            'producer'      => $url_response['producer'],
            'release_date'  => $url_response['release_date'],
            'url'           => $url_response['url']
        ]);
        
        foreach($url_response['characters'] as $characters_data){            
            Characters::create([
                'film_id'       => $request->film_id,
                'character_url' => $characters_data,
            ]);
        }

        foreach($url_response['planets'] as $planets_data){
            Planets::create([
                'film_id'       => $request->film_id,
                'planet_url'    => $planets_data,
            ]);
        }

        foreach($url_response['starships'] as $starship_data){
            Starships::create([
                'film_id'       => $request->film_id,
                'starship_url'  => $starship_data
            ]);
        }

        foreach($url_response['vehicles'] as $vehicle_data){
            Vehicles::create([
                'film_id'       => $request->film_id,
                'vehicles_url'  => $vehicle_data
            ]);
        }

        foreach($url_response['species'] as $specie_data){
            Species::create([
                'film_id'       => $request->film_id,
                'species_url'   => $specie_data
            ]);
        }

        $response= [
            'status' => 200,
            'success' => true,
            'data'  => array('message' => 'Movie Insert Successfully...!')
        ];

        return response()->json($response);
        
    }

    public function view_all(){
        $Movies = Movies::with(['characters','planets','speies','starships','vehicles'])->get();
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

    public function search_by_title(Request $request,$title){
        $title = isset($request->title)?$request->title:$title;
        $Movies = Movies::with(['characters','planets','speies','starships','vehicles'])
                    ->whereRaw("title LIKE '%$title%' ")
                    ->get();
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

    public function update(Request $request , $id){
        $validator = Validator::make($request->all(),[
            'title'             => ['required','string'],
            'episode_id'        => ['required','integer'],
            'opening_crawl'     => ['required','string'],
            'director'          => ['required','string'],
            'producer'          => ['required','string'],
            'release_date'      => ['required'],
        ]);
        if($validator->fails()){
            $response = [
                'code' => 401,
                'error' => true,
                'data' => $validator->messages()
            ];
            return response()->json($response);
        }

        $Movies = Movies::find('id', $id);
        $Movies->title          = $request->title;
        $Movies->espisode_id    = $request->episode_id;
        $Movies->opening_crawl  = $request->opening_crawl;
        $Movies->director       = $request->director;
        $Movies->producer       = $request->producer;
        $Movies->release_date   = $request->release_date;
        $Movies->save();

        $response = [
            'status'    => 200,
            'success'   => true,
            'data'      => array('message' => 'Movie Update Successfully...!')
        ];
        return response()->json($response);
    }

    public function delete_film($film_id){
        $Movies = Movies::where('film_id',$film_id)->get();
        if(count($Movies) > 0){
            Movies::where('film_id',$film_id)->delete();
            Planets::where('film_id',$film_id)->delete();
            Species::where('film_id',$film_id)->delete();
            Characters::where('film_id',$film_id)->delete();
            Starships::where('film_id',$film_id)->delete();
            Vehicles::where('film_id',$film_id)->delete();
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
