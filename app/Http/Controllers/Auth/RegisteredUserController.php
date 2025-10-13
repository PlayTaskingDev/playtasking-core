<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

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
        $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'email_confirmation'    => ['required', 'string', 'email', 'max:255', 'same:email'],
            'phone'                 => ['nullable', 'digits:10'],
            'checkbox_terms'        => ['required'],
            'checkbox_privacy'      => ['required'],
            'password'              => ['required', Rules\Password::defaults()],
            'members_number'        => [Rule::requiredIf(get_app_setting('members_number')), 'string', 'max:16'],
            'g-recaptcha-response'  => 'required|recaptchav3:register,0.5'
        ]);

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'phone'             => $request->phone,
            'members_number'    => $request->members_number,
            'password'          => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('welcome', ['tenant' => tenant('id')]);
    }
}
