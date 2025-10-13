<x-panel-layout>
    <x-slot name="title">
        {{ $title }}
    </x-slot>
    <x-slot name="description">
        {{ $description }}
    </x-slot>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Content types') }}
        </h1>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="relative overflow-x-auto rounded-lg mx-5">
                @if (session('status'))
                    <x-alert :status="session('status')" class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-4 my-4 text-sm rounded-lg"
                        role="alert" />
                @endif
                
                @if ($content_types->isNotEmpty())
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 table-auto">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                {{ __('Name') }}
                            </th>
                            <th scope="col" class="px-6 py-3 hidden sm:table-cell">
                                {{ __('Description') }}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{ __('Actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($content_types as $content_type)
                            <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4">
                                    {{ $content_type->name }}
                                </th>
                                <td class="px-6 py-4 hidden sm:table-cell">
                                    {{ $content_type->description }}
                                </td>
                                <td class="px-6 py-4 grid grid-cols-2 gap-2 justify-items-center">
                                    <a href="{{ route('content_type.edit', ['tenant' => tenant('id'), 'content_type' => $content_type]) }}"
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
