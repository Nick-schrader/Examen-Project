<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';

    protected $fillable = [
        'rooster_item_id',
        'rating',
        'comment',
        'status',
    ];

    public function roosterItem()
    {
        return $this->belongsTo(RoosterItem::class, 'rooster_item_id');
    }

    public function flags()
    {
        return $this->hasMany(ReviewFlag::class, 'review_id');
    }
}
