<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Korting extends Model
{
    use HasFactory;

    protected $table = 'korting';

    protected $fillable = [
        'leerling_id',
        'percentage',
        'reason',
        'created_at',
        'updated_at',
    ];

    public function leerling()
    {
        return $this->belongsTo(User::class, 'leerling_id');
    }
}
