<x-panel-layout>
    <x-slot name="title">
        {{ $title }}
    </x-slot>
    <x-slot name="description">
        {{ $description }}
    </x-slot>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Panel') }}
        </h1>
    </x-slot>

    @section('header_scripts')
        <!-- CodeMirror CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.15/codemirror.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.15/theme/monokai.min.css">
    @endsection

    <div class="py-6 mx-3">
        <div class="max-w-2xl mx-auto md:p-8 bg-white p-4 rounded shadow">
            @if (session('status'))
                <x-alert :status="session('status')" class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-4 mb-4 text-sm rounded-lg"
                    role="alert" />
            @endif
            <h1 class="mb-5 text-3xl font-bold leading-none tracking-tight text-gray-900 md:text-3xl dark:text-white">
                {{__('Welcome to the Admin Panel')}}
            </h1>
            <p>
                {{__('Navigate using the menu above. Happy Management!')}}
            </p>
           
            <h2 class="my-5 text-lg font-bold leading-none tracking-tight text-gray-900 md:text-lg dark:text-white">
                {{__('App settings')}}
            </h2>
            <form action="{{route('panel.settings.save', ['tenant' => tenant('id')])}}" enctype="multipart/form-data" method="POST">
                @csrf
                <div id="accordion-collapse" data-accordion="collapse">
                    {{-- General setting --}}
                    <h2 id="accordion-collapse-heading-1">
                        <button type="button" class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 rounded-t-xl focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3" data-accordion-target="#accordion-collapse-body-1" aria-expanded="true" aria-controls="accordion-collapse-body-1">
                          <span>{{__('General settings')}}</span>
                          <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                          </svg>
                        </button>
                    </h2>
                    <div id="accordion-collapse-body-1" class="hidden" aria-labelledby="accordion-collapse-heading-1">
                        <div class="p-5 border border-b-0 border-gray-200 dark:border-gray-700 dark:bg-gray-900">
                            <div class="my-5 flex items-center">
                                <input id="app_active" name="app_active" type="checkbox" value="1" {{get_app_setting('app_active') ? 'checked' : ''}}
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="app_active"
                                    class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('App enabled') }}</label>
                                <x-input-error :messages="$errors->get('app_active')" class="mt-2" />
                            </div>
                            <div class="my-5 flex items-center">
                                <input id="ranking_enabled" name="ranking_enabled" type="checkbox" value="1" {{get_app_setting('ranking_enabled') ? 'checked' : ''}}
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="ranking_enabled"
                                    class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('Ranking enabled') }}</label>
                                <x-input-error :messages="$errors->get('ranking_enabled')" class="mt-2" />
                            </div>
                            <div class="my-5">
                                <x-input-label for="app_name" :value="__('App name')" />
                                <x-text-input id="app_name" class="block mt-1 w-full" type="text" name="app_name"
                                    :value="old('app_name', $settings->app_name)" required autofocus autocomplete="app_name" />
                                <x-input-error :messages="$errors->get('app_name')" class="mt-2" />
                            </div>
                            <div class="my-5">
                                <x-input-label for="app_description" :value="__('App description')" />
                                <x-text-input id="app_description" class="block mt-1 w-full" type="text" name="app_description"
                                    :value="old('app_description', $settings->app_description)" required autofocus autocomplete="app_description" />
                                <x-input-error :messages="$errors->get('app_description')" class="mt-2" />
                            </div>
                            <div class="my-5">
                                <x-input-label for="ga4_id" :value="__('GA4 Measurement ID')" />
                                <x-text-input id="ga4_id" class="block mt-1 w-full" type="text" name="ga4_id"
                                    :value="old('ga4_id', $settings->ga4_id)" autofocus autocomplete="ga4_id" />
                                <x-input-error :messages="$errors->get('ga4_id')" class="mt-2" />
                            </div>
                            <div class="my-5 grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="app_logo" :value="__('App logo')" />
                                    <img src="{{$settings->app_logo}}" alt="{{__('App logo')}}" title="{{__('App logo')}}" class="my-5">
                                    <input
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                        aria-describedby="app_logo_help" id="app_logo" name="app_logo" type="file">
                                    <x-input-error class="mt-2" :messages="$errors->get('app_logo')" />
                                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="app_logo_help">
                                        {{__('Image must be less than 2MB and JPG or PNG format.')}} <br>
                                    </div>
                                </div>
                                <div>
                                    <x-input-label for="favicon" :value="__('Favicon')" />
                                    <img src="{{$settings->favicon}}" alt="{{__('Favicon')}}" title="{{__('Favicon')}}" class="my-5">
                                    <input
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                        aria-describedby="favicon_help" id="favicon" name="favicon" type="file">
                                    <x-input-error class="mt-2" :messages="$errors->get('favicon')" />
                                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="favicon_help">
                                        {{__('Image must be less than 2MB and JPG or PNG format.')}} <br>
                                        {{__('Square aspect ratio, PNG, 512x512')}}
                                    </div>
                                </div>
                            </div>
                            <div class="my-5">
                                <x-input-label for="custom_css" :value="__('Custom CSS')" />
                                <textarea id="custom_css" name="custom_css" rows="15">{{old('custom_css', $settings->custom_css)}}</textarea>
                                <x-input-error :messages="$errors->get('custom_css')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                    {{-- Ranking Games settings --}}
                    @if(get_app_setting('ranking_enabled'))
                        <h2 id="accordion-collapse-heading-10">
                            <button type="button" class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 rounded-t-xl focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3" data-accordion-target="#accordion-collapse-body-10" aria-expanded="true" aria-controls="accordion-collapse-body-10">
                            <span>{{__('Ranking Game Settings')}}</span>
                            <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                            </svg>
                            </button>
                        </h2>
                        <div id="accordion-collapse-body-10" class="hidden" aria-labelledby="accordion-collapse-heading-10">
                            <div class="p-5 border border-b-0 border-gray-200 dark:border-gray-700 dark:bg-gray-900">
                                <div class="my-5 flex items-center">
                                    <input id="ranking_enabled_games" name="ranking_enabled_games" type="checkbox" value="1" {{get_app_setting('ranking_enabled_games') ? 'checked' : ''}}
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="ranking_enabled_games"
                                        class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('Ranking Games Enabled') }}</label>
                                    <x-input-error :messages="$errors->get('ranking_enabled_games')" class="mt-2" />
                                </div>
                                <div class="my-5 grid grid-cols-3 gap-4">
                                    <div>
                                        <x-input-label for="first_place_icon_games" :value="__('First place image')" />
                                        @if ($settings->first_place_icon_games)
                                        <img src="{{$settings->first_place_icon_games}}" alt="" class="w-full mb-3">
                                        @else
                                        <p class="my-3">
                                            {{__('Not assigned')}}
                                        </p>
                                        @endif
                                        <input
                                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                            aria-describedby="first_place_icon_games_help" id="first_place_icon_games" name="first_place_icon_games" type="file">
                                        <x-input-error class="mt-2" :messages="$errors->get('first_place_icon_games')" />
                                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="first_place_icon_games_help">
                                            {{__('Image must be less than 2MB and JPG or PNG format.')}} <br>
                                        </div>
                                    </div>
                                    <div>
                                        <x-input-label for="second_place_icon_games" :value="__('Second place image')" />
                                        @if ($settings->second_place_icon_games)
                                        <img src="{{$settings->second_place_icon_games}}" alt="" class="w-full mb-3">
                                        @else
                                        <p class="my-3">
                                            {{__('Not assigned')}}
                                        </p>
                                        @endif
                                        <input
                                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                            aria-describedby="second_place_icon_games_help" id="second_place_icon_games" name="second_place_icon_games" type="file">
                                        <x-input-error class="mt-2" :messages="$errors->get('second_place_icon_games')" />
                                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="second_place_icon_games_help">
                                            {{__('Image must be less than 2MB and JPG or PNG format.')}} <br>
                                        </div>
                                    </div>
                                    <div>
                                        <x-input-label for="third_place_icon_games" :value="__('Third place image')" />
                                        @if ($settings->third_place_icon_games)
                                        <img src="{{$settings->third_place_icon_games}}" alt="" class="w-full mb-3">
                                        @else
                                        <p class="my-3">
                                            {{__('Not assigned')}}
                                        </p>
                                        @endif
                                        <input
                                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                            aria-describedby="third_place_icon_games_help" id="third_place_icon_games" name="third_place_icon_games" type="file">
                                        <x-input-error class="mt-2" :messages="$errors->get('third_place_icon_games')" />
                                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="third_place_icon_games_help">
                                            {{__('Image must be less than 2MB and JPG or PNG format.')}} <br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- Registration options --}}
                    <h2 id="accordion-collapse-heading-2">
                        <button type="button" class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3" data-accordion-target="#accordion-collapse-body-2" aria-expanded="false" aria-controls="accordion-collapse-body-2">
                          <span>{{__('Registration options')}}</span>
                          <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                          </svg>
                        </button>
                    </h2>
                    <div id="accordion-collapse-body-2" class="hidden" aria-labelledby="accordion-collapse-heading-2">
                        <div class="p-5 border border-b-0 border-gray-200 dark:border-gray-700 dark:bg-gray-900">
                            <div class="my-5 flex items-center">
                                <input id="allow_city" name="allow_city" type="checkbox" value="1" {{get_app_setting('allow_city') ? 'checked' : ''}}
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="allow_city"
                                    class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('Allow City') }}</label>
                                <x-input-error :messages="$errors->get('allow_city')" class="mt-2" />
                            </div>
                            <div class="my-5 flex items-center">
                                <input id="social_login_active" name="social_login_active" type="checkbox" value="1" {{get_app_setting('social_login_active') ? 'checked' : ''}}
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="social_login_active"
                                    class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('Social login enabled') }}</label>
                                <x-input-error :messages="$errors->get('social_login_active')" class="mt-2" />
                            </div>

                            <div class="my-5 flex items-center">
                                <input id="members_number" name="members_number" type="checkbox" value="1" {{get_app_setting('members_number') ? 'checked' : ''}}
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="members_number"
                                    class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('Members ID enabled') }}</label>
                                <x-input-error :messages="$errors->get('members_number')" class="mt-2" />
                            </div>

                            <div class="my-5">
                                <x-input-label for="members_legend" :value="__('Members legend')" />
                                <x-text-input id="members_legend" class="block mt-1 w-full" type="text" name="members_legend"
                                    :value="old('members_legend', $settings->members_legend)" required autofocus autocomplete="members_legend" />
                                <x-input-error :messages="$errors->get('members_legend')" class="mt-2" />
                            </div>
                            <div class="my-5">
                                <x-input-label for="members_placeholder" :value="__('Members placeholder')" />
                                <x-text-input id="members_placeholder" class="block mt-1 w-full" type="text" name="members_placeholder"
                                    :value="old('members_placeholder', $settings->members_placeholder)" required autofocus autocomplete="members_placeholder" />
                                <x-input-error :messages="$errors->get('members_placeholder')" class="mt-2" />
                            </div>
                            <div class="my-5">
                                <x-input-label for="members_url" :value="__('Members URL')" />
                                <x-text-input id="members_url" class="block mt-1 w-full" type="text" name="members_url"
                                    :value="old('members_url', $settings->members_url)" required autofocus autocomplete="members_url" />
                                <x-input-error :messages="$errors->get('members_url')" class="mt-2" />
                            </div>
                            <div class="my-5">
                                <x-input-label for="reg_form_name_label" :value="__('Register form Name Label')" />
                                <x-text-input id="reg_form_name_label" class="block mt-1 w-full" type="text" name="reg_form_name_label"
                                    :value="old('reg_form_name_label', $settings->reg_form_name_label)" required autofocus autocomplete="reg_form_name_label" />
                                <x-input-error :messages="$errors->get('reg_form_name_label')" class="mt-2" />
                            </div>
                            <div class="my-5">
                                <x-input-label for="reg_form_email_label" :value="__('Register form Email Label')" />
                                <x-text-input id="reg_form_email_label" class="block mt-1 w-full" type="text" name="reg_form_email_label"
                                    :value="old('reg_form_email_label', $settings->reg_form_email_label)" required autofocus autocomplete="reg_form_email_label" />
                                <x-input-error :messages="$errors->get('reg_form_email_label')" class="mt-2" />
                            </div>
                            <div class="my-5">
                                <x-input-label for="reg_form_email_conf_label" :value="__('Register form Email Confirmation Label')" />
                                <x-text-input id="reg_form_email_conf_label" class="block mt-1 w-full" type="text" name="reg_form_email_conf_label"
                                    :value="old('reg_form_email_conf_label', $settings->reg_form_email_conf_label)" required autofocus autocomplete="reg_form_email_conf_label" />
                                <x-input-error :messages="$errors->get('reg_form_email_conf_label')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    {{-- Home page --}}
                    <h2 id="accordion-collapse-heading-5">
                        <button type="button" class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3" data-accordion-target="#accordion-collapse-body-5" aria-expanded="false" aria-controls="accordion-collapse-body-5">
                          <span>{{__('Home page')}}</span>
                          <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                          </svg>
                        </button>
                    </h2>
                    <div id="accordion-collapse-body-5" class="hidden" aria-labelledby="accordion-collapse-heading-5">
                        <div class="p-5 border border-b-0 border-gray-200 dark:border-gray-700 dark:bg-gray-900">
                            <div class="my-5">
                                <x-input-label for="app_name" :value="__('Home content')" />
                                <textarea id="home_content" name="home_content" rows="10"
                                    class="tinymce-component block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{old('home_content', $settings->home_content)}}</textarea>
                                <x-input-error :messages="$errors->get('home_content')" class="mt-2" />
                            </div>
                            <div class="my-5">
                                <x-input-label for="terms_text" :value="__('Terms text')" />
                                <textarea id="terms_text" name="terms_text" rows="10"
                                    class="tinymce-component block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{old('terms_text', $settings->terms_text)}}</textarea>
                                <x-input-error :messages="$errors->get('terms_text')" class="mt-2" />
                            </div>
                            <div class="my-5">
                                <x-input-label for="privacy_text" :value="__('Privacy text')" />
                                <textarea id="privacy_text" name="privacy_text" rows="10"
                                    class="tinymce-component block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{old('privacy_text', $settings->privacy_text)}}</textarea>
                                <x-input-error :messages="$errors->get('privacy_text')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                    
                    {{-- Campaign settings --}}
                    <h2 id="accordion-collapse-heading-4">
                        <button type="button" class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3" data-accordion-target="#accordion-collapse-body-4" aria-expanded="false" aria-controls="accordion-collapse-body-4">
                          <span>{{__('Campaign settings')}}</span>
                          <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                          </svg>
                        </button>
                    </h2>
                    <div id="accordion-collapse-body-4" class="hidden" aria-labelledby="accordion-collapse-heading-4">
                        <div class="p-5 border border-t-0 border-gray-200 dark:border-gray-700">
                            <div class="my-5 flex items-center">
                                <input id="cards_shadow" name="cards_shadow" type="checkbox" value="1" {{get_app_setting('cards_shadow') ? 'checked' : ''}}
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="cards_shadow"
                                    class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('Cards shadow enabled') }}</label>
                                <x-input-error :messages="$errors->get('cards_shadow')" class="mt-2" />
                            </div>
                            <div class="my-5 grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="disabled_gradient_1" :value="__('Disabled game gradient 1')" class="mb-2" />
                                    <x-text-input id="disabled_gradient_1" class="block mt-1 w-full" type="text" name="disabled_gradient_1"
                                        :value="old('disabled_gradient_1', $settings->disabled_gradient_1)" required autofocus autocomplete="disabled_gradient_1" />
                                    <x-input-error :messages="$errors->get('disabled_gradient_1')" class="mt-2" />
                                    <div id="picker-disabled1-color"></div>
                                    <div id="picker-disabled1-viewer" class="block mt-1 w-full h-10 rounded"></div>
                                </div>

                                <div>
                                    <x-input-label for="disabled_gradient_2" :value="__('Disabled game gradient 2')" class="mb-2" />
                                    <x-text-input id="disabled_gradient_2" class="block mt-1 w-full" type="text" name="disabled_gradient_2"
                                        :value="old('disabled_gradient_2', $settings->disabled_gradient_2)" required autofocus autocomplete="disabled_gradient_2" />
                                    <x-input-error :messages="$errors->get('disabled_gradient_2')" class="mt-2" />
                                    <div id="picker-disabled2-color"></div>
                                    <div id="picker-disabled2-viewer" class="block mt-1 w-full h-10 rounded"></div>
                                </div>
                            </div>

                            <div class="my-5 grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="ranking_icon" :value="__('Ranking menu icon')" />
                                    <div class="img-app-preview my-5 w-full h-72 bg-gray-300" style="background-image: url({{$settings->ranking_icon}})"></div>
                                    <input
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                        aria-describedby="ranking_icon_help" id="ranking_icon" name="ranking_icon" type="file">
                                    <x-input-error class="mt-2" :messages="$errors->get('ranking_icon')" />
                                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="ranking_icon_help">
                                        {{__('Image must be less than 2MB and JPG or PNG format.')}} <br>
                                    </div>
                                </div>
                                <div>
                                    <x-input-label for="ranking_icon_active" :value="__('Ranking menu icon (active)')" />
                                    <div class="img-app-preview my-5 w-full h-72" style="background-image: url({{$settings->ranking_icon_active}})"></div>
                                    <input
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                        aria-describedby="ranking_icon_active_help" id="ranking_icon_active" name="ranking_icon_active" type="file">
                                    <x-input-error class="mt-2" :messages="$errors->get('ranking_icon_active')" />
                                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="ranking_icon_active_help">
                                        {{__('Image must be less than 2MB and JPG or PNG format.')}} <br>
                                    </div>
                                </div>
                            </div>

                            <div class="my-5">
                                <x-input-label for="ranking_banner" :value="__('Ranking banner')" />
                                @if ($settings->ranking_banner)
                                    <div id="delete_image_holder" class="relative">
                                        <img src="{{$settings->ranking_banner}}" alt="{{__('Ranking banner')}}" title="{{__('Ranking banner')}}" class="my-5 w-full">
                                        <x-delete-image :element="'delete_image_holder'"></x-delete-image>
                                    </div>
                                    <input type="hidden" id="delete_image_holder_hidden" name="delete_image_holder_hidden" value="0">
                                @endif
                                <input
                                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                    aria-describedby="ranking_banner_help" id="ranking_banner" name="ranking_banner" type="file">
                                <x-input-error class="mt-2" :messages="$errors->get('ranking_banner')" />
                                <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="ranking_banner_help">
                                    {{__('Image must be less than 2MB and JPG or PNG format.')}}<br>
                                    {{__('Dimensions must be')}} 500 x 300
                                </div>
                            </div>

                            <div class="my-5">
                                <x-input-label for="award_show_title" :value="__('Awards title')" />
                                <x-text-input id="award_show_title" class="block mt-1 w-full" type="text" name="award_show_title"
                                    :value="old('award_show_title', $settings->award_show_title)" required autofocus autocomplete="award_show_title" />
                                <x-input-error :messages="$errors->get('award_show_title')" class="mt-2" />
                            </div>

                            <div class="my-5">
                                <x-input-label for="awards_section_title" :value="__('Awards user panel title')" />
                                <x-text-input id="awards_section_title" class="block mt-1 w-full" type="text" name="awards_section_title"
                                    :value="old('awards_section_title', $settings->awards_section_title)" required autofocus autocomplete="awards_section_title" />
                                <x-input-error :messages="$errors->get('awards_section_title')" class="mt-2" />
                            </div>

                            <div class="my-5">
                                <x-input-label for="out_of_coupons_title" :value="__('Out of benefits title')" />
                                <x-text-input id="out_of_coupons_title" class="block mt-1 w-full" type="text" name="out_of_coupons_title"
                                    :value="old('out_of_coupons_title', $settings->out_of_coupons_title)" required autofocus autocomplete="out_of_coupons_title" />
                                <x-input-error :messages="$errors->get('out_of_coupons_title')" class="mt-2" />
                            </div>

                            <div class="my-5">
                                <x-input-label for="out_of_coupons_image" :value="__('Out of benefits image')" />
                                <img src="{{$settings->out_of_coupons_image}}" alt="" class="w-full mb-3">
                                <input
                                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                    aria-describedby="out_of_coupons_image_help" id="out_of_coupons_image" name="out_of_coupons_image" type="file">
                                <x-input-error class="mt-2" :messages="$errors->get('out_of_coupons_image')" />
                                <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="out_of_coupons_image_help">
                                    {{__('Image must be less than 2MB and JPG or PNG format.')}} <br>
                                </div>
                            </div>
                            <div class="my-5 grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="cards_background_color" :value="__('Cards background')" class="mb-2" />
                                    <x-text-input id="cards_background_color" class="block mt-1 w-full" type="text" name="cards_background_color"
                                        :value="old('cards_background_color', $settings->cards_background_color)" required autofocus autocomplete="cards_background_color" />
                                    <x-input-error :messages="$errors->get('cards_background_color')" class="mt-2" />
                                    <div id="picker-cardsbg-color"></div>
                                    <div id="picker-cardsbg-viewer" class="block mt-1 w-full h-10 rounded"></div>
                                </div>

                                <div>
                                    <x-input-label for="cards_font_color" :value="__('Cards font color')" class="mb-2" />
                                    <x-text-input id="cards_font_color" class="block mt-1 w-full" type="text" name="cards_font_color"
                                        :value="old('cards_font_color', $settings->cards_font_color)" required autofocus autocomplete="cards_font_color" />
                                    <x-input-error :messages="$errors->get('cards_font_color')" class="mt-2" />
                                    <div id="picker-cardsfont-color"></div>
                                    <div id="picker-cardsfont-viewer" class="block mt-1 w-full h-10 rounded"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(get_app_setting('ranking_enabled'))
                    {{-- Ranking Tickets module --}}
                    <h2 id="accordion-collapse-heading-6">
                        <button type="button" class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3" data-accordion-target="#accordion-collapse-body-6" aria-expanded="false" aria-controls="accordion-collapse-body-6">
                            <span>{{__('Ranking Tickets module settings')}}</span>
                          <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                          </svg>
                        </button>
                    </h2>
                    <div id="accordion-collapse-body-6" class="hidden" aria-labelledby="accordion-collapse-heading-6">
                        <div class="p-5 border border-t-0 border-gray-200 dark:border-gray-700">
                            <div class="my-5 flex items-center">
                                    <input id="ranking_enabled_tickets" name="ranking_enabled_tickets" type="checkbox" value="1" {{get_app_setting('ranking_enabled_tickets') ? 'checked' : ''}}
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="ranking_enabled_tickets"
                                        class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('Ranking Tickets Enabled') }}</label>
                                    <x-input-error :messages="$errors->get('ranking_enabled_tickets')" class="mt-2" />
                                </div>
                            <div class="my-5">
                                    <h6 class="mb-2 text-sm font-bold text-black dark:text-white">
                                        {{ __('Module type') }}
                                    </h6>
            
                                <ul
                                    class="flex flex-col items-center w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg md:flex-row dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <li
                                        class="w-full border-b border-gray-200 md:border-b-0 md:border-r dark:border-gray-600">
                                        <div class="flex items-center pl-3">
                                            <input id="ticket-ocr" type="radio" value="1" name="ocr_ticket_active" {{ get_app_setting('ocr_ticket_active') ? 'checked' : '' }}
                                                class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                            <label for="ticket-ocr"
                                                class="w-full py-3 ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                                OCR
                                            </label>
                                        </div>
                                    </li>
                                    <li
                                        class="w-full border-b border-gray-200 md:border-b-0 md:border-r dark:border-gray-600">
                                        <div class="flex items-center pl-3">
                                            <input id="ticket-trivia" type="radio" value="0" name="ocr_ticket_active" {{ !get_app_setting('ocr_ticket_active') ? 'checked' : '' }}
                                                class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                            <label for="ticket-trivia"
                                                class="w-full py-3 ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                                Trivia
                                            </label>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div id="module_ocr" class="{{ !get_app_setting('ocr_ticket_active') ? 'hidden' : '' }}">
                                <h6 class="mb-2 text-sm font-bold text-black dark:text-white italic">
                                    {{ __('Phrases') }}
                                </h6>
                                <div class="my-5">
                                    <x-input-label for="ocr_ticket_phrases" :value="__('Use breaklines to separate phrases')" />
                                    <textarea id="ocr_ticket_phrases" name="ocr_ticket_phrases" rows="10"
                                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        {{old('ocr_ticket_phrases') ? old('ocr_ticket_phrases') : $settings->ocr_ticket_phrases }}
                                    </textarea>
                                    <x-input-error :messages="$errors->get('ocr_ticket_phrases')" class="mt-2" />
                                </div>
                                <h6 class="mb-2 text-sm font-bold text-black dark:text-white italic">
                                    {{ __('Date parameters') }}
                                </h6>
                                <div class="my-5 grid grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="ocr_date_string" :value="__('String to find')" />
                                        <x-text-input id="ocr_date_string" class="block mt-1 w-full" type="text" name="ocr_date_string"
                                            :value="old('ocr_date_string', $settings->ocr_date_string)" required autofocus autocomplete="ocr_date_string" />
                                        <x-input-error :messages="$errors->get('ocr_date_string')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="ocr_date_characters" :value="__('Characters after')" />
                                        <x-text-input id="ocr_date_characters" class="block mt-1 w-full" type="number" name="ocr_date_characters"
                                            :value="old('ocr_date_characters', $settings->ocr_date_characters)" required autofocus autocomplete="ocr_date_characters" />
                                        <x-input-error :messages="$errors->get('ocr_date_characters')" class="mt-2" />
                                    </div>
                                </div>
                                <div class="my-5 grid grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="ocr_date_format" :value="__('Date format')" />
                                        <x-text-input id="ocr_date_format" class="block mt-1 w-full" type="text" name="ocr_date_format"
                                            :value="old('ocr_date_format', $settings->ocr_date_format)" required autofocus autocomplete="ocr_date_format"
                                            placeholder="Y-m-d" />
                                        <x-input-error :messages="$errors->get('ocr_date_format')" class="mt-2" />
                                    </div>
                                </div>

                                <h6 class="mb-2 text-sm font-bold text-black dark:text-white italic">
                                    {{ __('Time parameters') }}
                                </h6>
                                <div class="my-5 grid grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="ocr_time_string" :value="__('String to find')" />
                                        <x-text-input id="ocr_time_string" class="block mt-1 w-full" type="text" name="ocr_time_string"
                                            :value="old('ocr_time_string', $settings->ocr_time_string)" required autofocus autocomplete="ocr_time_string" />
                                        <x-input-error :messages="$errors->get('ocr_time_string')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="ocr_time_characters" :value="__('Characters after')" />
                                        <x-text-input id="ocr_time_characters" class="block mt-1 w-full" type="number" name="ocr_time_characters"
                                            :value="old('ocr_time_characters', $settings->ocr_time_characters)" required autofocus autocomplete="ocr_time_characters" />
                                        <x-input-error :messages="$errors->get('ocr_time_characters')" class="mt-2" />
                                    </div>
                                </div>

                                <h6 class="mb-2 text-sm font-bold text-black dark:text-white italic">
                                    {{ __('Transaction parameters') }}
                                </h6>
                                <div class="my-5 grid grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="ocr_transaction_string" :value="__('String to find')" />
                                        <x-text-input id="ocr_transaction_string" class="block mt-1 w-full" type="text" name="ocr_transaction_string"
                                            :value="old('ocr_transaction_string', $settings->ocr_transaction_string)" required autofocus autocomplete="ocr_transaction_string" />
                                        <x-input-error :messages="$errors->get('ocr_transaction_string')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="ocr_transaction_characters" :value="__('Characters after')" />
                                        <x-text-input id="ocr_transaction_characters" class="block mt-1 w-full" type="number" name="ocr_transaction_characters"
                                            :value="old('ocr_transaction_characters', $settings->ocr_transaction_characters)" required autofocus autocomplete="ocr_transaction_characters" />
                                        <x-input-error :messages="$errors->get('ocr_transaction_characters')" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <div id="module_trivia" class="{{ get_app_setting('ocr_ticket_active') ? 'hidden' : '' }}">
                                <div class="my-5">
                                    <x-input-label for="tickets_points" :value="__('Points per ticket')" />
                                    <x-text-input id="tickets_points" class="block mt-1 w-full" type="number" name="tickets_points"
                                        :value="old('tickets_points', $settings->tickets_points)" required autofocus autocomplete="tickets_points" />
                                    <x-input-error :messages="$errors->get('tickets_points')" class="mt-2" />
                                </div>
                                <div class="my-5 flex items-center">
                                    <input id="tickets_quiz_validation" name="tickets_quiz_validation" type="checkbox" value="1" {{get_app_setting('tickets_quiz_validation') ? 'checked' : ''}}
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="tickets_quiz_validation"
                                        class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('Ticket quiz validation enabled') }}</label>
                                    <x-input-error :messages="$errors->get('tickets_quiz_validation')" class="mt-2" />
                                </div>
                            </div>

                            <div class="my-5">
                                <x-input-label for="tickets_form_legend" :value="__('Ticket form legend')" />
                                <x-text-input id="tickets_form_legend" class="block mt-1 w-full" type="text" name="tickets_form_legend"
                                    :value="old('tickets_form_legend', $settings->tickets_form_legend)" required autofocus autocomplete="tickets_form_legend" />
                                <x-input-error :messages="$errors->get('tickets_form_legend')" class="mt-2" />
                            </div>

                            <div class="my-5 grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="ranking_color_1" :value="__('Auth user gradient 1')" class="mb-2" />
                                    <x-text-input id="ranking_color_1" class="block mt-1 w-full" type="text" name="ranking_color_1"
                                        :value="old('ranking_color_1', $settings->ranking_color_1)" required autofocus autocomplete="ranking_color_1" />
                                    <x-input-error :messages="$errors->get('ranking_color_1')" class="mt-2" />
                                    <div id="picker-ranking1-color"></div>
                                    <div id="picker-ranking1-viewer" class="block mt-1 w-full h-10 rounded"></div>
                                </div>

                                <div>
                                    <x-input-label for="ranking_color_2" :value="__('Auth user gradient 2')" class="mb-2" />
                                    <x-text-input id="ranking_color_2" class="block mt-1 w-full" type="text" name="ranking_color_2"
                                        :value="old('ranking_color_2', $settings->ranking_color_2)" required autofocus autocomplete="ranking_color_2" />
                                    <x-input-error :messages="$errors->get('ranking_color_2')" class="mt-2" />
                                    <div id="picker-ranking2-color"></div>
                                    <div id="picker-ranking2-viewer" class="block mt-1 w-full h-10 rounded"></div>
                                </div>
                            </div>

                            <div class="my-5 grid grid-cols-3 gap-4">
                                <div>
                                    <x-input-label for="tickets_success_response" :value="__('Ticket quiz correct image')" />
                                    <img src="{{$settings->tickets_success_response}}" alt="" class="w-full mb-3">
                                    <input
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                        aria-describedby="tickets_success_response_help" id="tickets_success_response" name="tickets_success_response" type="file">
                                    <x-input-error class="mt-2" :messages="$errors->get('tickets_success_response')" />
                                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="tickets_success_response_help">
                                        {{__('Image must be less than 2MB and JPG or PNG format.')}} <br>
                                    </div>
                                </div>
                                <div>
                                    <x-input-label for="tickets_failed_response" :value="__('Ticket quiz failed image')" />
                                    <img src="{{$settings->tickets_failed_response}}" alt="" class="w-full mb-3">
                                    <input
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                        aria-describedby="tickets_failed_response_help" id="tickets_failed_response" name="tickets_failed_response" type="file">
                                    <x-input-error class="mt-2" :messages="$errors->get('tickets_failed_response')" />
                                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="tickets_failed_response_help">
                                        {{__('Image must be less than 2MB and JPG or PNG format.')}} <br>
                                    </div>
                                </div>
                                <div>
                                    <x-input-label for="tickets_duplicated_image" :value="__('Ticket duplicated image')" />
                                    <img src="{{$settings->tickets_duplicated_image}}" alt="" class="w-full mb-3">
                                    <input
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                        aria-describedby="tickets_duplicated_image_help" id="tickets_duplicated_image" name="tickets_duplicated_image" type="file">
                                    <x-input-error class="mt-2" :messages="$errors->get('tickets_duplicated_image')" />
                                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="tickets_duplicated_image_help">
                                        {{__('Image must be less than 2MB and JPG or PNG format.')}} <br>
                                    </div>
                                </div>
                            </div>

                            <div class="my-5 grid grid-cols-3 gap-4">
                                <div>
                                    <x-input-label for="first_place_icon" :value="__('First place image')" />
                                    @if ($settings->first_place_icon)
                                    <img src="{{$settings->first_place_icon}}" alt="" class="w-full mb-3">
                                    @else
                                    <p class="my-3">
                                        {{__('Not assigned')}}
                                    </p>
                                    @endif
                                    <input
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                        aria-describedby="first_place_icon_help" id="first_place_icon" name="first_place_icon" type="file">
                                    <x-input-error class="mt-2" :messages="$errors->get('first_place_icon')" />
                                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="first_place_icon_help">
                                        {{__('Image must be less than 2MB and JPG or PNG format.')}} <br>
                                    </div>
                                </div>
                                <div>
                                    <x-input-label for="second_place_icon" :value="__('Second place image')" />
                                    @if ($settings->second_place_icon)
                                    <img src="{{$settings->second_place_icon}}" alt="" class="w-full mb-3">
                                    @else
                                    <p class="my-3">
                                        {{__('Not assigned')}}
                                    </p>
                                    @endif
                                    <input
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                        aria-describedby="second_place_icon_help" id="second_place_icon" name="second_place_icon" type="file">
                                    <x-input-error class="mt-2" :messages="$errors->get('second_place_icon')" />
                                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="second_place_icon_help">
                                        {{__('Image must be less than 2MB and JPG or PNG format.')}} <br>
                                    </div>
                                </div>
                                <div>
                                    <x-input-label for="third_place_icon" :value="__('Third place image')" />
                                    @if ($settings->third_place_icon)
                                    <img src="{{$settings->third_place_icon}}" alt="" class="w-full mb-3">
                                    @else
                                    <p class="my-3">
                                        {{__('Not assigned')}}
                                    </p>
                                    @endif
                                    <input
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                        aria-describedby="third_place_icon_help" id="third_place_icon" name="third_place_icon" type="file">
                                    <x-input-error class="mt-2" :messages="$errors->get('third_place_icon')" />
                                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="third_place_icon_help">
                                        {{__('Image must be less than 2MB and JPG or PNG format.')}} <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Code hunter module --}}
                    <h2 id="accordion-collapse-heading-7">
                        <button type="button" class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3" data-accordion-target="#accordion-collapse-body-7" aria-expanded="false" aria-controls="accordion-collapse-body-7">
                          <span>{{__('Code hunter module settings')}}</span>
                          <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                          </svg>
                        </button>
                    </h2>
                    <div id="accordion-collapse-body-7" class="hidden" aria-labelledby="accordion-collapse-heading-6">
                        <div class="p-5 border border-t-0 border-gray-200 dark:border-gray-700">
                            <div class="my-5">
                                <x-input-label for="coupons_form_legend" :value="__('Code Hunter form legend')" />
                                <x-text-input id="coupons_form_legend" class="block mt-1 w-full" type="text" name="coupons_form_legend"
                                    :value="old('coupons_form_legend', $settings->coupons_form_legend)" required autofocus autocomplete="coupons_form_legend" />
                                <x-input-error :messages="$errors->get('coupons_form_legend')" class="mt-2" />
                            </div>

                            <div class="my-5">
                                <x-input-label for="coupons_field_placeholder" :value="__('Code Hunter form placeholder')" />
                                <x-text-input id="coupons_field_placeholder" class="block mt-1 w-full" type="text" name="coupons_field_placeholder"
                                    :value="old('coupons_field_placeholder', $settings->coupons_field_placeholder)" required autofocus autocomplete="coupons_field_placeholder" />
                                <x-input-error :messages="$errors->get('coupons_field_placeholder')" class="mt-2" />
                            </div>

                            <div class="my-5 grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="code_hunter_incorrect" :value="__('Code incorrect image')" />
                                    <img src="{{$settings->code_hunter_incorrect}}" alt="" class="w-full mb-3">
                                    <input
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                        aria-describedby="code_hunter_incorrect_help" id="code_hunter_incorrect" name="code_hunter_incorrect" type="file">
                                    <x-input-error class="mt-2" :messages="$errors->get('code_hunter_incorrect')" />
                                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="code_hunter_incorrect_help">
                                        {{__('Image must be less than 2MB and JPG or PNG format.')}} <br>
                                    </div>
                                </div>
                                <div>
                                    <x-input-label for="code_hunter_duplicated" :value="__('Code duplicated image')" />
                                    <img src="{{$settings->code_hunter_duplicated}}" alt="" class="w-full mb-3">
                                    <input
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                        aria-describedby="code_hunter_duplicated_help" id="code_hunter_duplicated" name="code_hunter_duplicated" type="file">
                                    <x-input-error class="mt-2" :messages="$errors->get('code_hunter_duplicated')" />
                                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="code_hunter_duplicated_help">
                                        {{__('Image must be less than 2MB and JPG or PNG format.')}} <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Aplazo settings --}}
                    <h2 id="accordion-collapse-heading-7">
                        <button type="button" class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3" data-accordion-target="#accordion-collapse-body-9" aria-expanded="false" aria-controls="accordion-collapse-body-9">
                          <span>{{__('Aplazo settings')}}</span>
                          <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                          </svg>
                        </button>
                    </h2>
                    <div id="accordion-collapse-body-9" class="hidden" aria-labelledby="accordion-collapse-heading-6">
                        <div class="p-5 border border-t-0 border-gray-200 dark:border-gray-700">
                            <div class="my-5">
                                <x-input-label for="aplazo_endpoint" :value="__('Endpoint')" />
                                <x-text-input id="aplazo_endpoint" class="block mt-1 w-full" type="text" name="aplazo_endpoint"
                                    :value="old('aplazo_endpoint', $settings->aplazo_endpoint)" autofocus autocomplete="aplazo_endpoint" />
                                <x-input-error :messages="$errors->get('aplazo_endpoint')" class="mt-2" />
                            </div>

                            <div class="my-5">
                                <x-input-label for="aplazo_merchant_id" :value="__('Merchant ID')" />
                                <x-text-input id="aplazo_merchant_id" class="block mt-1 w-full" type="text" name="aplazo_merchant_id"
                                    :value="old('aplazo_merchant_id', $settings->aplazo_merchant_id)" autofocus autocomplete="aplazo_merchant_id" />
                                <x-input-error :messages="$errors->get('aplazo_merchant_id')" class="mt-2" />
                            </div>

                            <div class="my-5">
                                <x-input-label for="aplazo_api_token" :value="__('API Token')" />
                                <x-text-input id="aplazo_api_token" class="block mt-1 w-full" type="text" name="aplazo_api_token"
                                    :value="old('aplazo_api_token', $settings->aplazo_api_token)" autofocus autocomplete="aplazo_api_token" />
                                <x-input-error :messages="$errors->get('aplazo_api_token')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                    
                    {{-- App color settings --}}
                    <h2 id="accordion-collapse-heading-3">
                        <button type="button" class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border  border-gray-200 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3" data-accordion-target="#accordion-collapse-body-3" aria-expanded="false" aria-controls="accordion-collapse-body-3">
                          <span>{{__('Colors settings and backgrounds')}}</span>
                          <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                          </svg>
                        </button>
                    </h2>
                    <div id="accordion-collapse-body-3" class="hidden" aria-labelledby="accordion-collapse-heading-3">
                        <div class="p-5 border border-t-0 border-gray-200 dark:border-gray-700">
                            <div class="my-5 grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="primary_button_color" :value="__('Primary button color')" class="mb-2" />
                                    <x-text-input id="primary_button_color" class="block mt-1 w-full" type="text" name="primary_button_color"
                                        :value="old('primary_button_color', $settings->primary_button_color)" required autofocus autocomplete="primary_button_color" />
                                    <x-input-error :messages="$errors->get('primary_button_color')" class="mt-2" />
                                    <div id="picker-btn-color"></div>
                                    <div id="piker-btn-color-viewer" class="block mt-1 w-full h-10 rounded"></div>
                                </div>

                                <div>
                                    <x-input-label for="primary_button_background" :value="__('Primary button background color')" class="mb-2" />
                                    <x-text-input id="primary_button_background" class="block mt-1 w-full" type="text" name="primary_button_background"
                                        :value="old('primary_button_background', $settings->primary_button_background)" required autofocus autocomplete="primary_button_background" />
                                    <x-input-error :messages="$errors->get('primary_button_background')" class="mt-2" />
                                    <div id="picker-btn-bg"></div>
                                    <div id="piker-btn-bg-viewer" class="block mt-1 w-full h-10 rounded"></div>
                                </div>
                            </div>

                            <div class="my-5 grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="header_background_color" :value="__('Header background color')" class="mb-2" />
                                    <x-text-input id="header_background_color" class="block mt-1 w-full" type="text" name="header_background_color" type="color"
                                        :value="old('header_background_color', $settings->header_background_color)" required autofocus autocomplete="header_background_color" />
                                    <x-input-error :messages="$errors->get('header_background_color')" class="mt-2" />
                                    <div id="picker-header"></div>
                                    <div id="piker-header-viewer" class="block mt-1 w-full h-10 rounded"></div>
                                </div>

                                <div>
                                    <x-input-label for="app_background_color" :value="__('Body background color')" class="mb-2" />
                                    <x-text-input id="app_background_color" class="block mt-1 w-full" type="text" name="app_background_color"
                                        :value="old('app_background_color', $settings->app_background_color)" required autofocus autocomplete="app_background_color" />
                                    <x-input-error :messages="$errors->get('app_background_color')" class="mt-2" />
                                    <div id="picker-body"></div>
                                    <div id="piker-body-viewer" class="block mt-1 w-full h-10 rounded"></div>
                                </div>
                            </div>
                            
                            <div class="my-5 grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="app_background" :value="__('App background')" />
                                    <div class="img-app-preview my-5 w-full h-72" style="background-image: url({{$settings->app_background}})"></div>
                                    <input
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                        aria-describedby="app_background_help" id="app_background" name="app_background" type="file">
                                    <x-input-error class="mt-2" :messages="$errors->get('app_background')" />
                                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="app_background_help">
                                        {{__('Image must be less than 2MB and JPG or PNG format.')}} <br>
                                    </div>
                                </div>
                                <div>
                                    <x-input-label for="app_animated_background" :value="__('App background (animated)')" />
                                    <div class="img-app-preview my-5 w-full h-72" style="background-image: url({{$settings->app_animated_background}})"></div>
                                    <input
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                        aria-describedby="app_animated_background_help" id="app_animated_background" name="app_animated_background" type="file">
                                    <x-input-error class="mt-2" :messages="$errors->get('app_animated_background')" />
                                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="app_animated_background_help">
                                        {{__('Image must be less than 2MB and JPG or PNG format.')}} <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="my-5">
                        <button type="submit"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">{{ __('Save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <x-footer.tinymce-config/>

    @section('scripts')
    <!-- CodeMirror Core JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.15/codemirror.min.js"></script>
    <!-- CSS Syntax Highlighting Mode -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.15/mode/css/css.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {

            CodeMirror.fromTextArea(document.getElementById("custom_css"), {
                mode: "css",
                theme: "monokai", // or "default"
                lineNumbers: false,
                lineWrapping: true,
                tabSize: 4,
            });

            const moduleOcr = document.getElementById('module_ocr');
            const moduleTrivia = document.getElementById('module_trivia');
            const radioTicketsModule = document.querySelectorAll('input[type="radio"][name="ocr_ticket_active"]');

            radioTicketsModule.forEach(radioButton => {
                radioButton.addEventListener('change', function() {
                    if (this.value == '0') {
                        moduleOcr.classList.add('hidden');
                        moduleTrivia.classList.remove('hidden');
                        disable_inputs(moduleOcr);
                        enable_inputs(moduleTrivia);
                    } else {
                        moduleOcr.classList.remove('hidden');
                        moduleTrivia.classList.add('hidden');
                        enable_inputs(moduleOcr);
                        disable_inputs(moduleTrivia);
                    }
                });
            });

            function disable_inputs(targetDiv){
                var inputElements = targetDiv.querySelectorAll('input');
                inputElements.forEach(input => {
                    input.disabled = true;
                });
            }

            function enable_inputs(targetDiv){
                var inputElements = targetDiv.querySelectorAll('input');
                inputElements.forEach(input => {
                    input.disabled = false;
                });
            }
            if(document.getElementById('picker-ranking1-color') !== null){
                const rankingGradient1Picker = new ColorPicker({
                    color: '{{$settings->ranking_color_1}}',
                    background: '#fff',
                    el: document.getElementById('picker-ranking1-color'),
                    width: 250,
                    height: 150,
                });

                rankingGradient1Picker.onChange(function(){
                    currentColor = rankingGradient1Picker.getHexString();
                    let inputColor = document.getElementById('ranking_color_1');
                    inputColor.value = currentColor;

                    let pickerViewer = document.getElementById('picker-ranking1-viewer');
                    pickerViewer.style.backgroundColor = currentColor;
                });
            }
            if(document.getElementById('picker-ranking2-color') !== null){
                const rankingGradient2Picker = new ColorPicker({
                    color: '{{$settings->ranking_color_2}}',
                    background: '#fff',
                    el: document.getElementById('picker-ranking2-color'),
                    width: 250,
                    height: 150,
                });

                rankingGradient2Picker.onChange(function(){
                    currentColor = rankingGradient2Picker.getHexString();
                    let inputColor = document.getElementById('ranking_color_2');
                    inputColor.value = currentColor;

                    let pickerViewer = document.getElementById('picker-ranking2-viewer');
                    pickerViewer.style.backgroundColor = currentColor;
                });

            }
            const disabledGradient1Picker = new ColorPicker({
                color: '{{$settings->disabled_gradient_1}}',
                background: '#fff',
                el: document.getElementById('picker-disabled1-color'),
                width: 250,
                height: 150,
            });

            disabledGradient1Picker.onChange(function(){
                currentColor = disabledGradient1Picker.getHexString();
                let inputColor = document.getElementById('disabled_gradient_1');
                inputColor.value = currentColor;

                let pickerViewer = document.getElementById('picker-disabled1-viewer');
                pickerViewer.style.backgroundColor = currentColor;
            });

            const disabledGradient2Picker = new ColorPicker({
                color: '{{$settings->disabled_gradient_2}}',
                background: '#fff',
                el: document.getElementById('picker-disabled2-color'),
                width: 250,
                height: 150,
            });

            disabledGradient2Picker.onChange(function(){
                currentColor = disabledGradient2Picker.getHexString();
                let inputColor = document.getElementById('disabled_gradient_2');
                inputColor.value = currentColor;

                let pickerViewer = document.getElementById('picker-disabled2-viewer');
                pickerViewer.style.backgroundColor = currentColor;
            });

            const cardsFontColorPicker = new ColorPicker({
                color: '{{$settings->cards_font_color}}',
                background: '#fff',
                el: document.getElementById('picker-cardsfont-color'),
                width: 250,
                height: 150,
            });

            cardsFontColorPicker.onChange(function(){
                currentColor = cardsFontColorPicker.getHexString();
                let inputColor = document.getElementById('cards_font_color');
                inputColor.value = currentColor;

                let pickerViewer = document.getElementById('picker-cardsfont-viewer');
                pickerViewer.style.backgroundColor = currentColor;
            });

            const cardsBgPicker = new ColorPicker({
                color: '{{ $settings->cards_background_color ? $settings->cards_background_color : "transparent"}}',
                background: '#fff',
                el: document.getElementById('picker-cardsbg-color'),
                width: 250,
                height: 150,
            });

            cardsBgPicker.onChange(function(){
                currentColor = cardsBgPicker.getHexString();
                let inputColor = document.getElementById('cards_background_color');
                inputColor.value = currentColor;

                let pickerViewer = document.getElementById('picker-cardsbg-viewer');
                pickerViewer.style.backgroundColor = currentColor;
            });

            const primaryBtnPicker = new ColorPicker({
                color: '{{$settings->primary_button_color}}',
                background: '#fff',
                el: document.getElementById('picker-btn-color'),
                width: 250,
                height: 150,
            });

            primaryBtnPicker.onChange(function(){
                currentColor = primaryBtnPicker.getHexString();
                let inputColor = document.getElementById('primary_button_color');
                inputColor.value = currentColor;

                let pickerViewer = document.getElementById('piker-btn-color-viewer');
                pickerViewer.style.backgroundColor = currentColor;
            });

            const primaryBtnBgPicker = new ColorPicker({
                color: '{{$settings->primary_button_background}}',
                background: '#fff',
                el: document.getElementById('picker-btn-bg'),
                width: 250,
                height: 150,
            });

            primaryBtnBgPicker.onChange(function(){
                currentColor = primaryBtnBgPicker.getHexString();
                let inputColor = document.getElementById('primary_button_background');
                inputColor.value = currentColor;

                let pickerViewer = document.getElementById('piker-btn-bg-viewer');
                pickerViewer.style.backgroundColor = currentColor;
            });

            const bodyPicker = new ColorPicker({
                color: '{{$settings->app_background_color}}',
                background: '#fff',
                el: document.getElementById('picker-body'),
                width: 250,
                height: 150,
            });

            bodyPicker.onChange(function(){
                currentColor = bodyPicker.getHexString();
                let inputColor = document.getElementById('app_background_color');
                inputColor.value = currentColor;

                let pickerViewer = document.getElementById('piker-body-viewer');
                pickerViewer.style.backgroundColor = currentColor;
            });

            const headerPicker = new ColorPicker({
                color: '{{$settings->header_background_color}}',
                background: '#fff',
                el: document.getElementById('picker-header'),
                width: 250,
                height: 150,
            });

            headerPicker.onChange(function(){
                currentColor = headerPicker.getHexString();
                let inputColor = document.getElementById('header_background_color');
                inputColor.value = currentColor;

                let pickerViewer = document.getElementById('piker-header-viewer');
                pickerViewer.style.backgroundColor = currentColor;
            });
        });
    </script>
        
    @endsection
</x-panel-layout>
