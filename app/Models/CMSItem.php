<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CMSItem extends Model
{
    protected $table = 'cms_items';
    
    protected $fillable = [
        'section_id',
        'title',
        'description',
        'icon',
        'image',
        'link',
        'date',
        'order',
        'status',
    ];

    public function section()
    {
        return $this->belongsTo(CMSSection::class, 'section_id');
    }
}
