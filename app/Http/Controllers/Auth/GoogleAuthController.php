<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $google_user = Socialite::driver('google')->user();

        $user = User::updateOrCreate([
            'email'     => $google_user->getEmail(),
        ], [
            'name'      => $google_user->getName(),
            'email'     => $google_user->getEmail(),
            'avatar'    => $google_user->getAvatar(),
        ]);
     
        Auth::login($user);
     
        return redirect()->route('dashboard.index', ['tenant' => tenant('id')]);
    }
}
