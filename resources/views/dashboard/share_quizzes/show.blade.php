<x-app-layout>
    <x-slot name="title">
        {{ $share_quiz->title }}
    </x-slot>
    <x-slot name="description">
        {{ $share_quiz->description }}
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 overflow-hidden">
                <div class="game-card rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-3">

                    <x-campaign-menu :campaign-games="$campaign_games" :campaign-tickets="$campaign_tickets" :campaign-coupons="$campaign_coupons" :campaign-url="route('campaign.show', ['tenant' => tenant('id'), 'slug' => $share_quiz->campaign->slug])" :active="'games'" />

                    @if (session('status'))
                        <x-alert :status="session('status')" class="text-red-800 bg-red-50 font-bold mx-auto sm:px-6 lg:px-8 p-4 my-4 text-sm rounded-lg"
                            role="alert" />
                    @endif

                    @if (!is_null($share_quiz->game_banner_video))
                        <div class="aspect-w-16 aspect-h-9 mb-6">
                            <iframe src="{{$share_quiz->game_banner_video}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    @endif

                    @if(is_null($share_quiz->game_banner_video) && !is_null($share_quiz->game_banner))
                        @if ($share_quiz->game_banner_url)
                            <a href="{{ $share_quiz->game_banner_url }}" target="_blank" rel="noopener noreferrer">
                                <img src="{{$share_quiz->game_banner}}" alt="" class="w-full rounded mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
                            </a>
                        @else
                            <img src="{{$share_quiz->game_banner}}" alt="" class="w-full rounded mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
                        @endif
                    @endif

                    <h2
                        class="font-semibold text-2xl dark:text-gray-200 leading-tight pb-5 uppercase game-heading">
                        {{ __('Share') }}
                    </h2>
                    
                    <p class="font-bold mb-5">
                        {{ $share_quiz->description }}
                    </p>
                    @if($share_quiz->featured_image_url)
                    <div class="mb-6">
                        <img src="{{$share_quiz->featured_image_url}}" alt="{{$share_quiz->title}}" title="{{$share_quiz->title}}" class="h-auto w-full">
                    </div>
                    @endif
                    @if ($share_quiz->featured_video_url)
                    <div class="aspect-w-16 aspect-h-9 mb-6">
                        <iframe src="{{$share_quiz->featured_video_url}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    @endif
                    
                    <div class="my-5 font-bold">
                        {{__('Please, do not change the predefined text, otherwise the validation will not work.')}}
                    </div>
                    <div class="flex justify-center">
                        <button id="fb_share" type="button" class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700 text-center inline-flex items-center">
                            <svg class="rtl:rotate-180 w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 16 16"><path fill="currentColor" d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131c.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951"/></svg>
                            {{__('Share')}}
                        </button>
                        <a id="x_share" href="https://twitter.com/intent/tweet?text={{$share_quiz->share_text}}&url={{$share_quiz->share_url}}" class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700 text-center inline-flex items-center">
                            <svg class="rtl:rotate-180 w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" width="0.88em" height="1em" viewBox="0 0 448 512"><path fill="currentColor" d="M64 32C28.7 32 0 60.7 0 96v320c0 35.3 28.7 64 64 64h320c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64zm297.1 84L257.3 234.6L379.4 396h-95.6L209 298.1L123.3 396H75.8l111-126.9L69.7 116h98l67.7 89.5l78.2-89.5zm-37.8 251.6L153.4 142.9h-28.3l171.8 224.7h26.3z"/></svg>
                            {{__('Share')}}
                        </a>
                    </div>
                    <div class="mt-5">
                        <form action="{{route('share_quiz.done', ['tenant' => tenant('id')])}}" method="POST">
                            @csrf
                            <input type="hidden" name="share_quiz" value="{{$share_quiz->id}}" />
                            <div>
                                <x-input-label class="mb-3" for="post_url" :value="__('Once you share the URL, paste here the post link of your publication. Only Facebook and Twitter links are allowed.')" />
                                <x-text-input id="post_url" class="block mt-1 w-full text-black" type="text" name="post_url"
                                    :value="old('post_url')" required autofocus autocomplete="post_url" />
                                <x-input-error :messages="$errors->get('post_url')" class="mt-2" />
                            </div>
                            <div class="flex items-center justify-center mt-4">
                                <x-primary-button class="w-full">
                                    {{ __('Validate publication') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>
    <script type="text/javascript" async src="https://platform.twitter.com/widgets.js"></script>

    <script>
        window.fbAsyncInit = function() {
            FB.init({
                appId: '{{env('FACEBOOK_APP_ID')}}',
                xfbml: true,
                version: 'v20.0'
            });
        };

        const fbButton = document.getElementById('fb_share');
        fbButton.addEventListener('click', function(e){
            FB.ui(
                {
                    display: 'popup',
                    method: 'share',
                    hashtag: '{{$share_quiz->share_text}}',
                    quote: '{{$share_quiz->share_text}}',
                    href: '{{$share_quiz->share_url}}',
                },
                // callback
                function(response) {}
            );
        });
    </script>
    @endsection
</x-app-layout>
