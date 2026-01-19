<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auto extends Model
{
    use HasFactory;

    protected $table = 'auto';
<<<<<<< HEAD
=======
    
    public $timestamps = false;
>>>>>>> 303847d0f4a535bc8a210635339fd5905cc97fd0

    protected $fillable = [
        'kenteken',
        'merk',
        'type',
        'beschikbaar',
<<<<<<< HEAD
        'created_at',
        'updated_at',
=======
        'foto',
>>>>>>> 303847d0f4a535bc8a210635339fd5905cc97fd0
    ];

    public function roosterItems()
    {
        return $this->hasMany(RoosterItem::class, 'auto');
    }

    public function autoGebruiken()
    {
        return $this->hasMany(AutoGebruik::class, 'auto_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'auto_preference');
    }
}