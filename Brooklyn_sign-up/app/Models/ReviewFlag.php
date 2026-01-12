<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewFlag extends Model
{
    use HasFactory;

    protected $table = 'review_flags';

    protected $fillable = [
        'review_id',
        'reason',
    ];

    public function review()
    {
        return $this->belongsTo(Review::class, 'review_id');
    }
}
