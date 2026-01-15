<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LesItem extends Model
{
    use HasFactory;

    protected $table = 'rooster_items'; // Zorg dat de juiste tabelnaam wordt gebruikt

    public function leerling()
    {
        return $this->belongsTo(User::class, 'leerling_id');
    }

    public function instructeur()
    {
        return $this->belongsTo(User::class, 'instructeur_id');
    }

    public function auto()
    {
        return $this->belongsTo(Auto::class, 'auto');
    }
}
