<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'naam',
        'email',
        'wachtwoord',
        'telefoon',
        'type',
        'geboorte_datum',
        'geslacht',
        'adres',
        'auto_preference',
    ];

    protected $hidden = [
        'wachtwoord',
        'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->wachtwoord;
    }

    public function strippenkaarten(): HasMany
    {
        return $this->hasMany(Strippenkaart::class, 'leerling_id');
    }

    public function kortingen(): HasMany
    {
        return $this->hasMany(Korting::class, 'leerling_id');
    }

    public function roosterItemsLeerling(): HasMany
    {
        return $this->hasMany(RoosterItem::class, 'leerling_id');
    }

    public function roosterItemsInstructeur(): HasMany
    {
        return $this->hasMany(RoosterItem::class, 'instructeur_id');
    }

    public function autoPreference(): BelongsTo
    {
        return $this->belongsTo(Auto::class, 'auto_preference');
    }
}
