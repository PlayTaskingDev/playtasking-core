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
    <x-v2.common.page-breadcrumb pageTitle="{{ $title }}" desc="Aquí podras agregar y editar juegos tipo Comparte y Gana." isBtn='1' :titleBtn="__('Add SmashGame +')" routeBtn="{{ route('smashgames.create', ['tenant' => tenant('id')]) }}"/>
    <div class="space-y-6">

        <x-v2.common.component-card >
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
                    @foreach ($smash_games as $smash_game)
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                        <td class="px-5 py-4 sm:px-6" colspan="1">
                        <div class="flex items-center gap-3">
                            <div class="w-64">
                                <span class="block font-bold text-gray-800 text-theme-sm dark:text-white/90" >{{ $smash_game->title }}</span>
                                <span class="block text-gray-500 text-theme-xs dark:text-gray-400" >{{ $smash_game->description }}</span>
                            </div>
                        </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                        <div class="flex items-center gap-3">
                            <span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90" >{{ $smash_game->only_date }}</span>
                        </div>
                        </td>

                        <td class="px-5 py-4 sm:px-6">
                        <div class="flex items-center gap-3">
                            <span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90" >{{ $smash_game->active ? trans('Yes') : trans('No') }}</span>
                        </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                        <div class="flex items-center justify-end space-x-3">
                            <a
                            data-action="edit"
                            href="{{ route('smashgames.edit', [tenant('id'), $smash_game]) }}"
                            data-save-route="{{ route('smashgames.update', [tenant('id'), $smash_game]) }}"
                            class="border border-black inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-black   dark:bg-blue-700 dark:hover:bg-blue-800 rounded-lg transition-colors"
                            aria-label="{{ __('Edit') }} {{ $smash_game->name }}">
                            <x-heroicon-o-pencil-square class="w-5"/>
                            {{ __('Edit') }}
                            </a>
                            @if($smash_game->award)
                            <a href="{{ route('v2awards.edit', ['tenant' => tenant('id'), 'v2award' => $smash_game->award]) }}" class=" inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800 rounded-lg transition-colors">
                            <x-heroicon-o-gift class="w-5"/>
                            {{ __('Edit Award') }}</a>
                            <a href="{{ route('panel.export_user_interactions', ['tenant' => tenant('id'), 'table_name' => $smash_game->table_name, 'model_id' => $smash_game->id]) }}"
                                class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 rounded-lg transition-colors">
                                {{ __('Export') }}
                            </a>
                            @endif
                            <form method="post" action="{{ route('smashgames.destroy', ['tenant' => tenant('id'), 'smashgame' => $smash_game]) }}">
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

