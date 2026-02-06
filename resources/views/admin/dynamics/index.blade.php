@extends('layouts.v2.app')
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
@section('content')
    <x-v2.common.page-breadcrumb pageTitle="" />
    <div class="space-y-6">
        <x-v2.common.component-card title="{{ $title }}">
        <div x-data="{
        dynamics: {{$content_types}},
        getStatusClass(status) {
        const classes = {
        'Active': 'bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500',
        'Pending': 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400',
        'Cancel': 'bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-500',
        };
        return classes[status] || '';
        }
        }">
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="max-w-full overflow-x-auto custom-scrollbar">
                <table class="w-full min-w-[1102px]">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                        <th class="px-5 py-3 text-left sm:px-6">
                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                        {{ __('Name') }}
                        </p>
                        </th>
                        <th class="px-5 py-3 text-left sm:px-6">
                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                        {{ __('Description') }}
                        </p>
                        </th>
                        <th class="px-5 py-3 text-left sm:px-6">
                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                        {{ __('Actions') }}
                        </p>
                        </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="dynamic in dynamics" :key="dynamic.id">
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                        <td class="px-5 py-4 sm:px-6">
                        <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="dynamic.name"></p>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                        <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="dynamic.description"></p>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                        <a  x-bind:href="dynamic" href=""
                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ __('Edit') }}</a>
                        </td>
                        </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </x-v2.common.component-card>
</div>
@endsection

