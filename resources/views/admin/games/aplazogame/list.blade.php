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
            <button 
            data-action="create"
            data-modal-target="aplazogame-modal"
            data-modal-toggle="aplazogame-modal"
            data-save-route="{{ route('aplazogames.store', tenant('id')) }}"
            class="btn bg-brand-500 hover:bg-brand-600 flex w-full justify-center rounded-lg px-4 py-2.5 text-sm font-medium text-white sm:w-auto" aria-label="{{ __('Add new AplazoGame') }}">
                {{ __('Add AplazoGame +') }}
            </button>
        </div>
    <x-v2.common.component-card title="{{ $title }}">
        <div class="max-w-full overflow-x-auto custom-scrollbar">
        <table class="w-full ">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800">
                    @foreach (['Campaign', 'Expiration', 'Active', 'Actions'] as $header)
                        <th class="px-5 py-3 text-left sm:px-6" scope="col">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                {{ __($header) }}
                            </p>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($aplazo_games as $aplazo_game)
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <td class="px-5 py-4 sm:px-6" colspan="1">
                            <div class="flex items-center gap-3">
                                <div class="w-lg">
                                    <span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90" >{{ $aplazo_game->name }}</span>
                                    <span class="block text-gray-500 text-theme-sm dark:text-gray-400" >{{ $aplazo_game->description }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center gap-3">
                                <span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90" >{{ $aplazo_game->only_date }}</span>
                            </div>
                        </td>
                        
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center gap-3">
                                <span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90" >{{ $aplazo_game->active ? trans('Yes') : trans('No') }}</span>     
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center justify-start space-x-3">
                                <a 
                                    data-action="edit"
                                    data-modal-target="aplazogame-modal"
                                    data-modal-toggle="aplazogame-modal"
                                    href="{{ route('aplazogames.edit', [tenant('id'), $aplazo_game]) }}"
                                    data-save-route="{{ route('aplazogames.update', [tenant('id'), $aplazo_game]) }}"
                                    class="edit-button inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 rounded-lg transition-colors" 
                                    aria-label="{{ __('Edit') }} {{ $aplazo_game->name }}"
                                            fill="" />
                                    </svg>
                                    {{ __('Edit') }}
                                </a>
                                <form method="post" action="{{ route('aplazogames.destroy', ['tenant' => tenant('id'), 'aplazogame' => $aplazo_game]) }}">
                                    @csrf
                                    @method('delete')
                                </form>
    </div>
</div>
<x-footer.tinymce-config/>

@vite(['resources/js/cruds/aplazogame.js'])

@endsection