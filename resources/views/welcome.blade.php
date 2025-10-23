<x-guest-layout>
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
        <div class="w-full rounded-lg dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
            <div class="py-6">
                {!! get_app_setting('home_content') !!}
            </div>
            <!-- Non editable -->
            <div class="py-6 home-register-screen">
                <div class="bienvenida-login text-center">
                    @if (get_app_setting('app_active'))
                    <h3 class="text-3xl text-white text-center my-6 font-bold italic">Regístrate</h3>
                    @endif
                    @if (get_app_setting('social_login_active') == true)
                        <a href="{{ route('auth.facebook', ['tenant' => tenant('id')]) }}" class="btn-faceboock py-4 block w-full text-center my-4">
                            <img src="{{ Vite::global_asset('resources/images/icon-fb.svg') }}" class="inline-block mr-3"
                                loading="lazy" alt="Icon Facebook">
                            <div class="inline-block">{{__('Use Facebook')}}</div>
                        </a>
                        <a href="{{ route('auth.google', ['tenant' => tenant('id')]) }}" class="btn-google py-4 block w-full text-center my-4">
                            <img src="{{ Vite::global_asset('resources/images/icon-google.png') }}" loading="lazy"
                                alt="Icon Google" class="w-6 h-6 inline-block mr-3">
                            <div class="inline-block text-black">{{__('Use Google')}}</div>
                        </a>
                        <div class="divisor mt-4">
                            <div class="hr-login"></div>
                            <div class="div-block w-36">
                                <div class="text-white">o bien</div>
                            </div>
                            <div class="hr-login"></div>
                        </div>
                    @endif
                </div>

                @if (get_app_setting('app_active'))
                <form id="register-form" method="POST" action="{{ route('register', ['tenant' => tenant('id')]) }}">
                    @csrf
                    {!! RecaptchaV3::field('register') !!}
                    <div>
                        <x-input-label for="name" :value="get_app_setting('reg_form_name_label')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                            :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="email" :value="get_app_setting('reg_form_email_label')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                            :value="old('email')" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="email_confirmation" :value="get_app_setting('reg_form_email_conf_label')" />
                        <x-text-input id="email_confirmation" class="block mt-1 w-full" type="email" name="email_confirmation"
                            :value="old('email_confirmation')" required autocomplete="email_confirmation" />
                        <x-input-error :messages="$errors->get('email_confirmation')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Password')" />
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 right-0 flex items-center px-2">
                                <input class="hidden js-password-toggle" id="toggle" type="checkbox" />
                                <label
                                    class="bg-gray-400 hover:bg-gray-500 rounded px-2 py-1 text-sm text-black font-mono cursor-pointer js-password-label"
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
                            :value="old('members_number')" required autocomplete="members_number" />
                        <x-input-error :messages="$errors->get('members_number')" class="mt-2" />
                        <a class="members_number_url underline text-sm text-white font-bold dark:text-gray-400 hover:text-white dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
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
                                    class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{!! get_app_setting('terms_text') !!}</label>
                            </div>
                            <x-input-error :messages="$errors->get('checkbox_terms')" class="mt-2" />

                            <div class="flex items-center mb-4">
                                <input id="checkbox_privacy" name="checkbox_privacy" type="checkbox" value="1"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checkbox_privacy"
                                    class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{!! get_app_setting('privacy_text') !!}</label>
                            </div>
                            <x-input-error :messages="$errors->get('checkbox_privacy')" class="mt-2" />
                        </fieldset>
                    </div>

                    <div class="flex items-center justify-center mt-4">
                        <x-primary-button class="w-full text-center">
                            {{ __('Register') }}
                        </x-primary-button>
                    </div>
                    <div class="text-center mt-6">
                        <a class="underline text-white font-bold dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 already-registered"
                            href="{{ route('login', ['tenant' => tenant('id')]) }}">
                            {{ __('Already registered?') }}
                        </a>
                    </div>
                </form>
                @else
                <div class="text-center">
                    {!! get_app_setting('terms_text') !!}
                    {!! get_app_setting('privacy_text') !!}
                </div>
                @endif
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
