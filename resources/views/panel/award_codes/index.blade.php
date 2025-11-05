<x-panel-layout>
    <x-slot name="title">
        {{ $title }}
    </x-slot>
    <x-slot name="description">
        {{ $description }}
    </x-slot>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Award Codes') }}
        </h1>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="relative overflow-x-auto shadow-md rounded-lg mx-5">
                @if (session('status'))
                    <x-alert :status="session('status')" class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-4 my-4 text-sm rounded-lg" role="alert" />
                @endif
                @if (isset($failures))
                    @foreach ($failures as $failure)
                        <div class="p-4 my-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                            <span class="font-medium">{{__('Error on row ')}}{{$failure->row()}}: </span>
                            @foreach ($failure->errors() as $error)
                                {{$error}}
                            @endforeach
                            {{ $failure->values()[$failure->attribute()] }}
                        </div>
                    @endforeach
                @endif
                @if (!$awards->isEmpty())
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 bg-white">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                {{ __('Concourse') }}
                            </th>
                            <th scope="col" class="px-6 py-3 hidden sm:table-cell">
                                {{ __('Type') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-center hidden sm:table-cell">
                                {{ __('Codes Delivered') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                                {{ __('Codes Available') }}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{ __('Actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($awards as $award)
                            <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 w-[100%]">
                                    {{ $award->awardable->title ? $award->awardable->title : 'OCR Tickets' }}
                                </th>
                                <td class="p-2 hidden sm:table-cell w-[40%]">
                                    {{ __(Str::title(Str::snake($award->model_type, ' '))) }}
                                </td>
                                <td class="p-2 text-center hidden sm:table-cell w-[30%]">
                                    {{ number_format($award->codes_delivered_count) }}
                                </td>
                                <td class="p-2 text-center w-[30%]">
                                    {{ number_format($award->codes_available_count) }}
                                </td>
                                <td class="flex items-center justify-around w-[100%]">
                                    <a href="{{ route('awards.codes.show', ['tenant' => tenant('id'), 'award' => $award]) }}"
                                        class="font-medium text-blue-600 mr-4 hover:underline">{{ __('Import') }}
                                    </a>
                                    <a href="{{ route('awards.edit', ['tenant' => tenant('id'), 'award' => $award]) }}"
                                        class="font-medium text-blue-600 mr-4 hover:underline">{{ __('Edit') }}
                                    </a>
                                    <button 
                                    onclick="openModal(this)" 
                                    data-route="{{ route('awards.codes.destroy', ['tenant' => tenant('id'), 'award' => $award]) }}" 
                                    data-nameCodes="{{ $award->awardable->title ? $award->awardable->title : 'OCR Tickets' }}"
                                    data-modal-target="modalDeleteCodes" data-modal-toggle="modalDeleteCodes" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2" type="button">
                                       <x-heroicon-o-trash class="w-[20px]" />
                                    </button>
                                    
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
</x-panel-layout>
