<x-app-layout>
    <x-slot name="title">
        {{ __('Coupons') }}
    </x-slot>
    <x-slot name="description">
        {{ __('Coupons') }}
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 overflow-hidden">
                <div
                    class="game-card rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-3 p-3">

                    <x-campaign-menu :campaign-games="$campaign_games" :campaign-tickets="$campaign_tickets" :campaign-coupons="$campaign_coupons" :campaign-url="route('campaign.show', ['tenant' => tenant('id'), 'slug' => $campaign->slug])" :active="'coupons'" />

                    <div class="p-5">
                        <p class="my-5 font-normal text-gray-700 dark:text-gray-400">
                            <img src="{{ get_app_setting('code_hunter_incorrect') }}" class="w-full">
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

