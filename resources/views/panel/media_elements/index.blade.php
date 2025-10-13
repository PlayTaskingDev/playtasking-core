<x-panel-layout>
    <x-slot name="title">
        {{ $title }}
    </x-slot>
    <x-slot name="description">
        {{ $description }}
    </x-slot>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Media elements') }}
        </h1>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="relative overflow-x-auto rounded-lg mx-5">
                @if (session('status'))
                    <x-alert :status="session('status')" class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-4 my-4 text-sm rounded-lg"
                        role="alert" />
                @endif
                <div class="grid justify-items-end">
                    <a href="{{ route('media_elements.create', ['tenant' => tenant('id')]) }}"
                        class="text-black bg-white hover:bg-blue-300 focus:ring-4 focus:ring-blue-300 font-bold rounded-lg text-sm px-5 py-2.5 mb-5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        {{ __('Create') }} {{ __('Media element') }}
                    </a>
                </div>
                @if (!$media_elements->isEmpty())
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 bg-white table-auto">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                {{ __('Thumbnail') }}
                            </th>
                            <th scope="col" class="px-6 py-3 hidden sm:table-cell">
                                {{ __('Description') }}
                            </th>
                            <th scope="col" class="px-6 py-3 hidden sm:table-cell w-32">
                                {{ __('URL') }}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{ __('Actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($media_elements as $media_element)
                            <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4">
                                    <img class="w-8" src="{{ $media_element->asset }}" alt="{{ $media_element->description }}" title="{{ $media_element->description }}">
                                </th>
                                <td class="px-6 py-4 hidden sm:table-cell">
                                    {{ $media_element->description }}
                                </td>
                                <td class="px-6 py-4 hidden sm:table-cell text-wrap">
                                    {{ $media_element->asset }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('media_elements.edit', ['tenant' => tenant('id'), 'media_element' => $media_element]) }}"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ __('Edit') }}</a>
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
