<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = ['name', 'type'];
    public function exteriorVehicles()
    {
        return $this->hasMany(Vehicle::class, 'exterior_color_id');
    }
    public function interiorVehicles()
    {
        return $this->hasMany(Vehicle::class, 'interior_color_id');
    }
}
