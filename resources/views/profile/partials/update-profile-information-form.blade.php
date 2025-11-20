<section>
    <header>
        @if (session('status') === 'profile-updated')
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
                x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" role="alert">
                <span class="font-medium">{{ __('Saved.') }}!</span>
            </div>
        @endif

        <h2 class="text-2xl font-medium text-gray-900 dark:text-gray-100 mb-5">
            {{ __('Hi') }}, {{auth()->user()->name}}
        </h2>

        {{-- <p class="my-3 dark:text-gray-400 font-bold">
            {{__('Your ranking')}}: {{auth()->user()->ranking}}
        </p>
        <p class="my-3 dark:text-gray-400 font-bold">
            {{__('Your points')}}: {{auth()->user()->points}}
        </p> --}}

        <div class="my-3 dark:text-gray-400 font-bold p-4 rounded-lg border border-gray-200 dark:border-gray-700">
            <a href="{{ route('dashboard.awards.index', ['tenant' => tenant('id')]) }}" class="flex items-center justify-between text-gray-900 dark:text-gray-100 hover:text-gray-700 dark:hover:text-gray-300">
                {{ get_app_setting('awards_section_title') }} 
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
            </svg>
            </a>
        </div>

        <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
        
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send', ['tenant' => tenant('id')]) }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update', ['tenant' => tenant('id')]) }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Phone -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('Phone')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone"
                :value="old('phone', $user->phone)" autofocus autocomplete="phone" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        @if (get_app_setting('members_number') == true)
        <!-- Members -->
        <div class="mt-4">
            <x-input-label for="members_number" :value="get_app_setting('members_placeholder')" />
            <x-text-input id="members_number" class="block mt-1 w-full" type="text" name="members_number"
                :value="old('members_number', $user->members_number)" autofocus  />
            <x-input-error :messages="$errors->get('members_number')" class="mt-2" />
        </div>
        @endif

        <div>
            <x-input-label for="avatar" :value="__('Avatar')" />
            <input
                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                aria-describedby="user_avatar_help" id="avatar" name="avatar" type="file">
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
            <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="user_avatar_help">
                {{__('Image must be less than 2MB and JPG or PNG format.')}}
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
        </div>
    </form>
</section>
