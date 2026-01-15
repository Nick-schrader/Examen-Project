<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auto extends Model
{
    use HasFactory;

    protected $table = 'auto';
    
    public $timestamps = false;

    protected $fillable = [
        'kenteken',
        'merk',
        'type',
        'beschikbaar',
        'foto',
    ];

    public function roosterItems()
    {
        return $this->hasMany(RoosterItem::class, 'auto');
    }

    public function autoGebruiken()
    {
        return $this->hasMany(AutoGebruik::class, 'auto_id');
    }

    public function accounts()
    {
        return $this->hasMany(User::class, 'auto_preference');
    }
}