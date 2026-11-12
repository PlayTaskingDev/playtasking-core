@section('header_scripts')
    <link rel="stylesheet" href="{{ asset('css/participant-login.css') }}">
@endsection

<x-guest-layout>
    <div class="pt-login-page">
        <section class="pt-login-card" aria-labelledby="pt-login-title">
            <div class="pt-login-heading">
                <h1 id="pt-login-title" class="pt-login-title">{{ __('Log in') }}</h1>
                <p class="pt-login-subtitle">{{ get_app_setting('app_name') }}</p>
            </div>

            <x-auth-session-status class="mb-5" :status="session('status')" />

            @if (get_app_setting('social_login_active') == true)
                <div class="pt-login-social">
                    <a href="{{ route('auth.facebook', ['tenant' => tenant('id')]) }}" class="pt-login-social-link">
                        <img src="{{ Vite::asset('resources/images/icon-fb.svg') }}" alt="" loading="lazy">
                        <span>{{ __('Use Facebook') }}</span>
                    </a>
                    <a href="{{ route('auth.google', ['tenant' => tenant('id')]) }}" class="pt-login-social-link">
                        <img src="{{ Vite::asset('resources/images/icon-google.png') }}" alt="" loading="lazy">
                        <span>{{ __('Use Google') }}</span>
                    </a>
                </div>
                <div class="pt-login-divider"><span>o bien</span></div>
            @endif

            <form method="POST" action="{{ route('login', ['tenant' => tenant('id')]) }}">
                @csrf

                <div class="pt-login-field">
                    <label for="email" class="pt-login-label">{{ __('Email') }}</label>
                    <input id="email" class="pt-login-input" type="email" name="email"
                        value="{{ old('email') }}" required autofocus autocomplete="username"
                        aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                        @if ($errors->has('email')) aria-describedby="pt-login-email-error" @endif>
                    @error('email')
                        <p id="pt-login-email-error" class="pt-login-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-login-field">
                    <label for="password" class="pt-login-label">{{ __('Password') }}</label>
                    <input id="password" class="pt-login-input" type="password" name="password"
                        required autocomplete="current-password"
                        aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                        @if ($errors->has('password')) aria-describedby="pt-login-password-error" @endif>
                    @error('password')
                        <p id="pt-login-password-error" class="pt-login-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-login-row">
                    <label for="remember_me" class="pt-login-remember">
                        <input id="remember_me" type="checkbox" name="remember" @checked(old('remember'))>
                        <span>{{ __('Remember me') }}</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a class="pt-login-link" href="{{ route('password.request', ['tenant' => tenant('id')]) }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>

                <button class="pt-login-submit" type="submit">
                    <span>{{ __('Log in') }}</span>
                    <svg aria-hidden="true" width="19" height="19" fill="none" viewBox="0 0 24 24">
                        <path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </form>

            <div class="pt-login-register">
                <span>{{ __('Don\'t have an account?') }}</span>
                <a class="pt-login-link" href="{{ route('welcome', ['tenant' => tenant('id')]) }}">Regístrate</a>
            </div>
        </section>
    </div>
</x-guest-layout>
