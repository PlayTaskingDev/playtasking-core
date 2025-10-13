<x-guest-layout>
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
        <div class="w-full dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
            <h1 class="text-white text-4xl italic font-black my-4 text-center">{{ __('Log in') }}</h1>
            @if (get_app_setting('social_login_active') == true)
                <a href="{{ route('auth.facebook', ['tenant' => tenant('id')]) }}" class="btn-faceboock py-4 block w-full text-center my-4">
                    <img src="{{ Vite::asset('resources/images/icon-fb.svg') }}" class="inline-block mr-3" loading="lazy"
                        alt="Icon Facebook">
                    <div class="inline-block">{{ __('Use Facebook') }}</div>
                </a>
                <a href="{{ route('auth.google', ['tenant' => tenant('id')]) }}" class="btn-google py-4 block w-full text-center my-4">
                    <img src="{{ Vite::asset('resources/images/icon-google.png') }}" loading="lazy" alt="Icon Google"
                        class="w-6 h-6 inline-block mr-3">
                    <div class="inline-block text-black">{{ __('Use Google') }}</div>
                </a>
                <div class="divisor mt-4">
                    <div class="hr-login"></div>
                    <div class="div-block w-36">
                        <div class="text-white">o bien</div>
                    </div>
                    <div class="hr-login"></div>
                </div>
            @endif
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8 bg-white rounded-lg">
                <x-auth-session-status class="bg-white" :status="session('status')" />
                <form method="POST" action="{{ route('login', ['tenant' => tenant('id')]) }}">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                            :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Password')" />

                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                            autocomplete="current-password" />

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="block mt-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox"
                                class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                name="remember">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-center mt-4">
                        <x-primary-button class="ml-3">
                            {{ __('Log in') }}
                        </x-primary-button>
                    </div>
                    <div class="flex items-center justify-center mt-4">
                        @if (Route::has('password.request'))
                            <a class="underline font-bold text-sm text-black dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                                href="{{ route('password.request', ['tenant' => tenant('id')]) }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                    </div>
                    <div class="flex items-center justify-center mt-4">
                        @if (Route::has('password.request'))
                            <a class="underline font-bold text-sm text-black dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                                href="{{ route('register', ['tenant' => tenant('id')]) }}">
                                {{ __('Don\'t have an account?') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
