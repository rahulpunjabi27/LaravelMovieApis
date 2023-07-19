<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planets extends Model
{
    use HasFactory;
    protected $primaryKey = 'planet_id';
    protected $fillable = array(
        'film_id',
        'planet_url'
    );
}
