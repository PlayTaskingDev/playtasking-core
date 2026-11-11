<x-guest-layout>
    <style>
        /* Rediseño aislado del login: no modifica otras pantallas guest. */
        .redesign-login {
            position: relative;
            isolation: isolate;
            min-height: calc(100vh - 5rem);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 1rem 4rem;
        }
        .redesign-login::before {
            content: "";
            position: absolute;
            z-index: -1;
            width: min(100%, 680px);
            height: min(75vh, 660px);
            background: radial-gradient(ellipse, rgba(132, 72, 211, .24), transparent 68%);
            filter: blur(30px);
            pointer-events: none;
        }
        .redesign-login__card {
            width: 100%;
            max-width: 460px;
            padding: clamp(1.5rem, 5vw, 2.5rem);
            border: 1px solid rgba(255, 255, 255, .32);
            border-radius: 28px;
            color: #fff;
            background: linear-gradient(145deg, rgba(23, 19, 43, .79), rgba(31, 22, 49, .65));
            box-shadow: 0 24px 72px rgba(10, 5, 30, .34), 0 0 48px rgba(143, 89, 235, .16), inset 0 1px rgba(255, 255, 255, .12);
            -webkit-backdrop-filter: blur(22px);
            backdrop-filter: blur(22px);
        }
        .redesign-login__title {
            margin: 0;
            text-align: center;
            font-size: clamp(1.8rem, 5vw, 2.3rem);
            font-weight: 800;
            letter-spacing: -.035em;
            line-height: 1.15;
        }
        .redesign-login__subtitle {
            margin: .65rem 0 1.75rem;
            color: #ddd5eb;
            text-align: center;
            font-size: .9rem;
        }
        .redesign-login__label {
            display: block;
            margin-bottom: .5rem;
            color: #f3edfc;
            font-size: .86rem;
            font-weight: 600;
        }
        .redesign-login__input {
            display: block;
            width: 100%;
            min-height: 48px;
            padding: .75rem 1rem;
            border: 1px solid rgba(255, 255, 255, .30);
            border-radius: 13px;
            color: #fff;
            background: rgba(255, 255, 255, .10);
            transition: border-color .2s ease, background-color .2s ease, box-shadow .2s ease;
        }
        .redesign-login__input:focus {
            outline: none;
            border-color: #d6b6ff;
            background: rgba(255, 255, 255, .14);
            box-shadow: 0 0 0 3px rgba(198, 155, 255, .22);
        }
        .redesign-login__input::placeholder { color: #d0c7da; }
        .redesign-login__field + .redesign-login__field { margin-top: 1.15rem; }
        .redesign-login__remember {
            display: flex;
            align-items: center;
            gap: .65rem;
            margin-top: 1.15rem;
            color: #eee6f7;
            font-size: .85rem;
        }
        .redesign-login__remember input { accent-color: #aa7bfa; }
        .redesign-login__submit { margin-top: 1.5rem; }
        .redesign-login__submit button {
            width: 100%;
            min-height: 48px;
            border-radius: 13px;
            font-size: .95rem;
            box-shadow: 0 8px 26px rgba(115, 59, 179, .25);
            transition: transform .2s ease, filter .2s ease;
        }
        .redesign-login__submit button:hover { transform: translateY(-1px); filter: brightness(1.08); }
        .redesign-login__links {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: .8rem 1.25rem;
            margin-top: 1.5rem;
            text-align: center;
        }
        .redesign-login__links a { color: #f4e8ff; font-size: .84rem; font-weight: 600; text-decoration: underline; text-underline-offset: 4px; }
        .redesign-login__links a:hover { color: #fff; }
        .redesign-login__social { display: grid; gap: .75rem; margin-bottom: 1.4rem; }
        .redesign-login__social a {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .7rem;
            min-height: 46px;
            border-radius: 12px;
            font-weight: 700;
            text-align: center;
        }
        .redesign-login__social img { width: 24px; height: 24px; object-fit: contain; }
        .redesign-login__divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 0 0 1.4rem;
            color: #e6dcef;
            font-size: .8rem;
            text-align: center;
        }
        .redesign-login__divider::before, .redesign-login__divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, .25);
        }
        .redesign-login :is(a, button, input):focus-visible {
            outline: 2px solid #e1c4ff;
            outline-offset: 3px;
        }
        @media (max-width: 480px) {
            .redesign-login { padding: 1.5rem .85rem 3rem; }
            .redesign-login__card { border-radius: 22px; }
        }
        @media (prefers-reduced-motion: reduce) {
            .redesign-login__submit button, .redesign-login__input { transition: none; }
        }
    </style>

    <div class="redesign-login">
        <div class="redesign-login__card">
            <h1 class="redesign-login__title">{{ __('Log in') }}</h1>
            <p class="redesign-login__subtitle">{{ get_app_setting('app_name') }}</p>

            @if (get_app_setting('social_login_active') == true)
                <div class="redesign-login__social">
                    <a href="{{ route('auth.facebook', ['tenant' => tenant('id')]) }}" class="btn-faceboock">
                        <img src="{{ Vite::asset('resources/images/icon-fb.svg') }}" loading="lazy" alt="">
                        <span>{{ __('Use Facebook') }}</span>
                    </a>
                    <a href="{{ route('auth.google', ['tenant' => tenant('id')]) }}" class="btn-google">
                        <img src="{{ Vite::asset('resources/images/icon-google.png') }}" loading="lazy" alt="">
                        <span class="text-black">{{ __('Use Google') }}</span>
                    </a>
                </div>
                <div class="redesign-login__divider"><span>o bien</span></div>
            @endif

            <x-auth-session-status :status="session('status')" />

            <form method="POST" action="{{ route('login', ['tenant' => tenant('id')]) }}">
                @csrf

                <div class="redesign-login__field">
                    <label for="email" class="redesign-login__label">{{ __('Email') }}</label>
                    <x-text-input id="email" class="redesign-login__input" type="email" name="email"
                        :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="redesign-login__field">
                    <label for="password" class="redesign-login__label">{{ __('Password') }}</label>
                    <x-text-input id="password" class="redesign-login__input" type="password" name="password"
                        required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <label for="remember_me" class="redesign-login__remember">
                    <input id="remember_me" type="checkbox" name="remember" class="rounded">
                    <span>{{ __('Remember me') }}</span>
                </label>

                <div class="redesign-login__submit">
                    <x-primary-button>{{ __('Log in') }}</x-primary-button>
                </div>

                <div class="redesign-login__links">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request', ['tenant' => tenant('id')]) }}">{{ __('Forgot your password?') }}</a>
                        <a href="{{ route('welcome', ['tenant' => tenant('id')]) }}">{{ __("Don't have an account?") }}</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
