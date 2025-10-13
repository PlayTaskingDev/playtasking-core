<x-guest-layout>
    <div class="flex flex-col items-center justify-center px-6 py-6 mx-auto lg:py-0">
        <div
            class="w-full dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
            <h1 class="text-white text-4xl italic font-black my-4 text-center">{{__('Forgot your password?')}}</h1>
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8 bg-white rounded-lg shadow">
                <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email', ['tenant' => tenant('id')]) }}">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                            :value="old('email')" required autofocus />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-center mt-4">
                        <x-primary-button>
                            {{ __('Email Password Reset Link') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
