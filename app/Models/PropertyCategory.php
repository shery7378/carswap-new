<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyCategory extends Model
{
    protected $table = 'property_categories';
    protected $fillable = ['name', 'is_active'];

    public function properties()
    {
        return $this->hasMany(Property::class, 'property_category_id');
    }
}
