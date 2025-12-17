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

                    <x-campaign-menu :campaign-games="$campaign_games" :campaign-tickets="$campaign_tickets" :campaign-coupons="$campaign_coupons" :campaign-url="route('campaign.show', ['tenant' => tenant('id'), 'slug' => $campaign->slug])" :active="'ranking'" />

                    @if($thereisAnyRankingEnabled)
                        <h2 class="font-semibold mt-6 text-2xl dark:text-gray-200 leading-tight pb-5 uppercase game-heading">
                            {{ __('Ranking table') }}
                        </h2>
                        <div class="relative font-inter antialiased">

                            <main class="relative flex flex-col justify-center  overflow-hidden">
                                <div class="w-full max-w-6xl mx-auto ">
                                    
                                    <!-- Tabs component -->
                                    <div x-data="{ activeTab: 
                                        @if(get_app_setting('ranking_enabled_tickets'))
                                            1
                                        @else
                                            2
                                        @endif
                                    }">
                                    
                                        <!-- Buttons -->
                                        <div class="flex justify-center">
                                            <div
                                                role="tablist"
                                                class="max-[480px]:max-w-[180px] inline-flex flex-wrap justify-center bg-slate-200 rounded-[20px] p-1 mb-4 min-[480px]:mb-6"
                                                @keydown.right.prevent.stop="$focus.wrap().next()"
                                                @keydown.left.prevent.stop="$focus.wrap().prev()"
                                                @keydown.home.prevent.stop="$focus.first()"
                                                @keydown.end.prevent.stop="$focus.last()"
                                            >
                                                <!-- Button #1 Tickets Ranking-->
                                                @if(get_app_setting('ranking_enabled_tickets'))
                                                    <button
                                                        id="tab-1"
                                                        class="inline-flex items-center  text-sm font-medium h-8 px-4 rounded-2xl whitespace-nowrap focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300 transition-colors duration-150 ease-in-out"
                                                        :class="activeTab === 1 ? 'bg-white text-slate-900' : 'text-slate-600 hover:text-slate-900'"
                                                        :tabindex="activeTab === 1 ? 0 : -1"
                                                        :aria-selected="activeTab === 1"
                                                        aria-controls="tabpanel-1"
                                                        @click="activeTab = 1"
                                                        @focus="activeTab = 1"
                                                    >
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-tab-ranking size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" />
                                                    </svg>
                                                    {{ __(' Tickets Ranking') }}</button>
                                                @endif
                                                <!-- Button #2 Game Time Ranking-->
                                                @if(get_app_setting('ranking_enabled_games'))
                                                    <button
                                                        id="tab-2"
                                                        class="inline-flex items-center  text-sm font-medium h-8 px-4 rounded-2xl whitespace-nowrap focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300 transition-colors duration-150 ease-in-out"
                                                        :class="activeTab === 2 ? 'bg-white text-slate-900' : 'text-slate-600 hover:text-slate-900'"
                                                        :tabindex="activeTab === 2 ? 0 : -1"
                                                        :aria-selected="activeTab === 2"
                                                        aria-controls="tabpanel-2"
                                                        @click="activeTab = 2"
                                                        @focus="activeTab = 2"
                                                    >
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-tab-ranking size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                    </svg>
                                                    {{ __('Game Time Ranking') }}</button>
                                                @endif
                                                <!-- Button #3 ... -->
                                            </div>
                                        </div>

                                        <!-- Tab panels -->
                                        <div class="max-w-[640px] mx-auto">
                                            <div class="relative flex flex-col">

                                                <!-- Panel #1 -->
                                                @if(get_app_setting('ranking_enabled_tickets'))
                                                <article
                                                    id="tabpanel-1"
                                                    class="w-full items-stretch flex flex-col"
                                                    role="tabpanel" 
                                                    tabindex="0"
                                                    aria-labelledby="tab-1"
                                                    x-show="activeTab === 1"
                                                    x-transition:enter="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-700 transform order-first"
                                                    x-transition:enter-start="opacity-0 -translate-y-8"
                                                    x-transition:enter-end="opacity-100 translate-y-0"
                                                    x-transition:leave="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-300 transform absolute"
                                                    x-transition:leave-start="opacity-100 translate-y-0"
                                                    x-transition:leave-end="opacity-0 translate-y-12"                        
                                                >
                                                    @if (count($top_users) > 0)
                                                        <div id="podium" class="grid grid-cols-3 gap-4 mb-5">
                                                            @isset($top_users[1])
                                                            <div class="top-user text-center">
                                                                @if (get_app_setting('second_place_icon'))
                                                                    <img src="{{ get_app_setting('second_place_icon') }}" alt="" class="mx-auto sm:max-w-32 max-w-24">
                                                                @else
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 32 32" class="block m-auto"><path fill="currentColor" d="M6 30h20v-5a7.01 7.01 0 0 0-7-7h-6a7.01 7.01 0 0 0-7 7zM9 9a7 7 0 1 0 7-7a7 7 0 0 0-7 7"/></svg>
                                                                @endif
                                                                {{$top_users[1]->short_name}}
                                                            </div>
                                                            @endisset
                                                            @isset($top_users[0])
                                                            <div class="top-user text-center">
                                                                @if (get_app_setting('first_place_icon'))
                                                                    <img src="{{ get_app_setting('first_place_icon') }}" alt="" class="mx-auto sm:max-w-32 max-w-24">
                                                                @else
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 128 128" class="block m-auto"><g fill="#f79329"><path d="m91.56 50.38l14.35 44.94l-36.36-4.71z"/><path d="M105.91 96.5c-.05 0-.1 0-.15-.01l-36.37-4.71c-.39-.05-.72-.29-.9-.64s-.17-.76.01-1.1l22.02-40.23c.23-.41.69-.65 1.15-.61c.47.04.87.36 1.01.81l14.24 44.62c.14.19.22.43.22.68c0 .65-.53 1.18-1.18 1.18c0 .01-.03.01-.05.01M71.4 89.66l32.82 4.25l-12.94-40.55zM40.19 34.91a5.46 5.46 0 0 1-5.46 5.46c-3.01 0-5.46-2.45-5.46-5.46c0-3.02 2.44-5.46 5.46-5.46s5.46 2.44 5.46 5.46"/><path d="M34.73 41.54a6.65 6.65 0 0 1-6.64-6.64a6.65 6.65 0 0 1 6.64-6.64a6.65 6.65 0 0 1 6.64 6.64a6.65 6.65 0 0 1-6.64 6.64m0-10.91c-2.36 0-4.28 1.92-4.28 4.28s1.92 4.28 4.28 4.28s4.29-1.92 4.29-4.28s-1.93-4.28-4.29-4.28m58.85-1.18c3.01.18 5.31 2.77 5.13 5.78c-.17 3.01-2.77 5.3-5.77 5.13a5.45 5.45 0 0 1-5.13-5.77c.18-3.02 2.76-5.32 5.77-5.14"/><path d="m93.26 41.54l-.39-.01c-1.77-.1-3.4-.89-4.57-2.21a6.62 6.62 0 0 1-1.67-4.8a6.647 6.647 0 0 1 6.63-6.25l.39.01c3.66.22 6.46 3.38 6.24 7.03a6.64 6.64 0 0 1-6.63 6.23m.23-10.92c-2.5 0-4.37 1.77-4.5 4.03c-.07 1.14.31 2.24 1.07 3.1s1.8 1.36 2.95 1.43l.25.01c2.26 0 4.14-1.77 4.27-4.03c.14-2.36-1.67-4.39-4.03-4.54zM36.43 50.38L22.09 95.32l36.36-4.71z"/><path d="M22.09 96.5c-.34 0-.68-.15-.91-.42c-.26-.31-.34-.73-.22-1.11L35.3 50.03c.14-.45.54-.77 1.01-.81c.51-.05.92.19 1.15.61l22.02 40.23c.18.34.19.75.01 1.1c-.17.35-.51.58-.9.64l-36.36 4.71c-.04-.01-.09-.01-.14-.01m14.63-43.14L23.77 93.92l32.82-4.25z"/></g><use href="#notoV1Crown1"/><use href="#notoV1Crown1"/><defs><path id="notoV1Crown0" d="M119.5 53.43a1.18 1.18 0 0 0-1.29.22L87.25 82.71L65.16 49.72c-.22-.33-.58-.52-.98-.52c-.39 0-.76.19-.98.51l-22.19 33l-30.95-29.07a1.18 1.18 0 0 0-1.29-.22c-.43.19-.71.63-.69 1.1l1.27 47.52c0 10.33 24.06 18.43 54.78 18.43s54.78-8.1 54.78-18.4l1.27-47.55c.02-.46-.25-.9-.68-1.09"/><path id="notoV1Crown1" fill="#fcc21b" d="M72.17 28.76c0 4.51-3.66 8.17-8.17 8.17s-8.18-3.66-8.18-8.17c0-4.52 3.66-8.17 8.18-8.17s8.17 3.65 8.17 8.17m-58.72 6.15c0 3.58-2.9 6.48-6.49 6.48c-3.58 0-6.48-2.9-6.48-6.48c0-3.59 2.9-6.49 6.48-6.49c3.59 0 6.49 2.9 6.49 6.49m101.09 0c0 3.58 2.9 6.48 6.49 6.48c3.58 0 6.49-2.9 6.49-6.48a6.49 6.49 0 0 0-6.49-6.49a6.49 6.49 0 0 0-6.49 6.49"/></defs><use fill="#fcc21b" href="#notoV1Crown0"/><clipPath id="notoV1Crown2"><use href="#notoV1Crown0"/></clipPath><path fill="#d7598b" d="m119.91 78.06l.01.01l-.59 18.85h-.01c-4.2-.13-7.46-4.45-7.3-9.66c.16-5.22 3.69-9.33 7.89-9.2m-111.54 0l-.01.01l.58 18.85h.02c4.19-.13 7.46-4.45 7.29-9.66c-.16-5.22-3.69-9.33-7.88-9.2" clip-path="url(#notoV1Crown2)"/><path fill="#d7598b" d="M72.8 96.55c0 5.58-3.88 10.11-8.67 10.11c-4.78 0-8.66-4.53-8.66-10.11c0-5.59 3.88-10.11 8.66-10.11c4.79-.01 8.67 4.52 8.67 10.11"/><g fill="#ed6c30"><path d="M89.9 102.14c-.13 2.7-2.12 4.79-4.44 4.68c-2.31-.11-4.08-2.4-3.94-5.09c.14-2.71 2.13-4.8 4.44-4.68c2.31.1 4.07 2.39 3.94 5.09"/><ellipse cx="103.04" cy="98.95" rx="4.89" ry="4.2" transform="rotate(-87.013 103.044 98.958)"/></g><g fill="#ed6c30"><path d="M38.37 102.14c.13 2.7 2.12 4.79 4.44 4.68c2.31-.11 4.08-2.4 3.94-5.09c-.13-2.71-2.12-4.8-4.43-4.68c-2.32.1-4.09 2.39-3.95 5.09"/><ellipse cx="25.23" cy="98.95" rx="4.19" ry="4.89" transform="rotate(-2.987 25.234 98.957)"/></g></svg>
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 32 32" class="block m-auto"><path fill="currentColor" d="M6 30h20v-5a7.01 7.01 0 0 0-7-7h-6a7.01 7.01 0 0 0-7 7zM9 9a7 7 0 1 0 7-7a7 7 0 0 0-7 7"/></svg>
                                                                @endif
                                                                {{$top_users[0]->short_name}}
                                                            </div>
                                                            @endisset
                                                            @isset($top_users[2])
                                                            <div class="top-user text-center">
                                                                @if (get_app_setting('third_place_icon'))
                                                                    <img src="{{ get_app_setting('third_place_icon') }}" alt="" class="mx-auto sm:max-w-32 max-w-24">
                                                                @else
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 32 32" class="block m-auto"><path fill="currentColor" d="M6 30h20v-5a7.01 7.01 0 0 0-7-7h-6a7.01 7.01 0 0 0-7 7zM9 9a7 7 0 1 0 7-7a7 7 0 0 0-7 7"/></svg>
                                                                @endif
                                                                {{$top_users[2]->short_name}}
                                                            </div>
                                                            @endisset
                                                        </div>
                                                        <div id="ranking-table" class="pr-4 pl-4">
                                                            @foreach ($top_ten_users as $top_ten_user)
                                                                <div class="top-ten flex mb-3 rounded-md border border-solid" style="{{$top_ten_user->is_user ? 'background:' . get_app_setting('ranking_color_1') . '; background: linear-gradient(135deg, ' . get_app_setting('ranking_color_1') . ' 0%, ' . get_app_setting('ranking_color_2') . ' 85%); color:white;' : ''}}">
                                                                    <div class="basis-5/6 p-2 border-r {{$top_ten_user->is_user ? 'border-white' : ''}} border-solid">{{$top_ten_user->short_name}}</div>
                                                                    <div class="basis-1/6 p-2 text-center">{{$top_ten_user->ranking}}</div>
                                                                </div>
                                                            @endforeach
                                                            @if (!$user_in_top)
                                                                <div class="top-ten flex mb-3 rounded-md border border-black border-solid" style="background: {{get_app_setting('ranking_color_1')}}; background: linear-gradient(135deg, {{get_app_setting('ranking_color_1')}} 0%, {{get_app_setting('ranking_color_2')}} 85%); color:white;">
                                                                    <div class="basis-5/6 p-2 border-r border-white border-solid">{{$user->short_name}}</div>
                                                                    <div class="basis-1/6 p-2 text-center">{{$user->ranking ? $user->ranking : 'N/A'}}</div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        @else
                                                            <p>
                                                                {{__('No top competitors at this time.')}}
                                                            </p>
                                                    @endif
                                                </article>
                                                @endif

                                                <!-- Panel #2 -->
                                                @if(get_app_setting('ranking_enabled_games'))
                                                <article
                                                    id="tabpanel-2"
                                                    class="w-full items-stretch flex flex-col"
                                                    role="tabpanel" 
                                                    tabindex="0"
                                                    aria-labelledby="tab-2"
                                                    x-show="activeTab === 2"
                                                    x-transition:enter="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-700 transform order-first"
                                                    x-transition:enter-start="opacity-0 -translate-y-8"
                                                    x-transition:enter-end="opacity-100 translate-y-0"
                                                    x-transition:leave="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-300 transform absolute"
                                                    x-transition:leave-start="opacity-100 translate-y-0"
                                                    x-transition:leave-end="opacity-0 translate-y-12"                        
                                                >
                                                    <div class="pt-2 pb-4 flex  justify-center">
                                                        <div class="w-[350px]  flex flex-row items-center">
                                                            <label for="modelGameId" class="block mb-2 text-sm font-medium text-gray-900 mr-4">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 6.087c0-.355.186-.676.401-.959.221-.29.349-.634.349-1.003 0-1.036-1.007-1.875-2.25-1.875s-2.25.84-2.25 1.875c0 .369.128.713.349 1.003.215.283.401.604.401.959v0a.64.64 0 0 1-.657.643 48.39 48.39 0 0 1-4.163-.3c.186 1.613.293 3.25.315 4.907a.656.656 0 0 1-.658.663v0c-.355 0-.676-.186-.959-.401a1.647 1.647 0 0 0-1.003-.349c-1.036 0-1.875 1.007-1.875 2.25s.84 2.25 1.875 2.25c.369 0 .713-.128 1.003-.349.283-.215.604-.401.959-.401v0c.31 0 .555.26.532.57a48.039 48.039 0 0 1-.642 5.056c1.518.19 3.058.309 4.616.354a.64.64 0 0 0 .657-.643v0c0-.355-.186-.676-.401-.959a1.647 1.647 0 0 1-.349-1.003c0-1.035 1.008-1.875 2.25-1.875 1.243 0 2.25.84 2.25 1.875 0 .369-.128.713-.349 1.003-.215.283-.4.604-.4.959v0c0 .333.277.599.61.58a48.1 48.1 0 0 0 5.427-.63 48.05 48.05 0 0 0 .582-4.717.532.532 0 0 0-.533-.57v0c-.355 0-.676.186-.959.401-.29.221-.634.349-1.003.349-1.035 0-1.875-1.007-1.875-2.25s.84-2.25 1.875-2.25c.37 0 .713.128 1.003.349.283.215.604.401.96.401v0a.656.656 0 0 0 .658-.663 48.422 48.422 0 0 0-.37-5.36c-1.886.342-3.81.574-5.766.689a.578.578 0 0 1-.61-.58v0Z" />
                                                                </svg>
                                                            </label>
                                                            
                                                            <select id="modelGameId" class=" rounded-2xl  bg-gray-50 border border-gray-300 text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-1.5">
                                                                <option selected>{{ __('Choose Game') }}</option>
                                                                @foreach($games_models as $k => $g )
                                                                    <option value="{{$k}}">{{$g}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div id="printRankingGame">
                                                            <div id="podiumGame" class="grid grid-cols-3 gap-4 mb-5">
                                                            
                                                            </div>
                                                            <div id="rankingTableGame" class="pr-4 pl-4">
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </article>
                                                @endif
                                                
                                                <!-- Panel #3 ... -->
                                            </div>
                                        </div>
                                    
                                    </div>
                                    <!-- End: Tabs component -->

                                </div>
                            </main>
                        </div>
                    @else
                        <p class="p-4">{{ __('There is no active ranking system, activate one in the Administration Panel.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @section('scripts')
    <script>
        const selectModelId = document.getElementById('modelGameId');
        const printRanging = document.getElementById('printRanking');
        const podiumGame = document.getElementById('podiumGame');
        const rankingTableGame = document.getElementById('rankingTableGame')
        selectModelId.addEventListener("change",function(event){
            axios.get('{{ route('get.ranking', ['tenant' => tenant('id')]) }}', {params:{modelId: event.target.value}}, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(function (response)
            {
                data = response.data.data;
                const table = data.filter((valor, indice) => indice >= 3);
                if(data.length > 0){
                    podiumGame.innerHTML = '';
                    if(typeof data[1] !== 'undefined'){
                        podiumGame.innerHTML +=
                            `<div class="top-user text-center">
                                @if (get_app_setting('second_place_icon_games'))
                                    <img src="{{ get_app_setting('second_place_icon_games') }}" alt="" class="mx-auto sm:max-w-32 max-w-24">
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 32 32" class="block m-auto"><path fill="currentColor" d="M6 30h20v-5a7.01 7.01 0 0 0-7-7h-6a7.01 7.01 0 0 0-7 7zM9 9a7 7 0 1 0 7-7a7 7 0 0 0-7 7"/></svg>
                                @endif
                                ${data[1].user}<br> {{ __('Time')}}: ${data[1].time} s
                            </div>`;
                    }
                    if(typeof data[0] !== 'undefined'){
                        podiumGame.innerHTML +=
                            `<div class="top-user text-center">
                                @if (get_app_setting('first_place_icon_games'))
                                    <img src="{{ get_app_setting('first_place_icon_games') }}" alt="" class="mx-auto sm:max-w-32 max-w-24">
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 32 32" class="block m-auto"><path fill="currentColor" d="M6 30h20v-5a7.01 7.01 0 0 0-7-7h-6a7.01 7.01 0 0 0-7 7zM9 9a7 7 0 1 0 7-7a7 7 0 0 0-7 7"/></svg>
                                @endif
                                ${data[0].user}<br> {{ __('Time')}}: ${data[0].time} s
                            </div>`;
                    }
                    if(typeof data[2] !== 'undefined'){
                        podiumGame.innerHTML +=
                            `<div class="top-user text-center">
                                @if (get_app_setting('third_place_icon_games'))
                                    <img src="{{ get_app_setting('third_place_icon_games') }}" alt="" class="mx-auto sm:max-w-32 max-w-24">
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 32 32" class="block m-auto"><path fill="currentColor" d="M6 30h20v-5a7.01 7.01 0 0 0-7-7h-6a7.01 7.01 0 0 0-7 7zM9 9a7 7 0 1 0 7-7a7 7 0 0 0-7 7"/></svg>
                                @endif
                                ${data[2].user}<br> {{ __('Time')}}: ${data[2].time} s
                            </div>`;
                    }
                    
                }
                if(table.length > 0){
                    rankingTableGame.innerHTML = '';
                    table.forEach((user,indx) => {
                        rankingTableGame.innerHTML += 
                        `<div class="p-1 bg-white top-ten flex mb-3 rounded-2xl shadow-sm" style="${user.email == user.session_email ? 'background:{{get_app_setting('ranking_color_1')}}; background: linear-gradient(135deg, {{get_app_setting('ranking_color_1')}} 0%, {{get_app_setting('ranking_color_2')}} 85%); color:white;' : ''}">
                            <div class="basis-5/6 p-2 border-r text-black ${user.email == user.session_email ? 'border-white' : ''} border-solid">${indx+4}° <strong>${user.user}</strong></div>
                            <div class="basis-1/6 p-2 text-center text-black">${user.time} s</div>
                        </div>`;
                        if(user.email == user.session_email){

                        }
                    });
                }
            })
            .catch(function (error)
            {
                //console.log(error);
            });

        })
    </script>
    @endsection
</x-app-layout>