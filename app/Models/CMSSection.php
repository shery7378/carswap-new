<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CMSSection extends Model
{
    protected $table = 'cms_sections';

    protected $fillable = [
        'name',
        'slug',
        'title',
        'subtitle',
        'description',
        'image',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(CMSItem::class, 'section_id')->orderBy('order');
    }
}
