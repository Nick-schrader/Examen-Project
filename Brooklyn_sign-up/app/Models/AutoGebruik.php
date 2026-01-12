<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoGebruik extends Model
{
    use HasFactory;

    protected $table = 'auto_gebruiken';

    protected $fillable = [
        'auto_id',
        'start_gebruik',
        'eind_gebruik',
    ];

    public function auto()
    {
        return $this->belongsTo(Auto::class, 'auto_id');
    }
}
