<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicles extends Model
{
    use HasFactory;
    protected $primaryKey = 'vehicle_id';
    protected $fillable = array(
        'film_id',
        'vehicles_url'
    );
}
