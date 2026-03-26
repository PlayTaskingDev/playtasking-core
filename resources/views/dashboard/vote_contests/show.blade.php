<x-app-layout>
    <x-slot name="title">
        {{ $vote_contest->title }}
    </x-slot>
    <x-slot name="description">
        {{ $vote_contest->description }}
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 overflow-hidden p-3">
                <div class="game-card rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-3">

                    <x-campaign-menu :campaign-games="$campaign_games" :campaign-tickets="$campaign_tickets" :campaign-coupons="$campaign_coupons" :campaign-url="route('campaign.show', ['tenant' => tenant('id'), 'slug' => $vote_contest->campaign->slug])" :active="'games'" />

                    @if (!is_null($vote_contest->game_banner_video))
                        <div class="aspect-w-16 aspect-h-9 mb-6">
                            <iframe src="{{$vote_contest->game_banner_video}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    @endif

                    @if(is_null($vote_contest->game_banner_video) && !is_null($vote_contest->game_banner))
                        @if ($vote_contest->game_banner_url)
                            <a href="{{ $vote_contest->game_banner_url }}" target="_blank" rel="noopener noreferrer">
                                <img src="{{$vote_contest->game_banner}}" alt="" class="w-full rounded mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
                            </a>
                        @else
                            <img src="{{$vote_contest->game_banner}}" alt="" class="w-full rounded mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
                        @endif
                    @endif
                        
                    <h2
                        class="font-semibold text-2xl dark:text-gray-200 leading-tight pb-5 uppercase game-heading">
                        {{ $vote_contest->title }}
                    </h2>
                    
                    <p class="font-bold mb-5">
                        {{ $vote_contest->description }}
                    </p>
                    
                    <div class="mt-5">
                        <form action="{{route('vote_contest.store', ['tenant' => tenant('id')])}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="vote_contest" value="{{$vote_contest->id}}" />
                            <div class="mt-3">
                                <x-input-label for="title" :value="__('Title')" />
                                <x-text-input id="title" class="block mt-1 w-full text-black" type="text" name="title"
                                    :value="old('title')" required autofocus autocomplete="title" />
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>
                            <div class="mt-3">
                                <x-text-input id="asset" class="block mt-1 w-full text-black bg-white" type="file" name="asset"
                                    :value="old('asset')" required />
                                <x-input-label class="my-3" for="ticket" :value="__('strings.contest_asset_limit', ['size' => $vote_contest->mb_size])" />
                                <x-input-error :messages="$errors->get('asset')" class="mt-2" />
                            </div>
                            <div class="flex items-center justify-center mt-4">
                                <x-primary-button class="w-full" id="publishBtn">
                                    {{ __('Publish') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="voteOverlay" class="w-full h-full fixed top-0 left-0 bg-black opacity-75 z-50 hidden">
        <div class="flex justify-center items-center mt-[50vh]">
            <svg aria-hidden="true" class="inline w-24 h-24 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
            </svg>
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    @section('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const publishBtn = document.getElementById('publishBtn');
                const overlay = document.getElementById('voteOverlay');

                publishBtn.addEventListener('click', function(e){
                    overlay.classList.remove('hidden')
                });
            });
        </script>
    @endsection

</x-app-layout>
