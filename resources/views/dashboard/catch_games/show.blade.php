<x-app-layout>
    <x-slot name="title">
        {{ $catch_game->title }}
    </x-slot>
    <x-slot name="description">
        {{ $catch_game->description }}
    </x-slot>
    <x-slot name="settingspzl">
        {{ $puzzle_settings }}
    </x-slot>

    @section('header_scripts')
        <style>
            #gameContainer {
                position: relative;
                overflow: hidden;
                height: 600px;
                background: url('{{ $catch_game->game_bg_image }}') no-repeat center center;
                background-size: cover;
            }

            #basket {
                position: absolute;
                bottom: 0;
                left: 42%;
                width: 100px;
                height: 150px;
                background: url('{{ $catch_game->basket_image }}') no-repeat center center;
                background-size: contain;
                z-index: 10;
            }

            .object {
                position: absolute;
                top: 0;
                width: 50px;
                height: 50px;
                z-index: 5;
            }

            #hud {
                padding: 5px 10px;
                border-radius: 5px;
                z-index: 20;
            }

            #message {
                position: absolute;
                width: 90%;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                font-size: 32px;
                font-weight: bold;
                display: none;
                z-index: 30;
            }
        </style>
    @endsection

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800">
                <div class="game-card rounded-lg dark:bg-gray-800 dark:border-gray-700 p-3">
                    <div id="game-holder">

                        <x-campaign-menu :campaign-games="$campaign_games" :campaign-tickets="$campaign_tickets" :campaign-coupons="$campaign_coupons" :campaign-url="route('campaign.show', ['tenant' => tenant('id'), 'slug' => $catch_game->campaign->slug])" :active="'games'" />
                        
                        @if (!is_null($catch_game->game_banner_video))
                        <div class="aspect-w-16 aspect-h-9 mb-6">
                            <iframe src="{{$catch_game->game_banner_video}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                        @endif

                        @if(is_null($catch_game->game_banner_video) && !is_null($catch_game->game_banner))
                            @if ($catch_game->game_banner_url)
                                <a href="{{ $catch_game->game_banner_url }}" target="_blank" rel="noopener noreferrer">
                                    <img src="{{$catch_game->game_banner}}" alt="" class="w-full rounded mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
                                </a>
                            @else
                                <img src="{{$catch_game->game_banner}}" alt="" class="w-full rounded mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
                            @endif
                        @endif
                        <h2
                            class="font-semibold text-2xl dark:text-gray-200 leading-tight pb-5 uppercase game-heading">
                            {{ $catch_game->title }}
                        </h2>
                        
                        <p class="font-bold mb-5 text-center">
                            {{ $catch_game->description }}
                        </p>

                        {{-- Game --}}
                        <div id="try-again" class="hidden text-center rounded mx-3 mb-5">
                            <h2 class="text-3xl mb-3 font-bold">
                                {{__('Time is up!')}}
                            </h2>
                            <a href="{{route('catch_game.show', ['tenant' => tenant('id'), 'slug'=>$catch_game->slug])}}">
                                <h3 class="text-2xl mb-3">{{__('Try again.')}}</h3>
                            </a>
                            <img src="{{$catch_game->failed_image}}" alt="{{__('Time is up!')}}" class="w-full">
                        </div>
                        <div id="gameContainer" class="w-full rounded-lg">
                            <div id="hud" class="text-center font-bold mb-5">
                                {{ __('Score') }}: <span id="score">0</span> |
                                {{ __('Goal') }}: <span id="goal">20</span> |
                                {{ __('Time') }}: <span id="time">30</span>s
                            </div>
                            <button id="startButton" type="button"
                                class="block rounded-full px-5 py-2.5 mx-auto mb-3 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 font-bold {{ $catch_game->btn_shadow ? 'buttons-shadow' : '' }}" style="{{ $catch_game->btn_border ? 'border: 2px solid ' . $catch_game->btn_border_color . ';' : '' }} {{ $catch_game->btn_text_color ? 'color: ' . $catch_game->btn_text_color . ';' : '' }}background: {{ $catch_game->btn_background_color_1 . '; background: linear-gradient(135deg, ' . $catch_game->btn_background_color_1 . ' 0%, ' . $catch_game->btn_background_color_2 . ' 85%);' }}">
                                {{ __('Start') }}
                            </button>
                            <div id="basket"></div>
                            <div id="message" class="text-white text-center"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @vite(['resources/js/glgc/gcgpzl.js'])
</x-app-layout>
