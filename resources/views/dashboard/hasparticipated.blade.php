<x-app-layout>
    <x-slot name="title">
        {{ $title }}
    </x-slot>
    <x-slot name="description">
        {{ $description }}
    </x-slot>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('You have been participated in this quiz.') }}
        </h1>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden p-6 rounded">
                <p>
                    {{ __('You have been participated in this quiz. Be aware for more quizzes.') }}
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
