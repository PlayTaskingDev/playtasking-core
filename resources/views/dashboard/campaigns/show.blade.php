<x-app-layout>
    <x-slot name="title">
        {{$campaign->name}}
    </x-slot>
    <x-slot name="description">
        {{$campaign->description}}
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 overflow-hidden">
                <div class="game-card rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-3">

                    <x-campaign-menu :campaign-games="$campaign_games" :campaign-tickets="$campaign_tickets" :campaign-coupons="$campaign_coupons" :campaign-url="route('campaign.show', ['tenant' => tenant('id'), 'slug' => $campaign->slug])" :active="'games'" />

                    <div id="games">
                        {{-- Click Wins --}}
                        @foreach ($campaign->click_wins as $click_win)
                        <div class="border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}"
                        style="{{$user_games && $user_games->contains($click_win->id) ? 'background:' . get_app_setting('disabled_gradient_1') . '; background: linear-gradient(135deg, ' . get_app_setting('disabled_gradient_1') . ' 0%, ' . get_app_setting('disabled_gradient_2') . ' 85%);' : 'background:' . $click_win->gradient_1 . '; background: linear-gradient(135deg, ' . $click_win->gradient_1 . ' 0%, ' . $click_win->gradient_2 . ' 85%);'}}">
                            <div class="flex flex-row">
                                <div class="basis-full">
                                    <img src="{{$user_games && $user_games->contains($click_win->id) ? $click_win->featured_image_disabled : $click_win->featured_image}}" alt="{{$click_win->title}}" title="{{$click_win->title}}" class="rounded">
                                </div>
                            </div>
                            <div class="flex flex-row">
                                <div class="basis-full py-5 px-3">
                                    {{-- <p class="py-5 -mt-2.5 text-white font-bold ">{{$click_win->title}}</p>
                                    <p class="pb-5 -mt-3.5 text-white font-bold ">{{$click_win->description}}</p> --}}
                                    <div class="text-start">
                                        <a href="{{route('click_win.show', ['tenant' => tenant('id'), 'slug' => $click_win->slug])}}" 
                                            class="rounded-full px-5 py-2.5 me-2 mb-3 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 font-bold {{ $click_win->btn_shadow ? 'buttons-shadow' : '' }}" style="{{ $click_win->btn_border ? 'border: 2px solid ' . $click_win->btn_border_color . ';' : '' }} {{ $click_win->btn_text_color ? 'color: ' . $click_win->btn_text_color . ';' : '' }}background: {{ $click_win->btn_background_color_1 . '; background: linear-gradient(135deg, ' . $click_win->btn_background_color_1 . ' 0%, ' . $click_win->btn_background_color_2 . ' 85%);' }}">
                                            {{$user_games && $user_games->contains($click_win->id) ? $click_win->btn_text_inactive : $click_win->btn_text_active}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        {{-- Share Quizzes --}}
                        @foreach ($campaign->share_quizzes as $share_quiz)
                        <div class="border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}"
                        style="{{$user_games && $user_games->contains($share_quiz->id) ? 'background:' . get_app_setting('disabled_gradient_1') . '; background: linear-gradient(135deg, ' . get_app_setting('disabled_gradient_1') . ' 0%, ' . get_app_setting('disabled_gradient_2') . ' 85%);' : 'background:' . $share_quiz->gradient_1 . '; background: linear-gradient(135deg, ' . $share_quiz->gradient_1 . ' 0%, ' . $share_quiz->gradient_2 . ' 85%);'}}">
                            <div class="flex flex-row">
                                <div class="basis-full">
                                    <img src="{{$user_games && $user_games->contains($share_quiz->id) ? $share_quiz->featured_image_disabled : $share_quiz->featured_image}}" alt="{{$share_quiz->title}}" title="{{$share_quiz->title}}" class="rounded">
                                </div>
                            </div>
                            <div class="flex flex-row">
                                <div class="basis-full py-5 px-3">
                                    {{-- <p class="py-5 -mt-2.5 text-white font-bold ">{{$share_quiz->title}}</p>
                                    <p class="pb-5 -mt-3.5 text-white font-bold ">{{$share_quiz->description}}</p> --}}
                                    <a href="{{route('share_quiz.show', ['tenant' => tenant('id'), 'slug' => $share_quiz->slug])}}" 
                                        class="rounded-full px-5 py-2.5 me-2 mb-3 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 font-bold {{ $share_quiz->btn_shadow ? 'buttons-shadow' : '' }}" style="{{ $share_quiz->btn_border ? 'border: 2px solid ' . $share_quiz->btn_border_color . ';' : '' }} {{ $share_quiz->btn_text_color ? 'color: ' . $share_quiz->btn_text_color . ';' : '' }}background: {{ $share_quiz->btn_background_color_1 . '; background: linear-gradient(135deg, ' . $share_quiz->btn_background_color_1 . ' 0%, ' . $share_quiz->btn_background_color_2 . ' 85%);' }}">
                                        {{$user_games && $user_games->contains($share_quiz->id) ? $share_quiz->btn_text_inactive : $share_quiz->btn_text_active}}
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                        {{-- Quizzes --}}
                        @foreach ($campaign->quizzes as $quiz)
                        <div class="border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}"
                        style="{{$user_games && $user_games->contains($quiz->id) ? 'background:' . get_app_setting('disabled_gradient_1') . '; background: linear-gradient(135deg, ' . get_app_setting('disabled_gradient_1') . ' 0%, ' . get_app_setting('disabled_gradient_2') . ' 85%);' : 'background:' . $quiz->gradient_1 . '; background: linear-gradient(135deg, ' . $quiz->gradient_1 . ' 0%, ' . $quiz->gradient_2 . ' 85%);'}}">
                            <div class="flex flex-row">
                                <div class="basis-full">
                                    <img src="{{$user_games && $user_games->contains($quiz->id) ? $quiz->featured_image_disabled : $quiz->featured_image}}" alt="{{$quiz->title}}" title="{{$quiz->title}}" class="rounded">
                                </div>
                            </div>
                            <div class="flex flex-row">
                                <div class="basis-full py-5 px-3">
                                    {{-- <p class="py-5 -mt-2.5 text-white font-bold ">{{$quiz->title}}</p>
                                    <p class="pb-5 -mt-3.5 text-white font-bold ">{{$quiz->description}}</p> --}}
                                    <div class="text-start">
                                        <a href="{{route('quiz.show', ['tenant' => tenant('id'), 'slug' => $quiz->slug])}}" 
                                            class="rounded-full px-5 py-2.5 me-2 mb-3 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 font-bold {{ $quiz->btn_shadow ? 'buttons-shadow' : '' }}" style="{{ $quiz->btn_border ? 'border: 2px solid ' . $quiz->btn_border_color . ';' : '' }} {{ $quiz->btn_text_color ? 'color: ' . $quiz->btn_text_color . ';' : '' }}background: {{ $quiz->btn_background_color_1 . '; background: linear-gradient(135deg, ' . $quiz->btn_background_color_1 . ' 0%, ' . $quiz->btn_background_color_2 . ' 85%);' }}">
                                            {{$user_games && $user_games->contains($quiz->id) ? $quiz->btn_text_inactive : $quiz->btn_text_active}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        {{-- Memory Quizzes --}}
                        @foreach ($campaign->memory_quizzes as $memory_quiz)
                        <div class="border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}"
                        style="{{$user_games && $user_games->contains($memory_quiz->id) ? 'background:' . get_app_setting('disabled_gradient_1') . '; background: linear-gradient(135deg, ' . get_app_setting('disabled_gradient_1') . ' 0%, ' . get_app_setting('disabled_gradient_2') . ' 85%);' : 'background:' . $memory_quiz->gradient_1 . '; background: linear-gradient(135deg, ' . $memory_quiz->gradient_1 . ' 0%, ' . $memory_quiz->gradient_2 . ' 85%);'}}">
                            <div class="flex flex-row">
                                <div class="basis-full">
                                    <img src="{{$user_games && $user_games->contains($memory_quiz->id) ? $memory_quiz->featured_image_disabled : $memory_quiz->featured_image}}" alt="{{$memory_quiz->title}}" title="{{$memory_quiz->title}}" class="rounded">
                                </div>
                            </div>
                            <div class="flex flex-row">
                                <div class="basis-full py-5 px-3">
                                    {{-- <p class="py-5 -mt-2.5 text-white font-bold ">{{$memory_quiz->title}}</p>
                                    <p class="pb-5 -mt-3.5 text-white font-bold ">{{$memory_quiz->description}}</p> --}}
                                    <div class="text-start">
                                        <a href="{{route('memory_quiz.show', ['tenant' => tenant('id'), 'slug' => $memory_quiz->slug])}}" 
                                            class="rounded-full px-5 py-2.5 me-2 mb-3 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 font-bold {{ $memory_quiz->btn_shadow ? 'buttons-shadow' : '' }}" style="{{ $memory_quiz->btn_border ? 'border: 2px solid ' . $memory_quiz->btn_border_color . ';' : '' }} {{ $memory_quiz->btn_text_color ? 'color: ' . $memory_quiz->btn_text_color . ';' : '' }}background: {{ $memory_quiz->btn_background_color_1 . '; background: linear-gradient(135deg, ' . $memory_quiz->btn_background_color_1 . ' 0%, ' . $memory_quiz->btn_background_color_2 . ' 85%);' }}">
                                            {{$user_games && $user_games->contains($memory_quiz->id) ? $memory_quiz->btn_text_inactive : $memory_quiz->btn_text_active}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                        {{-- Vote Contests --}}
                        @foreach ($campaign->vote_contests as $vote_contest)
                        <div class="border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}"
                        style="{{$user_games && $user_games->contains($vote_contest->id) ? 'background:' . get_app_setting('disabled_gradient_1') . '; background: linear-gradient(135deg, ' . get_app_setting('disabled_gradient_1') . ' 0%, ' . get_app_setting('disabled_gradient_2') . ' 85%);' : 'background:' . $vote_contest->gradient_1 . '; background: linear-gradient(135deg, ' . $vote_contest->gradient_1 . ' 0%, ' . $vote_contest->gradient_2 . ' 85%);'}}">
                            <div class="flex flex-row">
                                <div class="basis-full">
                                    <img src="{{$user_games && $user_games->contains($vote_contest->id) ? $vote_contest->featured_image_disabled : $vote_contest->featured_image}}" alt="{{$vote_contest->title}}" title="{{$vote_contest->title}}" class="rounded">
                                </div>
                            </div>
                            <div class="flex flex-row">
                                <div class="basis-full py-5 px-3">
                                    {{-- <p class="py-5 -mt-2.5 text-white font-bold ">{{$vote_contest->title}}</p>
                                    <p class="pb-5 -mt-3.5 text-white font-bold ">{{$vote_contest->description}}</p> --}}
                                    <div class="text-start">
                                        <a href="{{route('vote_contest.show', ['tenant' => tenant('id'), 'slug' => $vote_contest->slug])}}" 
                                            class="rounded-full px-5 py-2.5 me-2 mb-3 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 font-bold {{ $vote_contest->btn_shadow ? 'buttons-shadow' : '' }}" style="{{ $vote_contest->btn_border ? 'border: 2px solid ' . $vote_contest->btn_border_color . ';' : '' }} {{ $vote_contest->btn_text_color ? 'color: ' . $vote_contest->btn_text_color . ';' : '' }}background: {{ $vote_contest->btn_background_color_1 . '; background: linear-gradient(135deg, ' . $vote_contest->btn_background_color_1 . ' 0%, ' . $vote_contest->btn_background_color_2 . ' 85%);' }}">
                                            {{$user_games && $user_games->contains($vote_contest->id) ? $vote_contest->btn_text_inactive : $vote_contest->btn_text_active}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        {{-- Aplazo Games --}}
                        @foreach ($campaign->aplazo_games as $aplazo_game)
                        <div class="border border-gray-200 rounded-t-lg shadow dark:bg-gray-800 dark:border-gray-700 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}"
                        style="{{'background:' . $aplazo_game->gradient_1 . '; background: linear-gradient(135deg, ' . $aplazo_game->gradient_1 . ' 0%, ' . $aplazo_game->gradient_2 . ' 85%);'}}">
                            <div class="flex flex-row badis-full">
                                <img src="{{$aplazo_game->featured_image}}" alt="" class="rounded">
                            </div>
                        </div>
                        <div class="flex flex-row items-center bg-white rounded-b-lg mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
                            <div class="basis-1/1 py-5 px-3">
                                <a href="{{route('aplazo.show', ['tenant' => tenant('id'), 'slug' => $aplazo_game->slug])}}" 
                                    class="rounded-full px-5 py-2.5 me-2 mb-3 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 font-bold {{ $aplazo_game->btn_shadow ? 'buttons-shadow' : '' }}" style="{{ $aplazo_game->btn_border ? 'border: 2px solid ' . $aplazo_game->btn_border_color . ';' : '' }} {{ $aplazo_game->btn_text_color ? 'color: ' . $aplazo_game->btn_text_color . ';' : '' }}background: {{ $aplazo_game->btn_background_color_1 . '; background: linear-gradient(135deg, ' . $aplazo_game->btn_background_color_1 . ' 0%, ' . $aplazo_game->btn_background_color_2 . ' 85%);' }}">
                                    <img src="{{ Storage::disk('public')->url('dummy_assets/shopping.svg') }}" alt="{{ __('Pay with APLAZO') }}" width="25" height="25" class="inline-block">
                                    {{$user_games && $user_games->contains($aplazo_game->id) ? $aplazo_game->btn_text_inactive : $aplazo_game->btn_text_active}}
                                </a>
                            </div>
                        </div>
                        @endforeach

                        {{-- Puzzles --}}
                        @foreach ($campaign->puzzles as $puzzle)
                        <div class="border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}"
                        style="{{$user_games && $user_games->contains($puzzle->id) ? 'background:' . get_app_setting('disabled_gradient_1') . '; background: linear-gradient(135deg, ' . get_app_setting('disabled_gradient_1') . ' 0%, ' . get_app_setting('disabled_gradient_2') . ' 85%);' : 'background:' . $puzzle->gradient_1 . '; background: linear-gradient(135deg, ' . $puzzle->gradient_1 . ' 0%, ' . $puzzle->gradient_2 . ' 85%);'}}">
                            <div class="flex flex-row">
                                <div class="basis-full">
                                    <img src="{{$user_games && $user_games->contains($puzzle->id) ? $puzzle->featured_image_disabled : $puzzle->featured_image}}" alt="{{$puzzle->title}}" title="{{$puzzle->title}}" class="rounded">
                                </div>
                            </div>
                            <div class="flex flex-row">
                                <div class="basis-full py-5 px-3">
                                    {{-- <p class="py-5 -mt-2.5 text-white font-bold ">{{$puzzle->title}}</p>
                                    <p class="pb-5 -mt-3.5 text-white font-bold ">{{$puzzle->description}}</p> --}}
                                    <div class="text-start">
                                        <a href="{{route('puzzle.show', ['tenant' => tenant('id'), 'slug' => $puzzle->slug])}}" 
                                            class="rounded-full px-5 py-2.5 me-2 mb-3 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 font-bold {{ $puzzle->btn_shadow ? 'buttons-shadow' : '' }}" style="{{ $puzzle->btn_border ? 'border: 2px solid ' . $puzzle->btn_border_color . ';' : '' }} {{ $puzzle->btn_text_color ? 'color: ' . $puzzle->btn_text_color . ';' : '' }}background: {{ $puzzle->btn_background_color_1 . '; background: linear-gradient(135deg, ' . $puzzle->btn_background_color_1 . ' 0%, ' . $puzzle->btn_background_color_2 . ' 85%);' }}">
                                            {{$user_games && $user_games->contains($puzzle->id) ? $puzzle->btn_text_inactive : $puzzle->btn_text_active}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        {{-- Catch Games --}}
                        @foreach ($campaign->catch_games as $catch_game)
                        <div class="border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}"
                        style="{{$user_games && $user_games->contains($catch_game->id) ? 'background:' . get_app_setting('disabled_gradient_1') . '; background: linear-gradient(135deg, ' . get_app_setting('disabled_gradient_1') . ' 0%, ' . get_app_setting('disabled_gradient_2') . ' 85%);' : 'background:' . $catch_game->gradient_1 . '; background: linear-gradient(135deg, ' . $catch_game->gradient_1 . ' 0%, ' . $catch_game->gradient_2 . ' 85%);'}}">
                            <div class="flex flex-row">
                                <div class="basis-full">
                                    <img src="{{$user_games && $user_games->contains($catch_game->id) ? $catch_game->featured_image_disabled : $catch_game->featured_image}}" alt="{{$catch_game->title}}" title="{{$catch_game->title}}" class="rounded">
                                </div>
                            </div>
                            <div class="flex flex-row">
                                <div class="basis-full py-5 px-3">
                                    {{-- <p class="py-5 -mt-2.5 text-white font-bold ">{{$puzzle->title}}</p>
                                    <p class="pb-5 -mt-3.5 text-white font-bold ">{{$catch_game->description}}</p> --}}
                                    <div class="text-start">
                                        <a href="{{route('catch_game.show', ['tenant' => tenant('id'), 'slug' => $catch_game->slug])}}" 
                                            class="rounded-full px-5 py-2.5 me-2 mb-3 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 font-bold {{ $catch_game->btn_shadow ? 'buttons-shadow' : '' }}" style="{{ $catch_game->btn_border ? 'border: 2px solid ' . $catch_game->btn_border_color . ';' : '' }} {{ $catch_game->btn_text_color ? 'color: ' . $catch_game->btn_text_color . ';' : '' }}background: {{ $catch_game->btn_background_color_1 . '; background: linear-gradient(135deg, ' . $catch_game->btn_background_color_1 . ' 0%, ' . $catch_game->btn_background_color_2 . ' 85%);' }}">
                                            {{$user_games && $user_games->contains($catch_game->id) ? $catch_game->btn_text_inactive : $catch_game->btn_text_active}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        {{-- Smash Games --}}
                        @foreach ($campaign->smash_games as $smash_game)
                        <div class="border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}"
                        style="{{$user_games && $user_games->contains($smash_game->id) ? 'background:' . get_app_setting('disabled_gradient_1') . '; background: linear-gradient(135deg, ' . get_app_setting('disabled_gradient_1') . ' 0%, ' . get_app_setting('disabled_gradient_2') . ' 85%);' : 'background:' . $smash_game->gradient_1 . '; background: linear-gradient(135deg, ' . $smash_game->gradient_1 . ' 0%, ' . $smash_game->gradient_2 . ' 85%);'}}">
                            <div class="flex flex-row">
                                <div class="basis-full">
                                    <img src="{{$user_games && $user_games->contains($smash_game->id) ? $smash_game->featured_image_disabled : $smash_game->featured_image}}" alt="{{$smash_game->title}}" title="{{$smash_game->title}}" class="rounded">
                                </div>
                            </div>
                            <div class="flex flex-row">
                                <div class="basis-full py-5 px-3">
                                    {{-- <p class="py-5 -mt-2.5 text-white font-bold ">{{$puzzle->title}}</p>
                                    <p class="pb-5 -mt-3.5 text-white font-bold ">{{$smash_game->description}}</p> --}}
                                    <div class="text-start">
                                        <a href="{{route('smash_game.show', ['tenant' => tenant('id'), 'slug' => $smash_game->slug])}}" 
                                            class="rounded-full px-5 py-2.5 me-2 mb-3 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 font-bold {{ $smash_game->btn_shadow ? 'buttons-shadow' : '' }}" style="{{ $smash_game->btn_border ? 'border: 2px solid ' . $smash_game->btn_border_color . ';' : '' }} {{ $smash_game->btn_text_color ? 'color: ' . $smash_game->btn_text_color . ';' : '' }}background: {{ $smash_game->btn_background_color_1 . '; background: linear-gradient(135deg, ' . $smash_game->btn_background_color_1 . ' 0%, ' . $smash_game->btn_background_color_2 . ' 85%);' }}">
                                            {{$user_games && $user_games->contains($smash_game->id) ? $smash_game->btn_text_inactive : $smash_game->btn_text_active}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        {{-- Flappy Games --}}
                        @foreach ($campaign->flappy_games as $flappy_game)
                        <div class="border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}"
                        style="{{$user_games && $user_games->contains($flappy_game->id) ? 'background:' . get_app_setting('disabled_gradient_1') . '; background: linear-gradient(135deg, ' . get_app_setting('disabled_gradient_1') . ' 0%, ' . get_app_setting('disabled_gradient_2') . ' 85%);' : 'background:' . $flappy_game->gradient_1 . '; background: linear-gradient(135deg, ' . $flappy_game->gradient_1 . ' 0%, ' . $flappy_game->gradient_2 . ' 85%);'}}">
                            <div class="flex flex-row">
                                <div class="basis-full">
                                    <img src="{{$user_games && $user_games->contains($flappy_game->id) ? $flappy_game->featured_image_disabled : $flappy_game->featured_image}}" alt="{{$flappy_game->title}}" title="{{$flappy_game->title}}" class="rounded">
                                </div>
                            </div>
                            <div class="flex flex-row">
                                <div class="basis-full py-5 px-3">
                                    {{-- <p class="py-5 -mt-2.5 text-white font-bold ">{{$puzzle->title}}</p>
                                    <p class="pb-5 -mt-3.5 text-white font-bold ">{{$flappy_game->description}}</p> --}}
                                    <div class="text-start">
                                        <a href="{{route('flappy_game.show', ['tenant' => tenant('id'), 'slug' => $flappy_game->slug])}}" 
                                            class="rounded-full px-5 py-2.5 me-2 mb-3 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 font-bold {{ $flappy_game->btn_shadow ? 'buttons-shadow' : '' }}" style="{{ $flappy_game->btn_border ? 'border: 2px solid ' . $flappy_game->btn_border_color . ';' : '' }} {{ $flappy_game->btn_text_color ? 'color: ' . $flappy_game->btn_text_color . ';' : '' }}background: {{ $flappy_game->btn_background_color_1 . '; background: linear-gradient(135deg, ' . $flappy_game->btn_background_color_1 . ' 0%, ' . $flappy_game->btn_background_color_2 . ' 85%);' }}">
                                            {{$user_games && $user_games->contains($flappy_game->id) ? $flappy_game->btn_text_inactive : $flappy_game->btn_text_active}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>