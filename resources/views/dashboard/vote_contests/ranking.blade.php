<x-app-layout>
    <x-slot name="title">
        {{ __('Ranking table') }}
    </x-slot>
    <x-slot name="description">
        {{ __('Ranking table') }}
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 overflow-hidden">
                <div class="game-card rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-3">

                    <x-campaign-menu :campaign-games="$campaign_games" :campaign-tickets="$campaign_tickets" :campaign-coupons="$campaign_coupons" :campaign-url="route('campaign.show', ['tenant' => tenant('id'), 'slug' => $campaign->slug])" :active="'games'" />


                    @if (!is_null($vote_contest->game_banner))
                        <img src="{{$vote_contest->game_banner}}" alt="" class="w-full rounded mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
                    @endif
                    <h2
                        class="font-semibold text-2xl dark:text-gray-200 leading-tight pb-3 uppercase game-heading">
                        {{ $vote_contest->title }}
                    </h2>

                    @if ($user->contest_assets->isNotEmpty())
                        <h3 class="text-center text-xl font-bold my-5">{{$user->contest_assets[0]->title}}</h3>
                        <div class="p-4 rounded text-white mb-3 font-bold" style="background-color:{{get_app_setting('primary_button_background')}}">
                            {{__('Total votes')}}: {{$user->contest_assets[0]->points}}
                        </div>
                        <div class="relative my-3">
                            {{-- <div class="absolute inline-flex items-center justify-center w-20 h-20 text-md font-bold border-2 border-white rounded-full -top-4 -end-4 dark:border-gray-900 z-50" style="background-color:{{get_app_setting('primary_button_background')}} ; color:{{get_app_setting('primary_button_color')}} ;">{{$user->contest_assets[0]->points}}</div> --}}
                            @if ($vote_contest->asset_type == 'photo')
                                <img src="{{$user->contest_assets[0]->asset_url}}" alt="{{$user->contest_assets[0]->title}}" title="{{$user->contest_assets[0]->title}}" class="h-auto w-full rounded">
                            @else
                                <div class="w-full h-auto max-w-full">
                                    <div style="padding:56.25% 0 0 0;position:relative;">
                                        <iframe src="{{$user->contest_assets[0]->iframe_video_url}}" frameborder="0" allow="autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media" style="position:absolute;top:0;left:0;width:100%;height:100%;" title="My puss in boots"></iframe>
                                    </div>
                                    <script src="https://player.vimeo.com/api/player.js" async></script>
                                </div>
                            @endif
                            @auth
                                @if(auth()->user()->id == $user->contest_assets[0]->user_id)
                                <form method="POST" action="{{ route('vote_contest.destroy', ['tenant' => tenant('id'), 'asset' => $user->contest_assets[0]]) }}">
                                    @csrf
                                    @method('delete')
                                    <button class="p-3 absolute w-16 h-16 right-0 -top-0 rounded bg-red-800" 
                                        onclick="return confirm('{{ __('Are you sure to delete this?') }}')">
                                        <svg class="w-[32px] h-[32px] text-white dark:text-white font-bold" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            @endauth
                        </div>
                        <div class="flex items-center justify-center mt-4">
                            <x-primary-button class="ml-4" type="button" id="copyAssetUrl">
                                {{ __('Copy link') }}
                            </x-primary-button>
                        </div>
                    @endif

                    <h2 class="font-semibold text-2xl dark:text-gray-200 leading-tight py-5 uppercase game-heading">
                        {{ __('Ranking table') }}
                    </h2>

                    @if (count($top_users) > 0)
                    <div id="podium" class="grid grid-cols-{{count($top_users)}} gap-4 mb-5">
                        @isset($top_users[1])
                        <div class="top-user text-center">
                            <a href="{{route('asset.show', ['tenant' => tenant('id'), 'asset' => $top_users[1]])}}" target="_blank" rel="noopener noreferrer">
                                @if (get_app_setting('second_place_icon'))
                                    <img src="{{ get_app_setting('second_place_icon') }}" alt="" class="mx-auto sm:max-w-32 max-w-24">
                                @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 32 32" class="block m-auto"><path fill="currentColor" d="M6 30h20v-5a7.01 7.01 0 0 0-7-7h-6a7.01 7.01 0 0 0-7 7zM9 9a7 7 0 1 0 7-7a7 7 0 0 0-7 7"/></svg>
                                @endif
                                {{$top_users[1]->user->short_name}}<br>
                                {{$top_users[1]->user->contest_assets[0]->points}}<br>
                                <svg class="w-[32px] h-[32px] text-white inline-block" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z"/>
                                    <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                            </a>
                        </div>
                        @endisset
                        @isset($top_users[0])
                        <div class="top-user text-center">
                            <a href="{{route('asset.show', ['tenant' => tenant('id'), 'asset' => $top_users[0]])}}" target="_blank" rel="noopener noreferrer">
                                @if (get_app_setting('first_place_icon'))
                                    <img src="{{ get_app_setting('first_place_icon') }}" alt="" class="mx-auto sm:max-w-32 max-w-24">
                                @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 128 128" class="block m-auto"><g fill="#f79329"><path d="m91.56 50.38l14.35 44.94l-36.36-4.71z"/><path d="M105.91 96.5c-.05 0-.1 0-.15-.01l-36.37-4.71c-.39-.05-.72-.29-.9-.64s-.17-.76.01-1.1l22.02-40.23c.23-.41.69-.65 1.15-.61c.47.04.87.36 1.01.81l14.24 44.62c.14.19.22.43.22.68c0 .65-.53 1.18-1.18 1.18c0 .01-.03.01-.05.01M71.4 89.66l32.82 4.25l-12.94-40.55zM40.19 34.91a5.46 5.46 0 0 1-5.46 5.46c-3.01 0-5.46-2.45-5.46-5.46c0-3.02 2.44-5.46 5.46-5.46s5.46 2.44 5.46 5.46"/><path d="M34.73 41.54a6.65 6.65 0 0 1-6.64-6.64a6.65 6.65 0 0 1 6.64-6.64a6.65 6.65 0 0 1 6.64 6.64a6.65 6.65 0 0 1-6.64 6.64m0-10.91c-2.36 0-4.28 1.92-4.28 4.28s1.92 4.28 4.28 4.28s4.29-1.92 4.29-4.28s-1.93-4.28-4.29-4.28m58.85-1.18c3.01.18 5.31 2.77 5.13 5.78c-.17 3.01-2.77 5.3-5.77 5.13a5.45 5.45 0 0 1-5.13-5.77c.18-3.02 2.76-5.32 5.77-5.14"/><path d="m93.26 41.54l-.39-.01c-1.77-.1-3.4-.89-4.57-2.21a6.62 6.62 0 0 1-1.67-4.8a6.647 6.647 0 0 1 6.63-6.25l.39.01c3.66.22 6.46 3.38 6.24 7.03a6.64 6.64 0 0 1-6.63 6.23m.23-10.92c-2.5 0-4.37 1.77-4.5 4.03c-.07 1.14.31 2.24 1.07 3.1s1.8 1.36 2.95 1.43l.25.01c2.26 0 4.14-1.77 4.27-4.03c.14-2.36-1.67-4.39-4.03-4.54zM36.43 50.38L22.09 95.32l36.36-4.71z"/><path d="M22.09 96.5c-.34 0-.68-.15-.91-.42c-.26-.31-.34-.73-.22-1.11L35.3 50.03c.14-.45.54-.77 1.01-.81c.51-.05.92.19 1.15.61l22.02 40.23c.18.34.19.75.01 1.1c-.17.35-.51.58-.9.64l-36.36 4.71c-.04-.01-.09-.01-.14-.01m14.63-43.14L23.77 93.92l32.82-4.25z"/></g><use href="#notoV1Crown1"/><use href="#notoV1Crown1"/><defs><path id="notoV1Crown0" d="M119.5 53.43a1.18 1.18 0 0 0-1.29.22L87.25 82.71L65.16 49.72c-.22-.33-.58-.52-.98-.52c-.39 0-.76.19-.98.51l-22.19 33l-30.95-29.07a1.18 1.18 0 0 0-1.29-.22c-.43.19-.71.63-.69 1.1l1.27 47.52c0 10.33 24.06 18.43 54.78 18.43s54.78-8.1 54.78-18.4l1.27-47.55c.02-.46-.25-.9-.68-1.09"/><path id="notoV1Crown1" fill="#fcc21b" d="M72.17 28.76c0 4.51-3.66 8.17-8.17 8.17s-8.18-3.66-8.18-8.17c0-4.52 3.66-8.17 8.18-8.17s8.17 3.65 8.17 8.17m-58.72 6.15c0 3.58-2.9 6.48-6.49 6.48c-3.58 0-6.48-2.9-6.48-6.48c0-3.59 2.9-6.49 6.48-6.49c3.59 0 6.49 2.9 6.49 6.49m101.09 0c0 3.58 2.9 6.48 6.49 6.48c3.58 0 6.49-2.9 6.49-6.48a6.49 6.49 0 0 0-6.49-6.49a6.49 6.49 0 0 0-6.49 6.49"/></defs><use fill="#fcc21b" href="#notoV1Crown0"/><clipPath id="notoV1Crown2"><use href="#notoV1Crown0"/></clipPath><path fill="#d7598b" d="m119.91 78.06l.01.01l-.59 18.85h-.01c-4.2-.13-7.46-4.45-7.3-9.66c.16-5.22 3.69-9.33 7.89-9.2m-111.54 0l-.01.01l.58 18.85h.02c4.19-.13 7.46-4.45 7.29-9.66c-.16-5.22-3.69-9.33-7.88-9.2" clip-path="url(#notoV1Crown2)"/><path fill="#d7598b" d="M72.8 96.55c0 5.58-3.88 10.11-8.67 10.11c-4.78 0-8.66-4.53-8.66-10.11c0-5.59 3.88-10.11 8.66-10.11c4.79-.01 8.67 4.52 8.67 10.11"/><g fill="#ed6c30"><path d="M89.9 102.14c-.13 2.7-2.12 4.79-4.44 4.68c-2.31-.11-4.08-2.4-3.94-5.09c.14-2.71 2.13-4.8 4.44-4.68c2.31.1 4.07 2.39 3.94 5.09"/><ellipse cx="103.04" cy="98.95" rx="4.89" ry="4.2" transform="rotate(-87.013 103.044 98.958)"/></g><g fill="#ed6c30"><path d="M38.37 102.14c.13 2.7 2.12 4.79 4.44 4.68c2.31-.11 4.08-2.4 3.94-5.09c-.13-2.71-2.12-4.8-4.43-4.68c-2.32.1-4.09 2.39-3.95 5.09"/><ellipse cx="25.23" cy="98.95" rx="4.19" ry="4.89" transform="rotate(-2.987 25.234 98.957)"/></g></svg>
                                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 32 32" class="block m-auto"><path fill="currentColor" d="M6 30h20v-5a7.01 7.01 0 0 0-7-7h-6a7.01 7.01 0 0 0-7 7zM9 9a7 7 0 1 0 7-7a7 7 0 0 0-7 7"/></svg>
                                @endif
                                {{$top_users[0]->user->short_name}}<br>
                                {{$top_users[0]->user->contest_assets[0]->points}}<br>
                                <svg class="w-[32px] h-[32px] text-white inline-block" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z"/>
                                    <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                            </a>
                        </div>
                        @endisset
                        @isset($top_users[2])
                        <div class="top-user text-center">
                            <a href="{{route('asset.show', ['tenant' => tenant('id'), 'asset' => $top_users[2]])}}" target="_blank" rel="noopener noreferrer">
                                @if (get_app_setting('third_place_icon'))
                                    <img src="{{ get_app_setting('third_place_icon') }}" alt="" class="mx-auto sm:max-w-32 max-w-24">
                                @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 32 32" class="block m-auto"><path fill="currentColor" d="M6 30h20v-5a7.01 7.01 0 0 0-7-7h-6a7.01 7.01 0 0 0-7 7zM9 9a7 7 0 1 0 7-7a7 7 0 0 0-7 7"/></svg>
                                @endif
                                {{$top_users[2]->user->short_name}}<br>
                                {{$top_users[2]->user->contest_assets[0]->points}}<br>
                                <svg class="w-[32px] h-[32px] text-white inline-block" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z"/>
                                    <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                            </a>
                        </div>
                        @endisset
                    </div>
                    
                    <div id="ranking-table">
                        @foreach ($top_ten_users as $top_ten_user)
                            <div class="top-ten flex items-center mb-3 rounded-md border border-solid" style="{{$top_ten_user->user->id == $user->id ? 'background:' . get_app_setting('ranking_color_1') . '; background: linear-gradient(135deg, ' . get_app_setting('ranking_color_1') . ' 0%, ' . get_app_setting('ranking_color_2') . ' 85%); color:white;' : ''}}">
                                <div class="basis-5/6 self-center p-2 border-r {{$top_ten_user->user->id == $user->id ? 'border-white' : ''}} border-solid">
                                    <a href="{{route('asset.show', ['tenant' => tenant('id'), 'asset' => $top_ten_user])}}" target="_blank" rel="noopener noreferrer" title="{{__('View')}}" >
                                        {{$top_ten_user->user->short_name}} 
                                        <svg class="w-[32px] h-[32px] text-white inline-block" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z"/>
                                            <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                        </svg>
                                    </a>
                                </div>
                                <div class="basis-1/6 p-2 text-center">{{$top_ten_user->points}}</div>
                            </div>
                        @endforeach
                        @if (!$user_in_top)
                            <div class="top-ten flex items-center mb-3 rounded-md border border-black border-solid" style="background: {{get_app_setting('ranking_color_1')}}; background: linear-gradient(135deg, {{get_app_setting('ranking_color_1')}} 0%, {{get_app_setting('ranking_color_2')}} 85%); color:white;">
                                <div class="basis-5/6 self-center p-2 border-r border-white border-solid">
                                    <a href="{{route('asset.show', ['tenant' => tenant('id'), 'asset' => $user->contest_assets[0]])}}" target="_blank" rel="noopener noreferrer" title="{{__('View')}}">
                                        {{$user->short_name}} 
                                        <svg class="w-[32px] h-[32px] text-white inline-block" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z"/>
                                            <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                        </svg>
                                    </a>
                                </div>
                                <div class="basis-1/6 p-2 text-center">{{$user->contest_assets->isNotEmpty() ? $user->contest_assets[0]->points : 'N/A'}}</div>
                            </div>
                        @endif
                    </div>
                    @else
                        <p>
                            {{__('No top competitors at this time.')}}
                        </p>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const buttonCopy = document.getElementById('copyAssetUrl');
                buttonCopy.addEventListener('click', function(e){
                    navigator.clipboard.writeText('{{route('asset.show', ['tenant' => tenant('id'), 'asset' => $user->contest_assets[0]])}}');
                    alert('{{__('URL copied')}}');
                });

                const $targetEl = document.getElementById('vote-modal');
                const modal = new Modal($targetEl);
                @if ($errors->isNotEmpty())
                    modal.show();
                @endif
            });
        </script>
    @endsection
</x-app-layout>