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
        {{-- <x-v2.ui.alert
        variant="success"
        title="{{ session('status') }}"
        :showLink="false"
        /> --}}
    <x-ui.modal-alert :title="session('status')" :description="session('description')" :open="true" />
    @endif
    <x-v2.common.page-breadcrumb pageTitle="{{ $title }}" />

    <x-v2.common.component-card title="{{ $title }}">
    <div class="max-w-full overflow-x-auto custom-scrollbar">
        <table class="w-full ">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800">
                @foreach (['Concurso', 'Tipo', 'Cupones Entregados', 'Cupones Disponibles', 'Acciones'] as $header)
                    <th class="px-5 py-3 text-left sm:px-6" scope="col">
                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                    {{ __($header) }}
                    </p>
                    </th>
                @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($awards as $award)
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                    <td class="px-5 py-4 sm:px-6" colspan="1">
                    <div class="flex items-center gap-3">
                        <div class="w-full">
                            <span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90" >{{ $award->awardable->title ? $award->awardable->title : 'OCR Tickets' }}</span>
                        </div>
                    </div>
                    </td>
                    <td class="px-5 py-4 sm:px-6" >
                    <div class="flex items-center gap-3">
                        <span class="block text-gray-500 text-theme-sm dark:text-gray-400" >{{ __(Str::title(Str::snake($award->model_type, ' '))) }}</span>
                    </div>
                    </td>
                    <td class="px-5 py-4 sm:px-6">
                    <div class="flex items-center gap-3">
                        <span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90" >{{ number_format($award->codes_delivered_count) }}</span>
                    </div>
                    </td>

                    <td class="px-5 py-4 sm:px-6">
                    <div class="flex items-center gap-3">
                        <span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90" >{{ number_format($award->codes_available_count) }}</span>
                    </div>
                    </td>
                    <td class="px-5 py-4 sm:px-6">
                    <div class="flex items-center justify-start space-x-3">
                        <a
                        href="{{ route('awardcodes.show', ['tenant' => tenant('id'), 'awardcode' => $award]) }}"
                        class="show-button inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 rounded-lg transition-colors"
                        aria-label="{{ __('Import') }} {{ $award->name }}"
                        >

                        {{ __('Import') }}
                        </a>
                        <a
                        data-action="edit"
                        href="{{ route('awardcodes.edit', ['tenant' => tenant('id'), 'awardcode' => $award]) }}"
                        class="edit-button inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 rounded-lg transition-colors"
                        aria-label="{{ __('Edit') }} {{ $award->name }}"
                        >
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M15.0911 2.78206C14.2125 1.90338 12.7878 1.90338 11.9092 2.78206L4.57524 10.116C4.26682 10.4244 4.0547 10.8158 3.96468 11.2426L3.31231 14.3352C3.25997 14.5833 3.33653 14.841 3.51583 15.0203C3.69512 15.1996 3.95286 15.2761 4.20096 15.2238L7.29355 14.5714C7.72031 14.4814 8.11172 14.2693 8.42013 13.9609L15.7541 6.62695C16.6327 5.74827 16.6327 4.32365 15.7541 3.44497L15.0911 2.78206ZM12.9698 3.84272C13.2627 3.54982 13.7376 3.54982 14.0305 3.84272L14.6934 4.50563C14.9863 4.79852 14.9863 5.2734 14.6934 5.56629L14.044 6.21573L12.3204 4.49215L12.9698 3.84272ZM11.2597 5.55281L5.6359 11.1766C5.53309 11.2794 5.46238 11.4099 5.43238 11.5522L5.01758 13.5185L6.98394 13.1037C7.1262 13.0737 7.25666 13.003 7.35947 12.9002L12.9833 7.27639L11.2597 5.55281Z"
                        fill="" />
                        </svg>
                        {{ __('Edit') }}
                        </a>
                        <button
                        onclick="openModal(this)"
                        data-route="{{ route('awardcodes.destroy', ['tenant' => tenant('id'), 'awardcode' => $award]) }}"
                        data-nameCodes="{{ $award->awardable->title ? $award->awardable->title : 'OCR Tickets' }}"
                        data-modal-target="modalDeleteCodes" data-modal-toggle="modalDeleteCodes" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2" type="button">
                        <x-heroicon-o-trash class="w-5" />
                        </button>
                    </div>

                    </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </x-v2.common.component-card>
</div>


<div id="modalDeleteCodes" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
            <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="modalDeleteCodes">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
            <span class="sr-only">Close modal</span>
            </button>
            <div class="p-4 md:p-5 text-center">
                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">¿Confirmar eliminación de códigos de <span id="nameGame" class="font-bold"></span>? </h3>
                <form id="formDeleteCodes" method="post"  action="{{ route('awards.codes.destroy', ['tenant' => tenant('id'), 'award' => 2]) }}">
                    @csrf
                    @method('delete')
                    <div class="mb-4">
                        <label for="inputConfirmDelete" class="block mb-2 text-[12px] font-medium text-gray-900 dark:text-white">Escribe "ELIMINAR" para confirmar eliminación.</label>
                        <input onkeyup="detectDelete(this)"
                        type="text" name="inputConfirmDelete" id="inputConfirmDelete" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" autocomplete="false" />
                    </div>
                    <div class="flex mb-4 justify-end">
                        <button data-modal-hide="modalDeleteCodes" id="btnDelete" disabled="true" type="submit" class="opacity-25 text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                        Confirmar eliminación
                        </button>
                        <button data-modal-hide="modalDeleteCodes" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 ">No</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>


<script>
let formDeleteCodes = document.getElementById('formDeleteCodes');
let inputConfirmDelete = document.getElementById('inputConfirmDelete');
let btnDelete = document.getElementById('btnDelete');
let nameGame = document.getElementById('nameGame');
function openModal(e){
inputConfirmDelete.value = "";
formDeleteCodes.setAttribute('action',e.getAttribute('data-route'));
nameGame.innerHTML = e.getAttribute('data-nameCodes');

}
// Confirmar eliminación de elementors
function detectDelete(e){
if(e.value.toUpperCase() == "ELIMINAR"){
btnDelete.style.opacity = '1';
btnDelete.removeAttribute('disabled');
}
}
</script>

@endsection

