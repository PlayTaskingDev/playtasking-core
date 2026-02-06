<nav id="main-nav" x-data="{ open: false }" class="py-1">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Hamburger -->
            <div class="-mr-2 flex items-center lg:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-white dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('welcome', ['tenant' => tenant('id')]) }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-white dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 lg:flex">
                    <x-nav-link :href="route('panel.index', ['tenant' => tenant('id')])" :active="request()->routeIs('panel.index')">
                        {{ __('Admin Panel') }}
                    </x-nav-link>
                    <x-nav-link :href="route('pages.index', ['tenant' => tenant('id')])" :active="request()->routeIs('pages.index')">
                        {{ __('Pages') }}
                    </x-nav-link>
                    <button id="dropDownGamesLink" data-dropdown-toggle="dropDownGames" class="flex items-center justify-between w-full py-2 px-3 text-white text-sm rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-white md:p-0 md:w-auto dark:text-white md:dark:hover:text-blue-500 dark:focus:text-white dark:border-gray-700 dark:hover:bg-gray-700 md:dark:hover:bg-transparent">
                        {{__('Games')}} 
                        <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                        </svg>
                    </button>
                    <div id="dropDownGames" class="z-10 hidden font-normal divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600" style="background-color: {{get_app_setting('header_background_color')}};">
                        <ul class="py-2 text-sm" aria-labelledby="dropdownLargeButton">
                            <li>
                                <x-nav-link :href="route('content_type.index', ['tenant' => tenant('id')])" :active="request()->routeIs('content_type.index')" class="py-2">
                                    {{ __('Content types') }}
                                </x-nav-link>
                            </li>
                            <li>
                                <x-nav-link :href="route('panel.campaign.index', ['tenant' => tenant('id')])" :active="request()->routeIs('panel.campaign.index')" class="py-2">
                                    {{ __('Campaigns') }}
                                </x-nav-link>
                            </li>
                            <li>
                                <x-nav-link :href="route('quizzes.index', ['tenant' => tenant('id')])" :active="request()->routeIs('quizzes.index')" class="py-2">
                                    {{ __('Quizzes') }}
                                </x-nav-link>
                            </li>
                            <li>
                                <x-nav-link :href="route('memory_quizzes.index', ['tenant' => tenant('id')])" :active="request()->routeIs('memory_quizzes.index')" class="py-2">
                                    {{ __('Memory Quizzes') }}
                                </x-nav-link>
                            </li>
                            <li>
                                <x-nav-link :href="route('share_quizzes.index', ['tenant' => tenant('id')])" :active="request()->routeIs('share_quizzes.index')" class="py-2">
                                    {{ __('Share Quizzes') }}
                                </x-nav-link>
                            </li>
                            <li>
                                <x-nav-link :href="route('panel.vote_contest.index', ['tenant' => tenant('id')])" :active="request()->routeIs('panel.vote_contest.index')" class="py-2">
                                    {{ __('Vote Contests') }}
                                </x-nav-link>
                            </li>
                            <li>
                                <x-nav-link :href="route('click_wins.index', ['tenant' => tenant('id')])" :active="request()->routeIs('click_wins.index')" class="py-2">
                                    {{ __('Click and Win') }}
                                </x-nav-link>
                            </li>
                            <li>
                                <x-nav-link :href="route('aplazo_games.index', ['tenant' => tenant('id')])" :active="request()->routeIs('aplazo_games.index')" class="py-2">
                                    {{ __('Aplazo games') }}
                                </x-nav-link>
                            </li>
                            <li>
                                <x-nav-link :href="route('puzzles.index', ['tenant' => tenant('id')])" :active="request()->routeIs('puzzles.index')" class="py-2">
                                    {{ __('Puzzles') }}
                                </x-nav-link>
                            </li>
                            <li>
                                <x-nav-link :href="route('catch_games.index', ['tenant' => tenant('id')])" :active="request()->routeIs('catch_games.index')" class="py-2">
                                    {{ __('Catch Games') }}
                                </x-nav-link>
                            </li>
                            <li>
                                <x-nav-link :href="route('smash_games.index', ['tenant' => tenant('id')])" :active="request()->routeIs('smash_games.index')" class="py-2">
                                    {{ __('Smash Games') }}
                                </x-nav-link>
                            </li>
                            <li>
                                <x-nav-link :href="route('code.index', ['tenant' => tenant('id')])" :active="request()->routeIs('code.index')" class="py-2">
                                    {{ __('Code Hunter') }}
                                </x-nav-link>
                            </li>
                            <li>
                                <x-nav-link :href="route('awards.codes.index', ['tenant' => tenant('id')])" :active="request()->routeIs('awards.codes.index')" class="py-2">
                                    {{ __('Award Codes') }}
                                </x-nav-link>
                            </li>
                            
                        </ul>
                    </div>

                    <button id="dropDownTicketsLink" data-dropdown-toggle="dropDownTickets" class="flex items-center justify-between w-full py-2 px-3 text-white text-sm rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-white md:p-0 md:w-auto dark:text-white md:dark:hover:text-blue-500 dark:focus:text-white dark:border-gray-700 dark:hover:bg-gray-700 md:dark:hover:bg-transparent">
                        {{__('Tickets')}} 
                        <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                        </svg>
                    </button>
                    <div id="dropDownTickets" class="z-10 hidden font-normal divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600" style="background-color: {{get_app_setting('header_background_color')}};">
                        <ul class="py-2 text-sm" aria-labelledby="dropdownLargeButton">
                            <li>
                                <x-nav-link :href="route('ticketQuestion.index', ['tenant' => tenant('id')])" :active="request()->routeIs('ticket.questions.index')">
                                    {{ __('Ticket Questions') }}
                                </x-nav-link>
                            </li>
                            <li>
                                <x-nav-link :href="route('panel.tickets.index', ['tenant' => tenant('id')])" :active="request()->routeIs('panel.tickets.index')">
                                    {{ __('Ticket Submissions') }}
                                </x-nav-link>
                            </li>
                        </ul>
                    </div>
                    
                    <x-nav-link :href="route('media_elements.index', ['tenant' => tenant('id')])" :active="request()->routeIs('media_elements.index')">
                        {{ __('Media elements') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('panel.statistics', ['tenant' => tenant('id')])" :active="request()->routeIs('panel.statistics')">
                        {{ __('Statistics') }}
                    </x-nav-link>
                </div>
            </div>

            @auth
                <!-- Settings Dropdown -->
                <div class="hidden lg:flex sm:items-center sm:ml-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-black dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit', ['tenant' => tenant('id')])">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout', ['tenant' => tenant('id')]) }}">
                                @csrf

                                <x-dropdown-link :href="route('logout', ['tenant' => tenant('id')])"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>

                            </form>
                            <x-dropdown-link :href="route('campaign.splash', ['tenant' => tenant('id')])">
                                {{ __('Dashboard') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                </div>
                <!-- Responsive Settings Options -->
                <div class="flex lg:hidden">
                    <div class="flex mt-3 space-y-1">
                        <x-responsive-nav-link :href="route('profile.edit', ['tenant' => tenant('id')])" title="{{ Auth::user()->name }}">
                            @if (Auth::user()->avatar)
                            <div class="w-12 h-12 bg-no-repeat bg-center bg-cover rounded-full -mt-2" style="background-image: url({{Auth::user()->avatar}});"></div>
                            @else
                            <img src="{{ Vite::asset('resources/images/no-foto.png') }}"
                            alt="{{ Auth::user()->name }}" title="{{ Auth::user()->name }}" class="max-h-12 -mt-2">
                            @endif
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('profile.edit', ['tenant' => tenant('id')])">
                            {{ __('Profile') }}
                        </x-responsive-nav-link>
                    </div>
                </div>
            @endauth
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden lg:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('panel.index', ['tenant' => tenant('id')])" :active="request()->routeIs('panel.index')">
                {{ __('Admin Panel') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('v2.index', ['tenant' => tenant('id')])" :active="request()->routeIs('v2.index')">
                {{ __('Admin Panel V2') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('pages.index', ['tenant' => tenant('id')])" :active="request()->routeIs('pages.index')" class="flex items-center">
                {{ __('Pages') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('content_type.index', ['tenant' => tenant('id')])" :active="request()->routeIs('content_type.index')" class="flex items-center">
                {{ __('Content types') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('panel.campaign.index', ['tenant' => tenant('id')])" :active="request()->routeIs('panel.campaign.index')" class="flex items-center">
                {{ __('Campaigns') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('quizzes.index', ['tenant' => tenant('id')])" :active="request()->routeIs('quizzes.index')" class="flex items-center">
                {{ __('Quizzes') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('memory_quizzes.index', ['tenant' => tenant('id')])" :active="request()->routeIs('memory_quizzes.index')" class="flex items-center">
                {{ __('Memory Quizzes') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('share_quizzes.index', ['tenant' => tenant('id')])" :active="request()->routeIs('share_quizzes.index')" class="flex items-center">
                {{ __('Share Quizzes') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('panel.vote_contest.index', ['tenant' => tenant('id')])" :active="request()->routeIs('panel.vote_contest.index')" class="flex items-center">
                {{ __('Vote Contests') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('click_wins.index', ['tenant' => tenant('id')])" :active="request()->routeIs('click_wins.index')" class="flex items-center">
                {{ __('Click and Win') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('aplazo_games.index', ['tenant' => tenant('id')])" :active="request()->routeIs('aplazo_games.index')" class="flex items-center">
                {{ __('Aplazo games') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('puzzles.index', ['tenant' => tenant('id')])" :active="request()->routeIs('puzzles.index')" class="flex items-center">
                {{ __('Puzzles') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('catch_games.index', ['tenant' => tenant('id')])" :active="request()->routeIs('catch_games.index')" class="flex items-center">
                {{ __('Catch Games') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('smash_games.index', ['tenant' => tenant('id')])" :active="request()->routeIs('smash_games.index')" class="flex items-center">
                {{ __('Smash Games') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('code.index', ['tenant' => tenant('id')])" :active="request()->routeIs('code.index')" class="flex items-center">
                {{ __('Coupons') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('media_elements.index', ['tenant' => tenant('id')])" :active="request()->routeIs('media_elements.index')" class="flex items-center">
                {{ __('Media elements') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('awards.codes.index', ['tenant' => tenant('id')])" :active="request()->routeIs('awards.codes.index')" class="flex items-center">
                {{ __('Codes') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('ticketQuestion.index', ['tenant' => tenant('id')])" :active="request()->routeIs('ticketQuestion.index')" class="flex items-center">
                {{ __('Ticket Questions') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('panel.statistics', ['tenant' => tenant('id')])" :active="request()->routeIs('panel.statistics')" class="flex items-center">
                {{ __('Statistics') }}
            </x-responsive-nav-link>
            <!-- Authentication -->
            <form method="POST" action="{{ route('logout', ['tenant' => tenant('id')]) }}">
                @csrf

                <x-responsive-nav-link :href="route('logout', ['tenant' => tenant('id')])"
                    onclick="event.preventDefault();
                                this.closest('form').submit();"
                    class="flex items-center">
                    {{-- <img src="{{ asset(tenant('id') . '/storage/assets/images/logout.png') }}" alt="{{ __('Log Out') }}"
                        title="{{ __('Log Out') }}" class="w-8 h-8 mr-2"> --}}
                    {{ __('Log Out') }}
                </x-responsive-nav-link>
            </form>
            <x-responsive-nav-link :href="route('campaign.splash', ['tenant' => tenant('id')])" class="flex items-center">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>
    </div>
</nav>
