<x-app-layout>
    <x-slot name="title">
        {{ __('Tickets') }}
    </x-slot>
    <x-slot name="description">
        {{ __('Tickets') }}
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 overflow-hidden">
                <div
                    class="game-card rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-3 p-3">
                    @if (session('status') === 'success')
                        <h2 class="font-semibold text-2xl dark:text-gray-200 leading-tight pb-5 uppercase game-heading">
                            {{__('Ticket stored successfully')}}
                        </h2>
                        <img src="{{ get_app_setting('tickets_success_response') }}" alt="" class="w-full">
                    @elseif(session('status') === 'error')
                        <h2 class="font-semibold text-2xl dark:text-gray-200 leading-tight pb-5 uppercase game-heading">
                            {{__('Wrong answer')}}
                        </h2>
                        <img src="{{ get_app_setting('tickets_failed_response') }}" alt="" class="w-full">
                    @elseif(session('status') === 'duplicated')
                        <h2 class="font-semibold text-2xl dark:text-gray-200 leading-tight pb-5 uppercase game-heading">
                            {{__('Duplicated ticket')}}
                        </h2>
                        <img src="{{ get_app_setting('tickets_duplicated_image') }}" alt="" class="w-full">
                    @else
                        <h2 class="font-semibold text-2xl dark:text-gray-200 leading-tight pb-5 uppercase game-heading">
                            {{ __('Error') }}
                        </h2>
                        <p class="font-bold mb-5">
                            {{ __('An error ocurred trying to store the ticket. Please, try again.') }}
                        </p>
                    @endif
                    @if(false) {{-- Disabled for now AlbertoPaz--}}
                        <div class="mt-6 text-center">
                            <x-primary-link href="{{ route('ticketsdash.create', ['tenant' => tenant('id'), 'slug' => $campaign->slug]) }}"
                                class="inline-flex items-center">
                                {{ __('Enter more tickets') }}
                            </x-primary-link>
                        </div>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>