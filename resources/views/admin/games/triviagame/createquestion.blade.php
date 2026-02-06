@extends('layouts.v2.app')

<x-slot name="title">
    {{ $question->id == null ? trans('Create') . ' ' . trans('Question') : $question->title }}
</x-slot>
<x-slot name="description">
    {{ $question->id == null ? '' : $question->title }}
</x-slot>

<x-slot name="header">
    <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
    {{ $question->id == null ? trans('Create') : trans('Edit') }} {{ __('Question') }}
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
            <form method="POST"
            action="{{ $question->id == null ? route('questions.store', ['tenant' => tenant('id')]) : route('questions.update', ['tenant' => tenant('id'), 'question' => $question]) }}">
            @csrf
            @isset($question->id)
            @method('PATCH')
            <input type="hidden" name="id" value="{{ $question->id }}">
            @endisset

            <input type="hidden" name="quiz_id"
            value="{{ !is_null($question->id) ? $question->quiz_id : $quiz_id }}">

            <div class="my-5">
                <x-input-label for="title" :value="__('Title')" />
                <x-text-input id="title" class="block mt-1 w-full" type="text" name="title"
                :value="old('title', $question->title)" required autofocus autocomplete="title" />
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>

            <div class="flex my-5">
                <button type="submit"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">{{ __('Save') }}</button>
                @if (!is_null($question->id))
                    <a href="{{ route('quizzes.edit', ['tenant' => tenant('id'), 'quiz' => $question->quiz]) }}"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">{{ __('Back to') }}
                    {{ __('Quiz') }}</a>
                @endif
            </div>
        </form>
    </div>
</div>
@if (!is_null($question->id))
    <div class="py-6 mx-5">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 bg-white p-3 rounded shadow">
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-5">
                {{ __('Answers') }}</h2>
                <a href="{{ route('triviagameanswers.create', ['tenant' => tenant('id'), 'question' => $question]) }}"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                {{ __('Create') }} {{ __('Answer') }}
                </a>
            </div>
            <div class="relative overflow-x-auto shadow-md rounded-lg">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                        <th scope="col" class="px-6 py-3">
                        {{ __('Question Title') }}
                        </th>
                        <th scope="col" class="px-6 py-3">
                        {{ __('Is correct answer') }}
                        </th>
                        <th scope="col" class="px-6 py-3">
                        {{ __('Actions') }}
                        </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($question->answers as $answer)
                            <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4">
                            {{ $answer->title }}
                            </th>
                            <td
                            class="px-6 py-4 font-bold {{ $answer->is_correct ? 'text-green-500' : 'text-red-500' }}">
                            {{ $answer->is_correct ? trans('Yes') : trans('No') }}
                            </td>
                            <td class="px-6 py-4">
                            <a href="{{ route('triviagameanswers.edit', ['tenant' => tenant('id'), 'triviagameanswer' => $answer]) }}"
                            class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ __('Edit') }}</a>
                            </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
@endsection

