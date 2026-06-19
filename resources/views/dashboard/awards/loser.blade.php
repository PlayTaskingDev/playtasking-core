<x-app-layout>
    <x-slot name="title">
        {{ $model->title }}
    </x-slot>
    <x-slot name="description">
        {{ $model->description }}
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 overflow-hidden">
                <div
                    class="game-card rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-3 p-3">
                    
                    <x-campaign-menu :campaign-games="$campaign_games" :campaign-tickets="$campaign_tickets" :campaign-coupons="$campaign_coupons" :campaign-url="route('campaign.show', ['tenant' => tenant('id'), 'slug' => $campaign->slug])" :active="'games'" />

                    <div class="p-5">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight dark:text-white uppercase game-heading">
                            {{__('So sorry')}}
                        </h5>
                        <p class="my-3 font-normal text-gray-700 dark:text-gray-400">
                            {{ $model->failed_response }}
                        </p>
                    </div>
                    @if($out_time)
                        <img class="rounded-t-lg w-full" src="{{ $model->failed_image_out_time }}" alt="{{ $model->title }}" />
                    @else
                        <img class="rounded-t-lg w-full" src="{{ $model->failed_image }}" alt="{{ $model->title }}" />
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="game-navigator mb-3 text-center">
        <x-primary-link href="{{ route('campaign.show', ['tenant' => tenant('id'), 'slug' => $campaign->slug]) }}"
            class="inline-flex w-32 mx-auto">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="m9 14l-4-4l4-4"/><path d="M5 10h11a4 4 0 1 1 0 8h-1"/></g></svg>
            {{ __('Back') }}
        </x-primary-link>
    </div>
</x-app-layout>
