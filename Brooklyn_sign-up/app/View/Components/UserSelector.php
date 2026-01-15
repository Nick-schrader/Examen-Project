<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserSelector extends Component
{
    public $users;
    public $selectedUserId;
    public $selectedUser; // <-- toegevoegd

    public function __construct($selectedUserId = null)
    {
        // Gebruik de GET parameter 'user' als die bestaat
        $this->selectedUserId = request('user') ?? $selectedUserId ?? Auth::id();

        $currentUser = Auth::user();

        $this->users = match ($currentUser->type) {
            3 => User::where('type', 2)->get(), // admin → instructeurs
            2 => User::where('type', 1)->get(), // instructeur → leerlingen
            default => collect(),
        };

        // Nu haalt hij de gekozen gebruiker op
        $this->selectedUser = User::with('strippenkaart')->find($this->selectedUserId);
    }

    public function render()
    {
        return view('components.user-selector');
    }
}
