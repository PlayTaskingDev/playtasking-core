
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
