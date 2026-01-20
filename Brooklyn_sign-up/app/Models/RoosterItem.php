<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoosterItem extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'rooster_items';

    protected $fillable = [
        'leerling_id',
        'instructeur_id',
        'datum_en_tijd',
        'auto',
    ];

    public function leerling()
    {
        return $this->belongsTo(User::class, 'leerling_id');
    }

    public function instructeur()
    {
        return $this->belongsTo(User::class, 'instructeur_id');
    }

    public function autoItem()
    {
        return $this->belongsTo(Auto::class, 'auto');
    }

    public function verslag()
    {
        return $this->hasOne(Verslag::class, 'rooster_item_id');
    }

    public function review()
    {
        return $this->hasOne(Review::class, 'rooster_item_id');
    }
}
