<x-panel-layout>
    <x-slot name="title">
        {{ $title }}
    </x-slot>
    <x-slot name="description">
        {{ $description }}
    </x-slot>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Share Quizzes') }}
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
                    <a href="{{ route('share_quizzes.create', ['tenant' => tenant('id')]) }}"
                        class="text-black bg-white hover:bg-blue-300 focus:ring-4 focus:ring-blue-300 font-bold rounded-lg text-sm px-5 py-2.5 mb-5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        {{ __('Create') }} {{ __('Share Quiz') }}
                    </a>
                </div>
                @if ($share_quizzes->isNotEmpty())
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 table-fixed">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                {{ __('Title') }}
                            </th>
                            <th scope="col" class="px-6 py-3 hidden sm:table-cell">
                                {{ __('Description') }}
                            </th>
                            <th scope="col" class="px-6 py-3 hidden sm:table-cell">
                                {{ __('Expires on') }}
                            </th>
                            <th scope="col" class="px-6 py-3 hidden sm:table-cell">
                                {{ __('Is valid') }}
                            </th>
                            <th scope="col" class="px-6 py-3" style="width: 25%;">
                                {{ __('Actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($share_quizzes as $share_quiz)
                            <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4">
                                    {{ $share_quiz->title }}
                                </th>
                                <td class="px-6 py-4 hidden sm:table-cell">
                                    {{ $share_quiz->description }}
                                </td>
                                <td class="px-6 py-4 hidden sm:table-cell">
                                    {{ $share_quiz->only_date }}
                                </td>
                                <td
                                    class="px-6 py-4 font-bold {{ $share_quiz->is_valid ? 'text-green-500' : 'text-red-500' }} hidden sm:table-cell">
                                    {{ $share_quiz->is_valid ? trans('Yes') : trans('No') }}
                                </td>
                                <td class="px-6 py-4 grid grid-cols-3 gap-2 justify-items-center">
                                    <a href="{{ route('share_quizzes.edit', ['tenant' => tenant('id'), 'share_quiz' => $share_quiz]) }}"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                        {{ __('Edit') }}
                                    </a>
                                    <a href="{{ route('panel.export_user_interactions', ['tenant' => tenant('id'), 'model_id' => $share_quiz->id]) }}"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                        {{ __('Export') }}
                                    </a>
                                    <form method="post" action="{{ route('share_quizzes.destroy', ['tenant' => tenant('id'), 'share_quiz' => $share_quiz]) }}">
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
