<x-panel-layout>
    <x-slot name="title">
        {{ $title }}
    </x-slot>
    <x-slot name="description">
        {{ $description }}
    </x-slot>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tickets') }}
        </h1>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white rounded-lg shadow-md">
            <div class="relative overflow-x-auto mx-5">
                <div class="my-5 grid grid-cols-2 gap-4">
                    <div>
                        <p class="mb-3"><strong>{{ __('Created at') }}</strong>: {{ $ticket->created_at }}</p>
                        <p class="mb-3"><strong>{{ __('Campaign') }}</strong>: {{ $ticket->campaign ? $ticket->campaign->name : '' }}</p>
                        <p class="mb-3"><strong>{{ __('Transaction number') }}</strong>: {{ $ticket->transaction_number }}</p>
                        <p class="mb-3"><strong>{{ __('Transaction date') }}</strong>: {{ $ticket->transaction_date ? $ticket->transaction_date : $ticket->date . ' ' . $ticket->time }}</p>
                        <p class="mb-3"><strong>{{ __('Transaction amount') }}</strong>: {{ $ticket->transaction_amount }}</p>
                        <p class="mb-3"><strong>{{ __('Store') }}</strong>: {{ $ticket->store }}</p>
                        <p class="mb-3"><strong>{{ __('Guessed') }}</strong>: {{ $ticket->guessed }}</p>
                        <p class="mb-3"><strong>{{ __('Points') }}</strong>: {{ $ticket->points }}</p>
                        <p class="mb-3"><strong>{{ __('User') }}</strong>: {{ $ticket->user->email }}</p>
                        <p class="mb-3"><strong>{{ __('OCR string') }}</strong>: {{ $ticket->ocr_string }}</p>
                    </div>
                    <div>
                        <p>
                            <img src="{{ $ticket->img_url }}" alt="Image" class="w-full object-cover rounded-lg">
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-panel-layout>