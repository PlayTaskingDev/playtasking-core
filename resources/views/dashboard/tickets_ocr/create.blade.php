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
                    class="game-card rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-3">

                    <x-campaign-menu :campaign-games="$campaign_games" :campaign-tickets="$campaign_tickets" :campaign-coupons="$campaign_coupons" :campaign-url="route('campaign.show', ['tenant' => tenant('id'), 'slug' => $campaign->slug])" :active="'tickets'" />

                    <h2 class="font-semibold text-2xl dark:text-gray-200 leading-tight pb-5 uppercase game-heading">
                        {{ __('Upload your ticket') }}
                    </h2>

                    <h2 class="mb-5 font-bold text-xl">
                        {{get_app_setting('tickets_form_legend')}}
                    </h2>
                    <form id="ticket_create_form" action="{{route('tickets.ocr.store', ['tenant' => tenant('id')])}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mt-3">
                            <x-input-label for="ticket" :value="__('Ticket image')" />
                            <x-text-input id="ticket" class="block mt-1 w-full text-black bg-white" type="file" name="ticket"
                                :value="old('ticket')" required />
                            <x-input-error :messages="$errors->get('ticket')" class="mt-2" />
                        </div>
                        
                        <div class="mt-6">
                            <x-primary-button class="w-full">
                                {{ __('Upload ticket') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

