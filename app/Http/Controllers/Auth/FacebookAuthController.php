<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class FacebookAuthController extends Controller
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        $facebook_user = Socialite::driver('facebook')->user();

        $user = User::updateOrCreate([
            'email'     => $facebook_user->getEmail(),
        ], [
            'name'      => $facebook_user->getName(),
            'email'     => $facebook_user->getEmail(),
            'avatar'    => $facebook_user->getAvatar(),
        ]);
     
        Auth::login($user);
     
        return redirect()->route('dashboard.index', ['tenant' => tenant('id')]);
    }
}
