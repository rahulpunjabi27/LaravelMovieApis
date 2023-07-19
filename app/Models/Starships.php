<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Starships extends Model
{
    use HasFactory;
    protected $primaryKey = 'starship_id';
    protected $fillable = array(
        'film_id',
        'starship_url'
    );
}
