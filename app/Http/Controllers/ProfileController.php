<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Traits\UploadImageTrait;

class ProfileController extends Controller
{
    use UploadImageTrait;
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $city = json_decode($request->user()['extra_info']);
        $user['city'] = (($city == null) ? '': $city->city);
        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        //$data = $request->validated();
        $data = $request->all();
        if(!isset($data['city']) && empty($data['city'])){
            $data['city'] = '';
        }
        $extra_info = ["city" => $data['city']]; 

        if($request->file('avatar')){
            $data['avatar'] = $this->uploadImage('gcs','avatars',$request->file('avatar'));
        }

        $request->user()->fill($data);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }
        $request->user()['extra_info'] = json_encode($extra_info);
        $request->user()->save();

        return Redirect::route('profile.edit', ['tenant' => tenant('id')])->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [ 
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
