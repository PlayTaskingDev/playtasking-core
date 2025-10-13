<x-panel-layout>
    <x-slot name="title">
        {{ $ticketAnswer->id == null ? trans('Create') . ' ' . trans('Answer') : $ticketAnswer->title }}
    </x-slot>
    <x-slot name="description">
        {{ $ticketAnswer->id == null ? '' : $ticketAnswer->description }}
    </x-slot>

    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $ticketAnswer->id == null ? trans('Create') : trans('Edit') }} {{ __('Answer') }}
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
                action="{{ $ticketAnswer->id == null ? route('ticketAnswer.store', ['tenant' => tenant('id')]) : route('ticketAnswer.update', ['tenant' => tenant('id'), 'ticketAnswer' => $ticketAnswer]) }}">
                @csrf
                @if (!is_null($ticketAnswer->id))
                    @method('PATCH')
                    <input type="hidden" name="id" value="{{ $ticketAnswer->id }}">
                @endif

                <input type="hidden" name="ticket_question_id"
                    value="{{ !is_null($ticketAnswer->id) ? $ticketAnswer->ticket_question_id : $ticket_question_id }}">

                <div class="my-5">
                    <x-input-label for="title" :value="__('Title')" />
                    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title"
                        :value="old('title', $ticketAnswer->title)" required autofocus autocomplete="title" />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <div class="my-5 flex items-center">
                    <input id="is_correct" name="is_correct" type="checkbox" value="1" {{$ticketAnswer->is_correct ? 'checked' : ''}}
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="is_correct"
                        class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('Is correct answer') }}</label>
                    <x-input-error :messages="$errors->get('is_correct')" class="mt-2" />
                </div>

                <div class="flex my-5">
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">{{ __('Save') }}</button>
                    @if (!is_null($ticketAnswer->id))
                        <a href="{{ route('ticketQuestion.edit', ['tenant' => tenant('id'), 'ticketQuestion' => $ticketAnswer->ticket_question]) }}"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">{{ __('Back to') }}
                            {{ __('Question') }}</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</x-panel-layout>
