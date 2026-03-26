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

                    <h2 class="mb-5 font-bold text-xl">
                        {{get_app_setting('coupons_form_legend')}}
                    </h2>
                    <form id="coupon_submission_form" action="{{route('coupons.validation', ['tenant' => tenant('id')])}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <x-text-input id="coupon_code" class="block mt-1 w-full text-black" type="text" name="coupon_code"
                                :value="old('coupon_code')" required autofocus autocomplete="coupon_code" placeholder="{{get_app_setting('coupons_field_placeholder')}}" />
                            <x-input-label for="coupon_code" :value="__('Coupon code')" class="mt-1" />
                            <x-input-error :messages="$errors->get('coupon_code')" class="mt-2" />
                        </div>
                        
                        <div class="mt-6">
                            <x-primary-button class="w-full">
                                {{ __('Validate coupon') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

