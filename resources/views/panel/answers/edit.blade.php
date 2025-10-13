<x-panel-layout>
    <x-slot name="title">
        {{ $answer->id == null ? trans('Create') . ' ' . trans('Answer') : $answer->title }}
    </x-slot>
    <x-slot name="description">
        {{ $answer->id == null ? '' : $answer->description }}
    </x-slot>

    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $answer->id == null ? trans('Create') : trans('Edit') }} {{ __('Answer') }}
        </h1>
    </x-slot>

    @if (session('status'))
        <div class="mx-5">
            <x-alert :status="session('status')" class="max-w-2xl mx-auto sm:px-6 lg:px-8 p-4 mt-4 text-sm rounded-lg" role="alert" />
        </div>
    @endif

    <div class="py-6 mx-5">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 bg-white p-3 rounded shadow">
            <form method="POST" enctype="multipart/form-data"
                action="{{ $answer->id == null ? route('answers.store', ['tenant' => tenant('id')]) : route('answers.update', ['tenant' => tenant('id'), 'answer' => $answer]) }}">
                @csrf
                @if (!is_null($answer->id))
                    @method('PATCH')
                    <input type="hidden" name="id" value="{{ $answer->id }}">
                @endif

                <input type="hidden" name="question_id"
                    value="{{ !is_null($answer->id) ? $answer->question_id : $question_id }}">

                <div class="my-5">
                    <x-input-label for="title" :value="__('Title')" />
                    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title"
                        :value="old('title', $answer->title)" required autofocus autocomplete="title" />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <div class="my-5 flex items-center">
                    <input id="is_correct" name="is_correct" type="checkbox" value="1" {{$answer->is_correct ? 'checked' : ''}}
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="is_correct"
                        class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('Is correct answer') }}</label>
                    <x-input-error :messages="$errors->get('is_correct')" class="mt-2" />
                </div>

                <div class="my-5">
                    <x-input-label for="featured_image" :value="__('Featured Image')" />
                    @if (!is_null($answer->id) && $answer->featured_image)
                        <img src="{{$answer->featured_image}}" alt="{{__('Answer Featured Image')}}" title="{{__('Answer Featured Image')}}" class="my-5">
                    @endif
                    <input
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                        aria-describedby="featured_image_help" id="featured_image" name="featured_image" type="file">
                    <x-input-error class="mt-2" :messages="$errors->get('featured_image')" />
                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="featured_image_help">
                        {{__('Image must be less than 2MB and JPG or PNG format.')}}
                    </div>
                </div>

                <div class="flex my-5">
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">{{ __('Save') }}</button>
                    @if (!is_null($answer->id))
                        <a href="{{ route('questions.edit', ['tenant' => tenant('id'), 'question' => $answer->question]) }}"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">{{ __('Back to') }}
                            {{ __('Question') }}</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</x-panel-layout>
