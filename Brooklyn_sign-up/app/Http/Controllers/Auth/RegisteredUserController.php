<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validatie van alle velden
        $request->validate([
            'naam' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'telefoon' => ['required', 'string', 'max:20'],
            'geboorte_datum' => ['required', 'date'],
            'geslacht' => ['required', 'string', 'in:man,vrouw'],
            'straat' => ['required', 'string', 'max:255'],
            'huisnummer' => ['required', 'string', 'max:10'],
            'postcode_stad' => ['required', 'string', 'max:255'],
        ]);

        // Adres samenstellen in het gewenste formaat
        $adres = $request->straat . ' -=- ' . $request->huisnummer . ' -=- ' . $request->postcode . ' -=- ' . $request->stad;


        // User aanmaken
        User::create([
            'naam' => $request->naam,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telefoon' => $request->telefoon,
            'geboorte_datum' => $request->geboorte_datum,
            'geslacht' => $request->geslacht,
            'adres' => $adres,
            'type' => 1,
        ]);


        // Event triggeren
        event(new Registered($user));

        // Automatisch inloggen
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
