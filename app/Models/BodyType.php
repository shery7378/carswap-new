<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BodyType extends Model
{
    protected $fillable = ['name', 'image'];
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}
