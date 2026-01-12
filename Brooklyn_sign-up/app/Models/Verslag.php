<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verslag extends Model
{
    use HasFactory;

    protected $table = 'verslagen';

    protected $fillable = [
        'rooster_item_id',
        'verslag',
        'datum_gemaakt',
        'datum_aangepast',
    ];

    public function roosterItem()
    {
        return $this->belongsTo(RoosterItem::class, 'rooster_item_id');
    }
}
