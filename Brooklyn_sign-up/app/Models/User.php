<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'naam',
        'email',
        'telefoon',
        'type',
        'geboorte_datum',
        'geslacht',
        'adres',
        'auto_preference',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function strippenkaart()
    {
        return $this->hasOne(Strippenkaart::class, 'leerling_id', 'id');
    }


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];  
    }

    /**
     * Check if the user is an eigenaar.
     *
     * @return bool
     */
    public function isEigenaar(): bool
    {
        return $this->type === 3;
    }
}
