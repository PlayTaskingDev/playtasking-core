<x-panel-layout>
    <x-slot name="title">
        {{ $page->title }}
    </x-slot>
    <x-slot name="description">
        {{ $page->description }}
    </x-slot>

    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $page->id == null ? trans('Create') : trans('Edit') }} {{ __('Page') }}
        </h1>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 bg-white p-3 rounded shadow">
            <form method="POST" enctype="multipart/form-data" action="{{ $page->id == null ? route('pages.store', ['tenant' => tenant('id')]) : route('pages.update', ['tenant' => tenant('id'), 'page' => $page]) }}">
                @csrf
                @isset($page->id)
                    @method('PATCH')
                    <input type="hidden" name="id" value="{{ $page->id }}">
                @endisset

                <div class="my-5">
                    <x-input-label for="title" :value="__('Title')" />
                    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title"
                        :value="old('title', $page->title)" required autofocus autocomplete="title" />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <div class="my-5">
                    <x-input-label for="icon" :value="__('Icon Image')" />
                    @if (!is_null($page->id) && $page->icon)
                    <div class="bg-gray-300">
                        <img src="{{$page->icon}}" alt="{{__('Icon image')}}" title="{{__('Icon image')}}" class="my-5">
                    </div>
                    @endif
                    <input
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                        aria-describedby="icon_help" id="icon" name="icon" type="file">
                    <x-input-error class="mt-2" :messages="$errors->get('icon')" />
                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="icon_help">
                        {{__('Image must be less than 2MB and JPG or PNG format.')}} <br>
                    </div>
                </div>

                <div class="my-5">
                    <x-input-label for="description" :value="__('Description')" />
                    <x-text-input id="description" class="block mt-1 w-full" type="text" name="description"
                        :value="old('description', $page->description)" required autofocus autocomplete="description" />
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="my-5">
                    <x-input-label for="slug" :value="__('Slug')" />
                    <x-text-input id="slug" class="block mt-1 w-full" type="text" name="slug"
                        :value="old('slug', $page->slug)" required autofocus autocomplete="slug" />
                    <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                </div>

                <div class="my-5 flex items-center">
                    <input id="active" name="active" type="checkbox" value="1" {{$page->active ? 'checked' : ''}}
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="active"
                        class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('Is active') }}</label>
                    <x-input-error :messages="$errors->get('active')" class="mt-2" />
                </div>

                <div class="my-5">
                    <x-input-label for="content" :value="__('Content')" />
                    <textarea id="content" name="content" rows="10"
                        class="tinymce-component block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{old('content', $page->content)}}</textarea>
                    <x-input-error :messages="$errors->get('content')" class="mt-2" />
                </div>

                <div class="my-5">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>

    <x-footer.tinymce-config/>
</x-panel-layout>
