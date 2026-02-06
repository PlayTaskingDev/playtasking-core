@extends('layouts.v2.app')
<x-slot name="title">
    {{ $award->id == null ? trans('Create') . ' ' . trans('Award') : $award->title }}
</x-slot>
<x-slot name="description">
    {{ $award->id == null ? '' : $award->description }}
</x-slot>

<x-slot name="header">
    <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
    {{ $award->id == null ? trans('Create') : trans('Edit') }} {{ __('Award') }}
    </h1>
</x-slot>
@section('content')

    <x-v2.common.page-breadcrumb pageTitle="{{ $award->id == null ? trans('Create') : trans('Edit') }} {{ __('Award') }}" />
    <div class="space-y-6">
        @if (session('status'))
            <x-v2.ui.alert
            variant="success"
            title="{{ session('status') }}"
            :showLink="false"
            />
        @endif
        <x-v2.common.component-card title="{{ $award->id == null ? trans('Create') : trans('Edit') }} {{ __('Award') }}">
        <form method="POST"
        action="{{ $award->id == null ? route('v2awards.store', ['tenant' => tenant('id')]) : route('v2awards.update', ['tenant' => tenant('id'), 'v2award' => $award]) }}">
        @csrf
        @if (!is_null($award->id))
            @method('PATCH')
            <input type="hidden" name="id" value="{{ $award->id }}">
        @endif

        <input type="hidden" name="awardable_id"
        value="{{ !is_null($award->id) ? $award->awardable_id : $awardable_id }}">

        <input type="hidden" name="awardable_type"
        value="{{ !is_null($award->id) ? $award->awardable_type : $awardable_type }}">

        <div class="my-5">
            <x-input-label for="title" :value="__('Title')" />
            <textarea id="title" name="title" rows="10"
            class="tinymce-component block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{old('title', $award->title)}}</textarea>
            <x-input-error :messages="$errors->get('title')" class="mt-2" />
        </div>

        <div class="my-5">
            <x-input-label for="content" :value="__('Content')" />
            <textarea id="content" name="content" rows="10"
            class="tinymce-component block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{old('content', $award->content)}}</textarea>
            <x-input-error :messages="$errors->get('content')" class="mt-2" />
        </div>

        <div class="flex my-5">
            <button type="submit"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">{{ __('Save') }}</button>
            @if (!is_null($award->id))
                <a href="{{ url()->previous() }}"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">{{ __('Back') }}</a>
            @endif
        </div>
    </form>
    </x-v2.common.component-card>
</div>

<x-footer.tinymce-config/>

@vite(['resources/js/cruds/aplazogame.js'])

@endsection

