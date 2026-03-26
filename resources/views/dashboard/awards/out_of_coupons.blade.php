<x-app-layout>
    <x-slot name="title">
        {{ __('So sorry') }}
    </x-slot>
    <x-slot name="description">
        {{ __('Out of benefits') }}
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 overflow-hidden p-3">
                <div
                    class="game-card rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-3">

                    <x-campaign-menu :campaign-games="$campaign_games" :campaign-tickets="$campaign_tickets" :campaign-coupons="$campaign_coupons" :campaign-url="route('campaign.show', ['tenant' => tenant('id'), 'slug' => $campaign->slug])" :active="'games'" />

                    <div class="p-5">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight dark:text-white uppercase game-heading">
                            {{ get_app_setting('out_of_coupons_title') }}
                        </h5>
                        <p class="my-5 font-normal text-gray-700 dark:text-gray-400">
                            <img src="{{ get_app_setting('out_of_coupons_image') }}" alt="{{ get_app_setting('out_of_coupons_title') }}" class="w-full">
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
