<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Species extends Model
{
    use HasFactory;
    protected $primaryKey = 'specie_id';
    protected $fillable = array(
        'film_id',
        'species_url'
    );
}
