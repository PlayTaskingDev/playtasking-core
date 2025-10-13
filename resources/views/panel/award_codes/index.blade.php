<x-panel-layout>
    <x-slot name="title">
        {{ $title }}
    </x-slot>
    <x-slot name="description">
        {{ $description }}
    </x-slot>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Award Codes') }}
        </h1>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="relative overflow-x-auto shadow-md rounded-lg mx-5">
                @if (session('status'))
                    <x-alert :status="session('status')" class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-4 my-4 text-sm rounded-lg" role="alert" />
                @endif
                @if (isset($failures))
                    @foreach ($failures as $failure)
                        <div class="p-4 my-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                            <span class="font-medium">{{__('Error on row ')}}{{$failure->row()}}: </span>
                            @foreach ($failure->errors() as $error)
                                {{$error}}
                            @endforeach
                            {{ $failure->values()[$failure->attribute()] }}
                        </div>
                    @endforeach
                @endif
                @if (!$awards->isEmpty())
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 bg-white">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                {{ __('Concourse') }}
                            </th>
                            <th scope="col" class="px-6 py-3 hidden sm:table-cell">
                                {{ __('Type') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-center hidden sm:table-cell">
                                {{ __('Codes Delivered') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                                {{ __('Codes Available') }}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{ __('Actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($awards as $award)
                            <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4">
                                    {{ $award->awardable->title ? $award->awardable->title : 'OCR Tickets' }}
                                </th>
                                <td class="p-2 hidden sm:table-cell">
                                    {{ __(Str::title(Str::snake($award->model_type, ' '))) }}
                                </td>
                                <td class="p-2 text-center hidden sm:table-cell">
                                    {{ number_format($award->codes_delivered_count) }}
                                </td>
                                <td class="p-2 text-center">
                                    {{ number_format($award->codes_available_count) }}
                                </td>
                                <td class="p-2 grid grid-cols-2 gap-2 justify-items-center place-items-center">
                                    <a href="{{ route('awards.codes.show', ['tenant' => tenant('id'), 'award' => $award]) }}"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ __('Import') }}
                                    </a>
                                    <a href="{{ route('awards.edit', ['tenant' => tenant('id'), 'award' => $award]) }}"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ __('Edit') }}
                                    </a>
                                    <form method="post" action="{{ route('awards.codes.destroy', ['tenant' => tenant('id'), 'award' => $award]) }}">
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
                @else
                <div class="bg-white p-5">
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
                        {{__('There are no items to display')}}
                    </h2>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-panel-layout>
