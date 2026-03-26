<x-app-layout>
    <x-slot name="title">
        {{ $puzzle->title }}
    </x-slot>
    <x-slot name="description">
        {{ $puzzle->description }}
    </x-slot>
    <x-slot name="settingspzl">
        {{ $puzzle_settings }}
    </x-slot>

    @section('header_scripts')
        <style>
            #forPuzzle {
                position: relative;
                width: 90%;
                height: 70vh;
                top: 0%;
                left: 5%;
                background-color: transparent;
                overflow: visible;
            }

            #forPuzzle img{
                border-radius: 15px;
            }

            .polypiece {
                display: block;
                overflow: hidden;
                position: absolute;
                cursor:grab;
                touch-action: none;
                user-select: none;
            }

            .moving {
                transition-property: top, left;
                transition-duration: 1s;
                transition-timing-function: linear;
            }

            .gameCanvas {
                display: none;
                overflow: hidden;
                position: absolute;
            }
        </style>
    @endsection

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800">
                <div class="game-card rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-3 p-3">
                    <div id="game-holder">

                        <x-campaign-menu :campaign-games="$campaign_games" :campaign-tickets="$campaign_tickets" :campaign-coupons="$campaign_coupons" :campaign-url="route('campaign.show', ['tenant' => tenant('id'), 'slug' => $puzzle->campaign->slug])" :active="'games'" />
                        
                        @if (!is_null($puzzle->game_banner_video))
                        <div class="aspect-w-16 aspect-h-9 mb-6">
                            <iframe src="{{$puzzle->game_banner_video}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    @endif

                    @if(is_null($puzzle->game_banner_video) && !is_null($puzzle->game_banner))
                        @if ($puzzle->game_banner_url)
                            <a href="{{ $puzzle->game_banner_url }}" target="_blank" rel="noopener noreferrer">
                                <img src="{{$puzzle->game_banner}}" alt="" class="w-full rounded mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
                            </a>
                        @else
                            <img src="{{$puzzle->game_banner}}" alt="" class="w-full rounded mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
                        @endif
                    @endif
                        <h2
                            class="font-semibold text-2xl dark:text-gray-200 leading-tight pb-5 uppercase game-heading">
                            {{ __('Puzzle') }}
                        </h2>
                        
                        <p class="font-bold mb-5">
                            {{ $puzzle->description }}
                        </p>

                        <button id="startPuzzleBtn" type="button"
                            class="block rounded-full px-5 py-2.5 mx-auto mb-3 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 font-bold {{ $puzzle->btn_shadow ? 'buttons-shadow' : '' }}" style="{{ $puzzle->btn_border ? 'border: 2px solid ' . $puzzle->btn_border_color . ';' : '' }} {{ $puzzle->btn_text_color ? 'color: ' . $puzzle->btn_text_color . ';' : '' }}background: {{ $puzzle->btn_background_color_1 . '; background: linear-gradient(135deg, ' . $puzzle->btn_background_color_1 . ' 0%, ' . $puzzle->btn_background_color_2 . ' 85%);' }}">
                            {{ __('Start') }}
                        </button>

                        <div id="timer" class="rounded p-3 mb-5 text-2xl text-center font-bold hidden">
                            {{ __('Remaining')}} <span></span> {{ __('seconds')}}
                        </div>
                        <input hidden id="shape" value="1">
                        <div class="dark:bg-gray-800 rounded puzzle-game relative" style="height: 70vh;">
                            <div id="forPuzzle" ></div>
                        </div>
                        <div id="try-again" class="hidden text-center rounded mx-3 mb-5">
                            <h2 class="text-3xl mb-3 font-bold">
                                {{__('Time is up!')}}
                            </h2>
                            <a href="{{route('puzzle.show', ['tenant' => tenant('id'), 'slug'=>$puzzle->slug])}}">
                                <h3 class="text-2xl mb-3">{{__('Try again.')}}</h3>
                            </a>
                            <img src="{{$puzzle->failed_image}}" alt="{{__('Time is up!')}}" class="w-full">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script src="/games/puzzle.js"></script>
    @endsection
    @vite(['resources/js/glgc/gpzl.js'])

</x-app-layout>
