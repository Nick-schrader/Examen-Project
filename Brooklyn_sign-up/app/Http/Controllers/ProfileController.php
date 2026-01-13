<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\AdresUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $adresParts = explode(' -=- ', $request->user()->adres ?? '');

        if (is_array($adresParts) && count($adresParts) === 4) {
            $straat = $adresParts[0];
            $huisnummer = $adresParts[1];
            $postcode = $adresParts[2];
            $woonplaats = $adresParts[3];
        }

        return view('profile.edit', [
            'user' => $request->user(),
            'adres' => $adresParts,
            'straat' => $straat ?? null,
            'huisnummer' => $huisnummer ?? null,
            'postcode' => $postcode ?? null,
            'woonplaats' => $woonplaats ?? null,
        ]);
    }

    public function adresupdate(AdresUpdateRequest $request): RedirectResponse
    {
        $data = $request->validated();
        // Combine address fields into one string
        $adres = $data['straat'] . ' -=- ' . $data['huisnummer'] . ' -=- ' . $data['postcode'] . ' -=- ' . $data['woonplaats'];
        $user = $request->user();
        $user->adres = $adres;
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
