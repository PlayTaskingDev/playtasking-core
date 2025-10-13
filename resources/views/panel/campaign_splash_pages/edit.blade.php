<x-panel-layout>
    <x-slot name="title">
        {{ !is_null($campaign_splash_page->title) ? $campaign_splash_page->title : trans('Create') . ' ' . trans('Welcome Page') }}
    </x-slot>
    <x-slot name="description">
        {{ $campaign_splash_page->id == null ? '' : $campaign_splash_page->description }}
    </x-slot>

    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $campaign_splash_page->id == null ? trans('Create') : trans('Edit') }} {{ __('Welcome Page') }}
        </h1>
    </x-slot>

    <div class="py-6 mx-5">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 bg-white p-3 rounded shadow">
            @if (session('status'))
                <x-alert :status="session('status')" class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-4 my-4 text-sm rounded-lg"
                    role="alert" />
            @endif
            <form method="POST" enctype="multipart/form-data"
                action="{{ $campaign_splash_page->id == null ? route('campaign_splash_page.store', ['tenant' => tenant('id')]) : route('campaign_splash_page.update', ['tenant' => tenant('id'), 'campaign_splash_page' => $campaign_splash_page]) }}">
                @csrf
                @isset($campaign_splash_page->id)
                    @method('PATCH')
                    <input type="hidden" name="id" value="{{ $campaign_splash_page->id }}">
                @endisset

                <input type="hidden" name="campaign_id" value="{{ !is_null($campaign_splash_page->id) ? $campaign_splash_page->campaign_id : $campaign_id }}">

                <div class="my-5">
                    <x-input-label for="instructions" :value="__('Instructions')" />
                    <textarea id="instructions" name="instructions" rows="10"
                        class="tinymce-component block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{old('instructions', $campaign_splash_page->instructions)}}</textarea>
                    <x-input-error :messages="$errors->get('instructions')" class="mt-2" />
                </div>

                <div class="my-5">
                    <x-input-label for="featured_image_url" :value="__('Image')" />
                    @if (!is_null($campaign_splash_page->id) && $campaign_splash_page->featured_image_url)
                        <div id="delete_image_holder" class="relative">
                            <img src="{{$campaign_splash_page->featured_image_url}}" alt="{{__('Welcome Featured Image')}}" title="{{__('Welcome Featured Image')}}" class="my-5 w-full">
                            <x-delete-image :element="'delete_image_holder'"></x-delete-image>
                        </div>
                        <input type="hidden" id="delete_image_holder_hidden" name="delete_image_holder_hidden" value="0">
                    @endif
                    <input
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                        aria-describedby="featured_image_url_help" id="featured_image_url" name="featured_image_url" type="file">
                    <x-input-error class="mt-2" :messages="$errors->get('featured_image_url')" />
                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="featured_image_url_help">
                        {{__('Image must be less than 2MB and JPG or PNG format.')}}<br>
                        {{__('Dimensions must be')}} 500 x 300
                    </div>
                </div>

                <div class="my-5">
                    <x-input-label for="featured_video_url" :value="__('Video')" />
                    @if (!is_null($campaign_splash_page->id) && $campaign_splash_page->featured_video_url)
                    <div class="aspect-w-16 aspect-h-9 mb-6">
                        <iframe src="{{$campaign_splash_page->featured_video_url}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    @endif
                    <x-text-input id="featured_video_url" class="block mt-1 w-full" type="text" name="featured_video_url"
                        :value="old('featured_video_url', $campaign_splash_page->featured_video_url)" autofocus autocomplete="featured_video_url" />
                    <x-input-error :messages="$errors->get('featured_video_url')" class="mt-2" />
                </div>

                <div class="my-5 flex">
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">{{ __('Save') }}</button>
                    @if (!is_null($campaign_splash_page->id))
                        <a href="{{ route('panel.campaign.edit', ['tenant' => tenant('id'), 'campaign' => $campaign_splash_page->campaign_id]) }}"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">{{ __('Back to') }}
                            {{ __('Campaign') }}</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <x-footer.tinymce-config/>
</x-panel-layout>
