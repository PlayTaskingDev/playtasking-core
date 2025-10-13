<x-panel-layout>
    <x-slot name="title">
        {{ $ticketQuestion->id == null ? trans('Create') . ' ' . trans('Question') : $ticketQuestion->title }}
    </x-slot>
    <x-slot name="description">
        {{ $ticketQuestion->id == null ? '' : $ticketQuestion->description }}
    </x-slot>

    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $ticketQuestion->id == null ? trans('Create') : trans('Edit') }} {{ __('Question') }}
        </h1>
    </x-slot>

    @if (session('status'))
        <div class="mx-5">
            <x-alert :status="session('status')" class="max-w-2xl mx-auto sm:px-6 lg:px-8 p-4 mt-4 text-sm rounded-lg" role="alert" />
        </div>
    @endif

    <div class="py-6 mx-5">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 bg-white p-3 rounded shadow">
            <form method="POST"
                action="{{ $ticketQuestion->id == null ? route('ticketQuestion.store', ['tenant' => tenant('id')]) : route('ticketQuestion.update', ['tenant' => tenant('id'), 'ticketQuestion' => $ticketQuestion]) }}">
                @csrf
                @isset($ticketQuestion->id)
                    @method('PATCH')
                    <input type="hidden" name="id" value="{{ $ticketQuestion->id }}">
                @endisset

                <div class="my-5">
                    <x-input-label for="title" :value="__('Title')" />
                    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title"
                        :value="old('title', $ticketQuestion->title)" required autofocus autocomplete="title" />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <div class="flex my-5">
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>

    @if (!is_null($ticketQuestion->id))
        <div class="py-6 mx-5">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 bg-white p-3 rounded shadow">
                <div class="flex justify-between">
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-5">
                        {{ __('Answers') }}</h2>
                    <a href="{{ route('ticketAnswer.create', ['tenant' => tenant('id'), 'ticketQuestion' => $ticketQuestion]) }}"
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
                            @foreach ($ticketQuestion->ticket_answers as $answer)
                                <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4">
                                        {{ $answer->title }}
                                    </th>
                                    <td
                                        class="px-6 py-4 font-bold {{ $answer->is_correct ? 'text-green-500' : 'text-red-500' }}">
                                        {{ $answer->is_correct ? trans('Yes') : trans('No') }}
                                    </td>
                                    <td class="px-6 py-4 grid grid-cols-2 gap-2 justify-items-center">
                                        <a href="{{ route('ticketAnswer.edit', ['tenant' => tenant('id'), 'ticketAnswer' => $answer]) }}"
                                            class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ __('Edit') }}</a>

                                        <form method="post" action="{{ route('ticketAnswer.destroy', ['tenant' => tenant('id'), 'ticketAnswer' => $answer]) }}">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline" onclick="return confirm('{{ __('Are you sure to delete this?') }}')">
                                                {{ __('Delete') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

</x-panel-layout>
