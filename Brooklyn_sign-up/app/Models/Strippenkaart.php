<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Strippenkaart extends Model
{
    use HasFactory;

    protected $table = 'strippenkaarten';

    protected $fillable = [
        'leerling_id',
        'tegoed',
        'verval_datum',
    ];

    public function leerling()
    {
        return $this->belongsTo(User::class, 'leerling_id');
    }
}
