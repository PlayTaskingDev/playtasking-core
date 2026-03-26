<x-guest-layout>
    <x-slot name="title">
        {{ $title }}
    </x-slot>
    <x-slot name="description">
        {{ $description }}
    </x-slot>
    <x-slot name="classes">
        {{ $classes }}
    </x-slot>
        
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $title }}
        </h1>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden p-3 shadow-sm rounded-lg">
                <div class="p-5">
                    @if (session('success'))
                    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                        {{session('success')}}
                      </div>
                    @endif
                    
                    <div class="p-4 rounded text-white mb-3 font-bold" style="background-color:{{get_app_setting('primary_button_background')}}">
                        {{__('Total votes')}}: {{$asset->points}}
                    </div>
                    <div class="relative">
                        {{-- <div class="absolute inline-flex items-center justify-center w-20 h-20 text-md font-bold border-2 border-white rounded-full -top-4 -end-4 dark:border-gray-900 z-50" style="background-color:{{get_app_setting('primary_button_background')}} ; color:{{get_app_setting('primary_button_color')}} ;">{{$asset->points}}</div> --}}
                        @if ($asset->vote_contest->asset_type == 'photo')
                            <img src="{{$asset->asset_url}}" alt="{{$asset->title}}" title="{{$asset->title}}" class="h-auto w-full rounded">
                        @else
                            <div class="w-full h-auto max-w-full">
                                <div style="padding:56.25% 0 0 0;position:relative;">
                                    <iframe src="{{$asset->iframe_video_url}}" frameborder="0" allow="autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media" style="position:absolute;top:0;left:0;width:100%;height:100%;" title="My puss in boots"></iframe>
                                </div>
                                <script src="https://player.vimeo.com/api/player.js" async></script>
                            </div>
                        @endif
                    </div>
                    <div class="p-5 mt-3 rounded text-center" style="background-color: {{get_app_setting('primary_button_background')}}; color: {{get_app_setting('primary_button_color')}};">
                        {{ $asset->vote_contest->description }}
                    </div>
                    <div class="flex items-center justify-center mt-4">
                        <x-primary-button class="ml-4" type="button" id="copyAssetUrl">
                            {{ __('Copy link') }}
                        </x-primary-button>
                    </div>
                    <p class="mt-3 text-xs text-center">
                        {{__('If the button above is not working, you can copy and paste the next URL:')}} <br>
                        {{url()->current()}}
                    </p>
                    
                    @if(is_null(auth()->user()) || auth()->user()->id != $asset->user_id)
                    <div class="flex items-center justify-center mt-4">
                        <x-primary-button class="ml-4" type="button" data-modal-target="vote-modal" data-modal-toggle="vote-modal">
                            {{ __('Vote now!') }}
                        </x-primary-button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(is_null(auth()->user()) || auth()->user()->id != $asset->user_id)
    <div id="vote-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        {{$asset->title}}
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="vote-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4">
                    <form method="POST" action="{{route('asset.vote', ['tenant' => tenant('id'), 'asset' => $asset])}}">
                        @csrf
                        <input type="hidden" name="id" value="{{$asset->id}}">
                        <div class="my-3">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full text-black" type="email" name="email"
                                :value="old('email')" required autofocus autocomplete="email" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <div>
                            {!! RecaptchaV3::field('vote') !!}
                            <x-input-error :messages="$errors->get('g-recaptcha-response')" class="mt-2" />
                        </div>
                        <div class="mt-6">
                            <x-primary-button class="mx-auto block">
                                {{ __('Vote') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    @section('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const buttonCopy = document.getElementById('copyAssetUrl');
                buttonCopy.addEventListener('click', function(e){
                    navigator.clipboard.writeText('{{url()->current()}}');
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
</x-guest-layout>
