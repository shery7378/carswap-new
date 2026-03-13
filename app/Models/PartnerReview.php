<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'rating',
        'title',
        'body',
        'reviewer_name',
        'reviewer_email',
        'is_approved',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}
