<x-panel-layout>
    <x-slot name="title">
        {{ $title }}
    </x-slot>
    <x-slot name="description">
        {{ $description }}
    </x-slot>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pages') }}
        </h1>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="relative overflow-x-auto shadow-md rounded-lg mx-5">
                @if (session('status'))
                    <x-alert :status="session('status')" class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-4 mb-4 text-sm rounded-lg"
                        role="alert" />
                @endif
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                {{ __('Page Title') }}
                            </th>
                            <th scope="col" class="px-6 py-3 hidden sm:table-cell">
                                {{ __('Description') }}
                            </th>
                            <th scope="col" class="px-6 py-3 hidden sm:table-cell">
                                {{ __('Slug') }}
                            </th>
                            <th scope="col" class="px-6 py-3 hidden sm:table-cell">
                                {{ __('Is active') }}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{ __('Actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pages as $page)
                            <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4">
                                    {{ $page->title }}
                                </th>
                                <td class="px-6 py-4 hidden sm:table-cell">
                                    {{ $page->description }}
                                </td>
                                <td class="px-6 py-4 hidden sm:table-cell">
                                    {{ $page->slug }}
                                </td>
                                <td
                                    class="px-6 py-4 font-bold {{ $page->is_active ? 'text-green-500' : 'text-red-500' }} hidden sm:table-cell">
                                    {{ $page->is_active ? trans('Yes') : trans('No') }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('pages.edit', ['tenant' => tenant('id'), 'page' => $page]) }}"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ __('Edit') }}</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-panel-layout>
