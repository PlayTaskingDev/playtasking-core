<x-guest-layout>
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
        <div class="w-full dark:border md:my-5 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
            <div class="bienvenida-login">
                <h1 class="text-white text-4xl italic font-black my-4 text-center">{{ __('Register') }}</h1>
                @if (get_app_setting('social_login_active') == true)
                <a href="{{ route('auth.facebook', ['tenant' => tenant('id')]) }}" class="btn-faceboock py-4 block w-full text-center my-4">
                    <img src="{{ Vite::asset('resources/images/icon-fb.svg') }}" class="inline-block mr-3" loading="lazy"
                        alt="Icon Facebook">
                    <div class="inline-block">Usar Facebook</div>
                </a>
                <a href="{{ route('auth.google', ['tenant' => tenant('id')]) }}" class="btn-google py-4 block w-full text-center my-4">
                    <img src="{{ Vite::asset('resources/images/icon-google.png') }}" loading="lazy" alt="Icon Google"
                        class="w-6 h-6 inline-block mr-3">
                    <div class="inline-block text-black">Usar Google</div>
                </a>
                <div class="divisor my-4">
                    <div class="hr-login"></div>
                    <div class="div-block w-36">
                        <div class="text-white">o bien</div>
                    </div>
                    <div class="hr-login"></div>
                </div>
                @endif
            </div>
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8 bg-white rounded-lg">
                <form method="POST" action="{{ route('register', ['tenant' => tenant('id')]) }}">
                    @csrf
                    {!! RecaptchaV3::field('register') !!}
                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="get_app_setting('reg_form_name_label')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                            :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div class="mt-4">
                        <x-input-label for="email" :value="get_app_setting('reg_form_email_label')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                            :value="old('email')" required autocomplete="email" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div class="mt-4">
                        <x-input-label for="email_confirmation" :value="get_app_setting('reg_form_email_conf_label')" />
                        <x-text-input id="email_confirmation" class="block mt-1 w-full" type="email" name="email_confirmation"
                            :value="old('email_confirmation')" required autocomplete="email_confirmation" />
                        <x-input-error :messages="$errors->get('email_confirmation')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Password')" />
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 right-0 flex items-center px-2">
                                <input class="hidden js-password-toggle" id="toggle" type="checkbox" />
                                <label
                                    class="bg-gray-300 hover:bg-gray-400 rounded px-2 py-1 text-sm text-gray-600 font-mono cursor-pointer js-password-label"
                                    for="toggle">{{__('show')}}</label>
                            </div>
                            <x-text-input id="password" class="block mt-1 w-full js-password" type="password" name="password"
                                required autocomplete="new-password" />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    @if (get_app_setting('members_number') == true)
                    <div class="mt-4">
                        <x-input-label for="members_number" :value="get_app_setting('members_placeholder')" />
                        <x-text-input id="members_number" class="block mt-1 w-full mb-3" type="text" name="members_number"
                            :value="old('members_number')" required />
                        <x-input-error :messages="$errors->get('members_number')" class="mt-2" />
                        <a class="underline text-sm text-black font-bold dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                            href="{{ get_app_setting('members_url') }}" target="_blank" rel="noopener noreferrer nofollow">
                            {{ get_app_setting('members_legend') }}
                        </a>
                    </div>
                    @endif

                    <div class="mt-6">
                        <fieldset>
                            <div class="flex items-center mb-4">
                                <input id="checkbox_terms" name="checkbox_terms" type="checkbox" value="1"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checkbox_terms"
                                    class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('I agree to the') }}
                                    <a href="{{route('page.show', ['tenant' => tenant('id'), 'slug' => 'terminos-y-condiciones'])}}" class="underline">{{ __('terms and conditions') }}</a>.</label>
                            </div>
                            <x-input-error :messages="$errors->get('checkbox_terms')" class="mt-2" />

                            <div class="flex items-center mb-4">
                                <input id="checkbox_privacy" name="checkbox_privacy" type="checkbox" value="1"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checkbox_privacy"
                                    class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('I have read the') }}
                                    <a href="{{route('page.show', ['tenant' => tenant('id'), 'slug' => 'aviso-de-privacidad'])}}" class="underline">{{ __('privacy policy') }}</a>.</label>
                            </div>
                            <x-input-error :messages="$errors->get('checkbox_privacy')" class="mt-2" />
                        </fieldset>
                    </div>

                    <div class="flex items-center justify-center mt-4">
                        <x-primary-button class="ml-4">
                            {{ __('Register') }}
                        </x-primary-button>
                    </div>
                    <div class="flex items-center justify-center mt-4">
                        <a class="underline text-sm text-black font-bold dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                            href="{{ route('login', ['tenant' => tenant('id')]) }}">
                            {{ __('Already registered?') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @section('scripts')
        <script>
            const passwordToggle = document.querySelector('.js-password-toggle')

            passwordToggle.addEventListener('change', function() {
                const password = document.querySelector('.js-password'),
                    passwordLabel = document.querySelector('.js-password-label')

                if (password.type === 'password') {
                    password.type = 'text'
                    passwordLabel.innerHTML = '{{__('hide')}}'
                } else {
                    password.type = 'password'
                    passwordLabel.innerHTML = '{{__('show')}}'
                }

                password.focus()
            })
        </script>
    @endsection
</x-guest-layout>
