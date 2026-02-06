<nav id="main-nav" x-data="{ open: false }" class="py-1">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Hamburger -->
            <div class="flex xl:hidden! -mr-2 items-center">
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
                <div class="relative md:absolute  right-3 h-20 inset-y-0">
                    <a href="{{ Auth::user() ? route('campaign.splash', ['tenant' => tenant('id')]) : route('welcome', ['tenant' => tenant('id')]) }}">
                        <x-application-logo class="h-9 w-auto fill-current text-white dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex! space-x-8 sm:-my-px sm:ml-10">
                    @auth
                        <x-nav-link :href="route('campaign.splash', ['tenant' => tenant('id')])" :active="request()->routeIs('campaign.splash')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                    @endauth
                    @foreach ($pages as $page)
                        <x-nav-link :href="route('page.show', ['tenant' => tenant('id'), 'slug' => $page->slug])" :active="request()->is(tenant('id') . '/' . $page->slug)">
                            {{ $page->title }}
                        </x-nav-link>
                    @endforeach
                </div>
            </div>

            @auth
                <!-- Settings Dropdown -->
                <div class="hidden md:flex! sm:items-center sm:ml-6 ">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent leading-4 font-medium rounded-md text-black dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
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
                            @role('admin')
                                <x-dropdown-link :href="route('panel.index', ['tenant' => tenant('id')])">
                                    {{ __('Admin Panel') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('v2.index', ['tenant' => tenant('id')])">
                                    {{ __('Admin Panel V2') }}
                                </x-dropdown-link>
                            @endrole
                        </x-slot>
                    </x-dropdown>
                </div>
                <!-- Responsive Settings Options -->
                <div class="flex lg:hidden">
                    <div class="flex mt-3 space-y-1">
                        
                        
                    </div>
                </div>
            @endauth
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden lg:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                <x-responsive-nav-link :href="route('profile.edit', ['tenant' => tenant('id')])" title="{{ Auth::user()->name }}" class="text-center">
                    @if (Auth::user()->avatar)
                        <div class="w-32 h-32 bg-no-repeat bg-center bg-cover rounded-full -mt-2 mx-auto mb-2"
                            style="background-image: url({{ Auth::user()->avatar }});"></div>
                    @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" viewBox="0 0 256 256" class="mx-auto"><path fill="currentColor" d="M128 26a102 102 0 1 0 102 102A102.12 102.12 0 0 0 128 26M71.44 198a66 66 0 0 1 113.12 0a89.8 89.8 0 0 1-113.12 0M94 120a34 34 0 1 1 34 34a34 34 0 0 1-34-34m99.51 69.64a77.53 77.53 0 0 0-40-31.38a46 46 0 1 0-51 0a77.53 77.53 0 0 0-40 31.38a90 90 0 1 1 131 0"/></svg>
                    @endif
                    {{ Auth::user()->name }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('campaign.splash', ['tenant' => tenant('id')])" :active="request()->routeIs('campaign.splash')" class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" class="mr-2" viewBox="0 0 32 32"><path fill="currentColor" d="M16.612 2.214a1.01 1.01 0 0 0-1.242 0L1 13.419l1.243 1.572L4 13.621V26a2.004 2.004 0 0 0 2 2h20a2.004 2.004 0 0 0 2-2V13.63L29.757 15L31 13.428ZM18 26h-4v-8h4Zm2 0v-8a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v8H6V12.062l10-7.79l10 7.8V26Z"/></svg>
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('profile.edit', ['tenant' => tenant('id')])" class="flex items-center" :active="request()->routeIs('profile.edit')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" class="mr-2" viewBox="0 0 256 256"><path fill="currentColor" d="M128 26a102 102 0 1 0 102 102A102.12 102.12 0 0 0 128 26M71.44 198a66 66 0 0 1 113.12 0a89.8 89.8 0 0 1-113.12 0M94 120a34 34 0 1 1 34 34a34 34 0 0 1-34-34m99.51 69.64a77.53 77.53 0 0 0-40-31.38a46 46 0 1 0-51 0a77.53 77.53 0 0 0-40 31.38a90 90 0 1 1 131 0"/></svg>
                    {{ __('View profile') }}
                </x-responsive-nav-link>
            @endauth
            
            @foreach ($pages as $page)
                <x-responsive-nav-link :href="route('page.show', ['tenant' => tenant('id'), 'slug' => $page->slug])" :active="request()->is(tenant('id') . '/' . $page->slug)" class="flex items-center">
                    <img src="{{ $page->icon }}" alt="{{ $page->title }}"
                        title="{{ $page->title }}" class="w-8 h-8 mr-2">
                    {{ $page->title }}
                </x-responsive-nav-link>
            @endforeach
            
            @auth
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout', ['tenant' => tenant('id')]) }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout', ['tenant' => tenant('id')])"
                        onclick="event.preventDefault();
                                this.closest('form').submit();"
                        class="flex items-center">
                        <img src="{{ global_asset('/storage/dummy_assets/logout.png') }}" alt="{{ __('Log Out') }}"
                            title="{{ __('Log Out') }}" class="w-8 h-8 mr-2">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            @endauth

            @role('admin')
                <x-responsive-nav-link :href="route('panel.index', ['tenant' => tenant('id')])">
                    {{ __('Admin Panel') }}
                </x-responsive-nav-link>
                <x-dropdown-link :href="route('v2.index', ['tenant' => tenant('id')])">
                    {{ __('Admin Panel V2') }}
                </x-dropdown-link>
            @endrole
        </div>


    </div>
</nav>
