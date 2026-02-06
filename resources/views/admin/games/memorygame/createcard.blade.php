@extends('layouts.v2.app')

<x-slot name="title">
    {{ $memory_card->id == null ? trans('Create') . ' ' . trans('Card') : $memory_card->name }}
</x-slot>
<x-slot name="description">
    {{ $memory_card->id == null ? '' : $memory_card->name }}
</x-slot>

<x-slot name="header">
    <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
    {{ $memory_card->id == null ? trans('Create') : trans('Edit') }} {{ __('Card') }}
    </h1>
</x-slot>

@if (session('status'))
    <div class="mx-5">
        <x-alert :status="session('status')" class="max-w-2xl mx-auto sm:px-6 lg:px-8 p-4 mt-4 text-sm rounded-lg" role="alert" />
    </div>
@endif

@section('content')
    <div class="py-6 mx-5">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 bg-white p-3 rounded shadow">
            <form method="POST" enctype="multipart/form-data"
            action="{{ $memory_card->id == null ? route('memory_cards.store', ['tenant' => tenant('id')]) : route('memory_cards.update', ['tenant' => tenant('id'), 'memory_card' => $memory_card]) }}">
            @csrf
            @isset($memory_card->id)
            @method('PATCH')
            <input type="hidden" name="id" value="{{ $memory_card->id }}">
            @endisset

            <input type="hidden" name="memory_quiz_id"
            value="{{ !is_null($memory_card->id) ? $memory_card->memory_quiz_id : $memory_quiz_id }}">

            <div class="my-5">
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                :value="old('name', $memory_card->name)" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="featured_image_help">
                    {{__('Use only lowercase.')}} <br>
                </div>
            </div>

            <div class="my-5">
                <x-input-label for="featured_image" :value="__('Featured Image')" />
                @if (!is_null($memory_card->id) && $memory_card->featured_image)
                    <img src="{{$memory_card->featured_image}}" alt="{{__('Featured Image')}}" title="{{__('Featured Image')}}" class="my-5">
                @endif
                <input
                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                aria-describedby="featured_image_help" id="featured_image" name="featured_image" type="file">
                <x-input-error class="mt-2" :messages="$errors->get('featured_image')" />
                <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="featured_image_help">
                    {{__('Image must be less than 2MB and JPG or PNG format.')}} <br>
                </div>
            </div>

            <div class="flex my-5">
                <button type="submit"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">{{ __('Save') }}</button>
                @if (!is_null($memory_card->id))
                    <a href="{{ route('memory_quizzes.edit', ['tenant' => tenant('id'), 'memory_quiz' => $memory_card->memory_quiz]) }}"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">{{ __('Back') }}</a>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection

