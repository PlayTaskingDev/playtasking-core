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
    @if (session('status'))
        <x-v2.ui.alert
        variant="success"
        title="{{ session('status') }}"
        :showLink="false"
        />
    @endif
    <x-v2.common.page-breadcrumb pageTitle="{{ $title }}" />
    <div class="space-y-6">

        <div class="w-full flex justify-end">
            <a
                data-action="create"
                href="{{ route('sharegames.create', ['tenant' => tenant('id')]) }}"
                class="btn bg-brand-500 hover:bg-brand-600 flex w-full justify-center rounded-lg px-4 py-2.5 text-sm font-medium text-white sm:w-auto" aria-label="{{ __('Add new ShareGame') }}">
                {{ __('Add ShareGame +') }}
            </a>
        </div>
        <x-v2.common.component-card title="{{ $title }}">
        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="w-full ">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                    @foreach (['Game', 'Expiration', 'Active', 'Actions'] as $header)
                        <th class="px-5 py-3 text-left sm:px-6" scope="col">
                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                        {{ __($header) }}
                        </p>
                        </th>
                    @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($share_quizzes as $share_quizze)
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                        <td class="px-5 py-4 sm:px-6" colspan="1">
                        <div class="flex items-center gap-3">
                            <div class="w-lg">
                                <span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90" >{{ $share_quizze->title }}</span>
                                <span class="block text-gray-500 text-theme-sm dark:text-gray-400" >{{ $share_quizze->description }}</span>
                            </div>
                        </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                        <div class="flex items-center gap-3">
                            <span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90" >{{ $share_quizze->only_date }}</span>
                        </div>
                        </td>

                        <td class="px-5 py-4 sm:px-6">
                        <div class="flex items-center gap-3">
                            <span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90" >{{ $share_quizze->active ? trans('Yes') : trans('No') }}</span>
                        </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                        <div class="flex items-center justify-start space-x-3">
                            <a
                            data-action="edit"
                            href="{{ route('sharegames.edit', [tenant('id'), $share_quizze]) }}"
                            data-save-route="{{ route('sharegames.update', [tenant('id'), $share_quizze]) }}"
                            class="edit-button inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 rounded-lg transition-colors"
                            aria-label="{{ __('Edit') }} {{ $share_quizze->name }}">
                            <x-heroicon-o-pencil-square class="w-5"/>
                            {{ __('Edit') }}
                            </a>
                            @if($share_quizze->award)
                            <a href="{{ route('v2awards.edit', ['tenant' => tenant('id'), 'v2award' => $share_quizze->award]) }}" class=" inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800 rounded-lg transition-colors">
                            <x-heroicon-o-gift class="w-5"/>
                            {{ __('Edit Award') }}</a>
                            @endif  
                            <form method="post" action="{{ route('sharegames.destroy', ['tenant' => tenant('id'), 'sharegame' => $share_quizze]) }}">
                                @csrf
                                @method('delete')
                                <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline" onclick="return confirm('{{ __('Are you sure to delete this?') }}')">
                                <x-heroicon-o-trash class="w-5" />
                                </button>
                            </form>
                        </div>
                        </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        </x-v2.common.component-card>
    </div>

    <x-footer.tinymce-config/>


@endsection

