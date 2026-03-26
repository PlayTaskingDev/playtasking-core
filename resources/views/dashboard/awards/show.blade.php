<x-app-layout>
    <x-slot name="title">
        {{ $award->title }}
    </x-slot>
    <x-slot name="description">
        {{ $award->description }}
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 overflow-hidden p-3">
                <div class="game-card rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">

                    <x-campaign-menu :campaign-games="$campaign_games" :campaign-tickets="$campaign_tickets" :campaign-coupons="$campaign_coupons" :campaign-url="route('campaign.show', ['tenant' => tenant('id'), 'slug' => $campaign->slug])" :active="$active_icon" />
                        
                    <div class="py-5">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight dark:text-white uppercase game-heading">
                            {{ get_app_setting('award_show_title') }}
                        </h5>
                        <p class="my-5 font-normal text-gray-700 dark:text-gray-400">
                            {!! $award->title !!}
                        </p>
                        <div class="award-content text-center mt-3">
                            {!! $award->content !!}
                        </div>
                    </div>
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

    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const awardMessage = document.querySelector('#message');
                const awardCode = document.querySelector('#code');
                const awardValidity = document.querySelector('#validity');

                if(awardMessage) {
                    const product = document.createElement('p');
                    product.style.color = 'unset';
                    product.innerHTML = '{{ $award_code->product }}';
                    awardMessage.appendChild(product);
                }

                if(awardCode) {
                    const code = document.createElement('p');
                    code.style.color = 'unset';
                    code.innerHTML = '{{ $award_code->code }}';
                    awardCode.appendChild(code);
                }

                if(awardValidity) {
                    const validity = document.createElement('p');
                    validity.style.color = 'unset';
                    validity.innerHTML = '{{ $award_code->validity }}';
                    awardValidity.appendChild(validity);
                }
                const rankingTime = document.querySelector('#rankingTime');
              
                if(rankingTime) {
                    const time = document.createElement('p');
                    time.style.color = 'unset';
                    time.innerHTML = '@if(isset($ranking_time) && !empty($ranking_time)){{$ranking_time}}@endif';
                    rankingTime.appendChild(time);
                }
            });
        </script>
    @endsection
</x-app-layout>
