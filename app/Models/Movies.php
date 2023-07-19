<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movies extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $fillable = array(
        'film_id',
        'title',
        'episode_id',
        'opening_crawl',
        'director',
        'producer',
        'release_date',
        'url'
    );

    public function characters(){
        return $this->hasMany(Characters::class,'film_id','film_id');
    }

    public function planets(){
        return $this->hasMany(Planets::class,'film_id','film_id');
    }

    public function speies(){
        return $this->hasMany(Species::class,'film_id','film_id');
    }

    public function starships(){
        return $this->hasMany(Starships::class,'film_id','film_id');
    }

    public function vehicles(){
        return $this->hasMany(vehicles::class,'film_id','film_id');
    }

}
