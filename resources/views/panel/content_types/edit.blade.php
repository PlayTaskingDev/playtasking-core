<x-panel-layout>
    <x-slot name="title">
        {{ !is_null($contentType->title) ? $contentType->title : trans('Create') . ' ' . trans('Content types') }}
    </x-slot>
    <x-slot name="description">
        {{ $contentType->id == null ? '' : $contentType->description }}
    </x-slot>

    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $contentType->id == null ? trans('Create') : trans('Edit') }} {{ __('Content types') }}
        </h1>
    </x-slot>

    <div class="py-6 mx-5">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 bg-white p-3 rounded shadow">
            <form method="POST" enctype="multipart/form-data"
                action="{{ $contentType->id == null ? route('content_type.store', ['tenant' => tenant('id')]) : route('content_type.update', ['tenant' => tenant('id'), 'content_type' => $contentType]) }}">
                @csrf
                @isset($contentType->id)
                    @method('PATCH')
                    <input type="hidden" name="id" value="{{ $contentType->id }}">
                @endisset

                <div class="my-5">
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                        :value="old('name', $contentType->name)" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="my-5">
                    <x-input-label for="description" :value="__('Description')" />
                    <x-text-input id="description" class="block mt-1 w-full" type="text" name="description"
                        :value="old('description', $contentType->description)" required autofocus autocomplete="description" />
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="my-5 grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="icon" :value="__('Menu icon')" />
                        <div class="img-app-preview my-5 w-full h-72 bg-gray-300" style="background-image: url({{$contentType->icon}})"></div>
                        <input
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                            aria-describedby="icon_help" id="icon" name="icon" type="file">
                        <x-input-error class="mt-2" :messages="$errors->get('icon')" />
                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="icon_help">
                            {{__('Image must be less than 2MB and JPG or PNG format.')}} <br>
                        </div>
                    </div>
                    <div>
                        <x-input-label for="icon_active" :value="__('Menu icon (active)')" />
                        <div class="img-app-preview my-5 w-full h-72" style="background-image: url({{$contentType->icon_active}})"></div>
                        <input
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                            aria-describedby="icon_active_help" id="icon_active" name="icon_active" type="file">
                        <x-input-error class="mt-2" :messages="$errors->get('icon_active')" />
                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="icon_active_help">
                            {{__('Image must be less than 2MB and JPG or PNG format.')}} <br>
                        </div>
                    </div>
                </div>

                <div class="my-5 grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="gradient_1" :value="__('Gradient Background 1')" />
                        <x-text-input id="gradient_1" class="block mt-1 w-full" type="text" name="gradient_1"
                            :value="old('gradient_1', $contentType->gradient_1)" required autofocus autocomplete="gradient_1" />
                        <x-input-error :messages="$errors->get('gradient_1')" class="mt-2" />
                        <div id="gradient1"></div>
                        <div id="piker-viewer-1" class="block mt-1 w-full h-10 rounded"></div>
                    </div>
                    <div>
                        <x-input-label for="gradient_2" :value="__('Gradient Background 2')" />
                        <x-text-input id="gradient_2" class="block mt-1 w-full" type="text" name="gradient_2"
                            :value="old('gradient_2', $contentType->gradient_2)" required autofocus autocomplete="gradient_2" />
                        <x-input-error :messages="$errors->get('gradient_2')" class="mt-2" />
                        <div id="gradient2"></div>
                        <div id="piker-viewer-2" class="block mt-1 w-full h-10 rounded"></div>
                    </div>
                </div>

                <div class="my-5">
                    <x-input-label for="section_banner" :value="__('Section banner')" />
                    @if ($contentType->section_banner)
                        <div id="delete_image_holder" class="relative">
                            <img src="{{$contentType->section_banner}}" alt="{{__('Banner Image')}}" title="{{__('Banner Image')}}" class="my-5 w-full">
                            <x-delete-image :element="'delete_image_holder'"></x-delete-image>
                        </div>
                        <input type="hidden" id="delete_image_holder_hidden" name="delete_image_holder_hidden" value="0">
                    @endif
                    <input
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                        aria-describedby="section_banner_help" id="section_banner" name="section_banner" type="file">
                    <x-input-error class="mt-2" :messages="$errors->get('section_banner')" />
                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="section_banner_help">
                        {{__('Image must be less than 2MB and JPG or PNG format.')}}<br>
                        {{__('Dimensions must be')}} 500 x 300
                    </div>
                </div>
                <div class="my-5">
                    <x-input-label for="game_banner_url" :value="__('Banner URL (Image)')" />
                    <x-text-input id="game_banner_url" class="block mt-1 w-full" type="text" name="game_banner_url"
                        :value="old('game_banner_url', $contentType->game_banner_url)" autofocus autocomplete="game_banner_url" />
                    <x-input-error :messages="$errors->get('game_banner_url')" class="mt-2" />
                </div>
                <div class="my-5">
                    <x-input-label for="game_banner_video" :value="__('Video')" />
                    @if (!is_null($contentType->id) && $contentType->game_banner_video)
                    <div class="aspect-w-16 aspect-h-9 mb-6">
                        <iframe src="{{$contentType->game_banner_video}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    @endif
                    <x-text-input id="game_banner_video" class="block mt-1 w-full" type="text" name="game_banner_video"
                        :value="old('game_banner_video', $contentType->game_banner_video)" autofocus autocomplete="game_banner_video" />
                    <x-input-error :messages="$errors->get('game_banner_video')" class="mt-2" />
                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="game_banner_help">
                        {{__('If a video URL is set, it has precedence over the image.')}}<br>
                        {{ __('Use an embed URL as: https://www.youtube.com/embed/B-M3YlA2KDg') }}
                    </div>
                </div>

                <div class="my-5">
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>

    @section('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const gradient1 = new ColorPicker({
                    color: '{{$contentType->gradient_1}}',
                    background: '#fff',
                    el: document.getElementById('gradient1'),
                    width: 250,
                    height: 150,
                });
    
                gradient1.onChange(function(){
                    currentColor = gradient1.getHexString();
                    let inputColor = document.getElementById('gradient_1');
                    inputColor.value = currentColor;
    
                    let pickerViewer = document.getElementById('piker-viewer-1');
                    pickerViewer.style.backgroundColor = currentColor;
                });

                const gradient2 = new ColorPicker({
                    color: '{{$contentType->gradient_2}}',
                    background: '#fff',
                    el: document.getElementById('gradient2'),
                    width: 250,
                    height: 150,
                });
    
                gradient2.onChange(function(){
                    currentColor = gradient2.getHexString();
                    let inputColor = document.getElementById('gradient_2');
                    inputColor.value = currentColor;
    
                    let pickerViewer = document.getElementById('piker-viewer-2');
                    pickerViewer.style.backgroundColor = currentColor;
                });
            });
        </script>
    @endsection
</x-panel-layout>
