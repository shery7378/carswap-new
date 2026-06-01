<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $fillable = ['name', 'property_category_id'];
    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class);
    }
    public function category()
    {
        return $this->belongsTo(PropertyCategory::class, 'property_category_id');
    }
}
