<x-panel-layout>
    <x-slot name="title">
        {{ !is_null($media_element->title) ? $media_element->title : trans('Create') . ' ' . trans('Media element') }}
    </x-slot>
    <x-slot name="description">
        {{ $media_element->id == null ? '' : $media_element->description }}
    </x-slot>

    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $media_element->id == null ? trans('Create') : trans('Edit') }} {{ __('Media element') }}
        </h1>
    </x-slot>

    <div class="py-6 mx-5">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 bg-white p-3 rounded shadow">
            <form method="POST" enctype="multipart/form-data"
                action="{{ $media_element->id == null ? route('media_elements.store', ['tenant' => tenant('id')]) : route('media_elements.update', ['tenant' => tenant('id'), 'media_element' => $media_element]) }}">
                @csrf
                @isset($media_element->id)
                    @method('PATCH')
                    <input type="hidden" name="id" value="{{ $media_element->id }}">
                @endisset

                <div class="my-5">
                    <x-input-label for="description" :value="__('Description')" />
                    <x-text-input id="description" class="block mt-1 w-full" type="text" name="description"
                        :value="old('description', $media_element->description)" required autofocus autocomplete="description" />
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="my-5">
                    <x-input-label for="asset" :value="__('Asset')" />
                    @if (!is_null($media_element->id))
                        <img src="{{$media_element->asset}}" alt="{{__('Asset')}}" title="{{__('Asset')}}" class="my-5">
                    @endif
                    <input
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                        aria-describedby="asset_help" id="asset" name="asset" type="file">
                    <x-input-error class="mt-2" :messages="$errors->get('asset')" />
                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="asset_help">
                        {{__('Image must be less than 2MB and JPG or PNG format.')}}<br>
                        {{__('Dimensions must be')}} 300 x 500
                    </div>
                </div>

                <div class="my-5">
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>

</x-panel-layout>
