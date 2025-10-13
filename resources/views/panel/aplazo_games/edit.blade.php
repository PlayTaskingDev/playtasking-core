<x-panel-layout>
    <x-slot name="title">
        {{ !is_null($aplazo_game->title) ? $aplazo_game->title : trans('Create') . '' . trans('Aplazo game') }}
    </x-slot>
    <x-slot name="description">
        {{ $aplazo_game->id == null ? '' : $aplazo_game->description }}
    </x-slot>

    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $aplazo_game->id == null ? trans('Create') : trans('Edit') }} {{ __('Aplazo game') }}
        </h1>
    </x-slot>

    <div class="py-6 mx-5">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 bg-white p-3 rounded shadow">
            <form method="POST" enctype="multipart/form-data"
                action="{{ $aplazo_game->id == null ? route('aplazo_games.store', ['tenant' => tenant('id')]) : route('aplazo_games.update', ['tenant' => tenant('id'), 'aplazo_game' => $aplazo_game]) }}">
                @csrf
                @isset($aplazo_game->id)
                    @method('PATCH')
                    <input type="hidden" name="id" value="{{ $aplazo_game->id }}">
                @endisset

                <input type="hidden" name="content_type_id" value="{{ $content_type->id }}">
                <div class="my-5">
                    <x-input-label for="campaign_id" :value="__('Campaign')" />
                    <select id="campaign_id" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="campaign_id" required autofocus>
                        <option value="">{{__('Select')}}</option>
                        @if ($campaigns)
                            @foreach ($campaigns as $campaign)
                                <option value="{{$campaign->id}}" {{(old('campaign_id') == $campaign->id ? 'selected' : (isset($aplazo_game->campaign) && $aplazo_game->campaign->id == $campaign->id ? 'selected' : ''))}}>{{$campaign->name}}</option>
                            @endforeach
                        @endif
                    </select>
                    <x-input-error :messages="$errors->get('campaign_id')" class="mt-2" />
                </div>

                <div class="my-5">
                    <x-input-label for="title" :value="__('Title')" />
                    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title"
                        :value="old('title', $aplazo_game->title)" required autofocus autocomplete="title" />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <div class="my-5">
                    <x-input-label for="description" :value="__('Description')" />
                    <x-text-input id="description" class="block mt-1 w-full" type="text" name="description"
                        :value="old('description', $aplazo_game->description)" required autofocus autocomplete="description" />
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-5">{{__('Promo information')}}</h3>
                <div class="my-5">
                    <x-input-label for="product_name" :value="__('Product name')" />
                    <x-text-input id="product_name" class="block mt-1 w-full" type="text" name="product_name"
                        :value="old('product_name', $aplazo_game->product_name)" required autofocus autocomplete="product_name" />
                    <x-input-error :messages="$errors->get('product_name')" class="mt-2" />
                </div>

                <div class="my-5">
                    <x-input-label for="product_description" :value="__('Promo description')" />
                    <x-text-input id="product_description" class="block mt-1 w-full" type="text" name="product_description"
                        :value="old('product_description', $aplazo_game->product_description)" required autofocus autocomplete="product_description" />
                    <x-input-error :messages="$errors->get('product_description')" class="mt-2" />
                </div>

                <div class="my-5">
                    <x-input-label for="price" :value="__('Price')" />
                    <x-text-input id="price" class="block mt-1 w-full" type="text" name="price"
                        :value="old('price', $aplazo_game->price)" required autofocus autocomplete="price" />
                    <x-input-error :messages="$errors->get('price')" class="mt-2" />
                </div>

                <div class="my-5 grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="featured_image" :value="__('Featured Image')" />
                        @if (!is_null($aplazo_game->id) && $aplazo_game->featured_image)
                            <img src="{{$aplazo_game->featured_image}}" alt="{{__('Aplazo Featured Image')}}" title="{{__('Aplazo Featured Image')}}" class="my-5">
                        @endif
                        <input
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                            aria-describedby="featured_image_help" id="featured_image" name="featured_image" type="file">
                        <x-input-error class="mt-2" :messages="$errors->get('featured_image')" />
                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="featured_image_help">
                            {{__('Image must be less than 2MB and JPG or PNG format.')}}<br>
                        </div>
                    </div>
                    <div>
                        <x-input-label for="featured_image_disabled" :value="__('Disabled Image')" />
                        @if (!is_null($aplazo_game->id) && $aplazo_game->featured_image_disabled)
                            <img src="{{$aplazo_game->featured_image_disabled}}" alt="{{__('Aplazo Disabled Image')}}" title="{{__('Aplazo Disabled Image')}}" class="my-5">
                        @endif
                        <input
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                            aria-describedby="featured_image_disabled_help" id="featured_image_disabled" name="featured_image_disabled" type="file">
                        <x-input-error class="mt-2" :messages="$errors->get('featured_image_disabled')" />
                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="featured_image_disabled_help">
                            {{__('Image must be less than 2MB and JPG or PNG format.')}}<br>
                        </div>
                    </div>
                </div>

                <div class="my-5">
                    <div>
                        <x-input-label for="promo_image" :value="__('Promo Image')" />
                        @if (!is_null($aplazo_game->id) && $aplazo_game->promo_image)
                            <img src="{{$aplazo_game->promo_image}}" alt="{{__('Promo Image')}}" title="{{__('Promo Image')}}" class="my-5">
                        @endif
                        <input
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                            aria-describedby="promo_image_help" id="promo_image" name="promo_image" type="file">
                        <x-input-error class="mt-2" :messages="$errors->get('promo_image')" />
                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="promo_image_help">
                            {{__('Image must be less than 2MB and JPG or PNG format.')}}
                        </div>
                    </div>
                </div>

                <div class="my-5">
                    <x-input-label for="slug" :value="__('Slug')" />
                    <x-text-input id="slug" class="block mt-1 w-full" type="text" name="slug"
                        :value="old('slug', $aplazo_game->slug)" required autofocus autocomplete="slug" />
                    <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                </div>

                <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-5">{{__('Date configuration')}}</h3>
                <div class="my-5 grid grid-cols-2 gap-4">
                    <div>
                        <button type="button" data-modal-target="timepicker-initdate-modal" data-modal-toggle="timepicker-initdate-modal" class="text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700 mb-3">
                            <svg class="w4 h-4 me-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                            </svg>
                            {{__('Set start date')}}
                        </button>

                        <x-input-label for="init_date" :value="__('Init date')" />
                        <div class="relative max-w-sm">
                            <div class="absolute inset-y-5 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                </svg>
                            </div>
                        </div>
                        <input id="init_date" name="init_date" value="{{ old('init_date', $aplazo_game->init_date) }}" type="text" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="{{ __('Select date') }}">
                            
                        <!-- Main modal -->
                        <div id="timepicker-initdate-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                            <div class="relative p-4 w-full max-w-[23rem] max-h-full">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                                    <!-- Modal header -->
                                    <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{__('Set start date')}}
                                        </h3>
                                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="timepicker-initdate-modal">
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>
                                    <!-- Modal body -->
                                    <div class="p-4 pt-0">
                                        <div id="datepickerInitDate" class="mx-auto sm:mx-0 flex justify-center my-5 [&>div>div]:shadow-none [&>div>div]:bg-gray-50 [&_div>button]:bg-gray-50"></div>
                                        <label class="text-sm font-medium text-gray-900 dark:text-white mb-2 block">
                                        {{__('Pick your time')}}
                                        </label>
                                        <ul id="timetableInitDate" class="grid w-full grid-cols-3 gap-2 mb-5">
                                            @foreach ($time_slots as $time_slot)
                                            <li>
                                                <input type="radio" id="{{$time_slot['id']}}-init" value="{{$time_slot['value']}}:00" class="hidden peer" name="timetableInitDate">
                                                <label for="{{$time_slot['id']}}-init"
                                                class="inline-flex items-center justify-center w-full px-2 py-1 text-sm font-medium text-center hover:text-gray-900 dark:hover:text-white bg-white dark:bg-gray-800 border rounded-lg cursor-pointer text-gray-500 border-gray-200 dark:border-gray-700 dark:peer-checked:border-blue-500 peer-checked:border-blue-700 dark:hover:border-gray-600 dark:peer-checked:text-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-600 dark:peer-checked:bg-blue-900">
                                                {{$time_slot['value']}}
                                                </label>
                                            </li>
                                            @endforeach
                                        </ul>
                                        <div class="grid grid-cols-2 gap-2">
                                            <button id="saveInitDateBtn" type="button" data-modal-hide="timepicker-initdate-modal" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 date-set" data-dateinstance="datepickerInitInstance" data-timetable="timetableInitDate" data-datefield="init_date">
                                                {{__('Save')}}
                                            </button>
                                            <button type="button" data-modal-hide="timepicker-initdate-modal" class="py-2.5 px-5 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                                {{__('Discard')}}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                        <x-input-error :messages="$errors->get('init_date')" class="mt-2" />
                    </div>
                    <div>
                        <button type="button" data-modal-target="timepicker-enddate-modal" data-modal-toggle="timepicker-enddate-modal" class="text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700 mb-3">
                            <svg class="w4 h-4 me-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                            </svg>
                            {{__('Set end date')}}
                        </button>

                        <x-input-label for="end_date" :value="__('End date')" />
                        <div class="relative max-w-sm">
                            <div class="absolute inset-y-5 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                </svg>
                            </div>
                        </div>
                        <input id="end_date" name="end_date" value="{{ old('end_date', $aplazo_game->end_date) }}" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="{{ __('Select date') }}">

                        <!-- Main modal -->
                        <div id="timepicker-enddate-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                            <div class="relative p-4 w-full max-w-[23rem] max-h-full">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                                    <!-- Modal header -->
                                    <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{__('Set start date')}}
                                        </h3>
                                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="timepicker-enddate-modal">
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>
                                    <!-- Modal body -->
                                    <div class="p-4 pt-0">
                                        <div id="datepickerEndDate" class="mx-auto sm:mx-0 flex justify-center my-5 [&>div>div]:shadow-none [&>div>div]:bg-gray-50 [&_div>button]:bg-gray-50"></div>
                                        <label class="text-sm font-medium text-gray-900 dark:text-white mb-2 block">
                                        {{__('Pick your time')}}
                                        </label>
                                        <ul id="timetableEndDate" class="grid w-full grid-cols-3 gap-2 mb-5">
                                            @foreach ($time_slots as $time_slot)
                                            <li>
                                                <input type="radio" id="{{$time_slot['id']}}-end" value="{{$time_slot['value']}}:00" class="hidden peer" name="timetableEndDate">
                                                <label for="{{$time_slot['id']}}-end"
                                                class="inline-flex items-center justify-center w-full px-2 py-1 text-sm font-medium text-center hover:text-gray-900 dark:hover:text-white bg-white dark:bg-gray-800 border rounded-lg cursor-pointer text-gray-500 border-gray-200 dark:border-gray-700 dark:peer-checked:border-blue-500 peer-checked:border-blue-700 dark:hover:border-gray-600 dark:peer-checked:text-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-600 dark:peer-checked:bg-blue-900">
                                                {{$time_slot['value']}}
                                                </label>
                                            </li>
                                            @endforeach
                                        </ul>
                                        <div class="grid grid-cols-2 gap-2">
                                            <button id="saveEndDateBtn" type="button" data-modal-hide="timepicker-enddate-modal" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 date-set" data-dateinstance="datepickerEndInstance" data-timetable="timetableEndDate" data-datefield="end_date">
                                                {{__('Save')}}
                                            </button>
                                            <button type="button" data-modal-hide="timepicker-enddate-modal" class="py-2.5 px-5 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                                {{__('Discard')}}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                    </div>
                </div>

                <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-5">{{__('Card settings')}}</h3>
                <div class="my-5 grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="gradient_1" :value="__('Gradient Background 1')" />
                        <x-text-input id="gradient_1" class="block mt-1 w-full" type="text" name="gradient_1"
                            :value="old('gradient_1', $aplazo_game->gradient_1)" required autofocus autocomplete="gradient_1" />
                        <x-input-error :messages="$errors->get('gradient_1')" class="mt-2" />
                        <div id="gradient1"></div>
                        <div id="piker-viewer-1" class="block mt-1 w-full h-10 rounded"></div>
                    </div>
                    <div>
                        <x-input-label for="gradient_2" :value="__('Gradient Background 1')" />
                        <x-text-input id="gradient_2" class="block mt-1 w-full" type="text" name="gradient_2"
                            :value="old('gradient_2', $aplazo_game->gradient_2)" required autofocus autocomplete="gradient_2" />
                        <x-input-error :messages="$errors->get('gradient_2')" class="mt-2" />
                        <div id="gradient2"></div>
                        <div id="piker-viewer-2" class="block mt-1 w-full h-10 rounded"></div>
                    </div>
                </div>

                <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-5">{{__('Button settings')}}</h3>
                <div class="my-5 grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="btn_background_color_1" :value="__('Gradient Button Background 1')" />
                        <x-text-input id="btn_background_color_1" class="block mt-1 w-full" type="text" name="btn_background_color_1"
                            :value="old('btn_background_color_1', $aplazo_game->btn_background_color_1)" required autofocus autocomplete="btn_background_color_1" />
                        <x-input-error :messages="$errors->get('btn_background_color_1')" class="mt-2" />
                        <div id="btn_bg_color_1"></div>
                        <div id="piker-viewer-3" class="block mt-1 w-full h-10 rounded"></div>
                    </div>
                    <div>
                        <x-input-label for="btn_background_color_2" :value="__('Gradient Button Background 2')" />
                        <x-text-input id="btn_background_color_2" class="block mt-1 w-full" type="text" name="btn_background_color_2"
                            :value="old('btn_background_color_2', $aplazo_game->btn_background_color_2)" required autofocus autocomplete="btn_background_color_2" />
                        <x-input-error :messages="$errors->get('btn_background_color_2')" class="mt-2" />
                        <div id="btn_bg_color_2"></div>
                        <div id="piker-viewer-4" class="block mt-1 w-full h-10 rounded"></div>
                    </div>
                </div>
                <div class="my-5 grid grid-cols-2 gap-4">
                    <div>
                        <input id="btn_border" name="btn_border" type="checkbox" value="1" {{$aplazo_game->btn_border ? 'checked' : ''}}
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="btn_border"
                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('Has border') }}</label>
                        <x-input-error :messages="$errors->get('btn_border')" class="mt-2" />

                        <div class="mt-3">
                            <x-input-label for="btn_border_color" :value="__('Button Border Color')" />
                            <x-text-input id="btn_border_color" class="block mt-1 w-full" type="text" name="btn_border_color"
                                :value="old('btn_border_color', $aplazo_game->btn_border_color)" required autofocus autocomplete="btn_border_color" />
                            <x-input-error :messages="$errors->get('btn_border_color')" class="mt-2" />
                            <div id="btn_brd_color"></div>
                            <div id="piker-viewer-5" class="block mt-1 w-full h-10 rounded"></div>
                        </div>
                    </div>
                    <div>
                        <input id="btn_shadow" name="btn_shadow" type="checkbox" value="1" {{$aplazo_game->btn_shadow ? 'checked' : ''}}
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="btn_shadow"
                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('Has shadow') }}</label>
                        <x-input-error :messages="$errors->get('btn_shadow')" class="mt-2" />

                        <div class="mt-3">
                            <x-input-label for="btn_text_color" :value="__('Button Text Color')" />
                            <x-text-input id="btn_text_color" class="block mt-1 w-full" type="text" name="btn_text_color"
                                :value="old('btn_text_color', $aplazo_game->btn_text_color)" required autofocus autocomplete="btn_text_color" />
                            <x-input-error :messages="$errors->get('btn_text_color')" class="mt-2" />
                            <div id="btn_txt_color"></div>
                            <div id="piker-viewer-6" class="block mt-1 w-full h-10 rounded"></div>
                        </div>
                    </div>
                </div>
                <div class="my-5 grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="btn_text_active" :value="__('Text Active')" />
                        <x-text-input id="btn_text_active" class="block mt-1 w-full" type="text" name="btn_text_active"
                            :value="old('btn_text_active', $aplazo_game->btn_text_active)" required autofocus autocomplete="btn_text_active" />
                        <x-input-error :messages="$errors->get('btn_text_active')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="btn_text_inactive" :value="__('Text Inactive')" />
                        <x-text-input id="btn_text_inactive" class="block mt-1 w-full" type="text" name="btn_text_inactive"
                            :value="old('btn_text_inactive', $aplazo_game->btn_text_inactive)" required autofocus autocomplete="btn_text_inactive" />
                        <x-input-error :messages="$errors->get('btn_text_inactive')" class="mt-2" />
                    </div>
                </div>

                <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-5">{{__('Banner settings')}}</h3>
                <div class="my-5">
                    <x-input-label for="game_banner" :value="__('Section banner')" />
                    @if ($aplazo_game->game_banner)
                        <div id="delete_image_holder" class="relative">
                            <img src="{{$aplazo_game->game_banner}}" alt="{{__('Banner Image')}}" title="{{__('Banner Image')}}" class="my-5 w-full">
                            <x-delete-image :element="'delete_image_holder'"></x-delete-image>
                        </div>
                        <input type="hidden" id="delete_image_holder_hidden" name="delete_image_holder_hidden" value="0">
                    @endif
                    <input
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                        aria-describedby="game_banner_help" id="game_banner" name="game_banner" type="file">
                    <x-input-error class="mt-2" :messages="$errors->get('game_banner')" />
                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="game_banner_help">
                        {{__('Image must be less than 2MB and JPG or PNG format.')}}<br>
                        {{__('Dimensions must be')}} 500 x 300
                    </div>
                </div>
                <div class="my-5">
                    <x-input-label for="game_banner_url" :value="__('Banner URL (Image)')" />
                    <x-text-input id="game_banner_url" class="block mt-1 w-full" type="text" name="game_banner_url"
                        :value="old('game_banner_url', $aplazo_game->game_banner_url)" autofocus autocomplete="game_banner_url" />
                    <x-input-error :messages="$errors->get('game_banner_url')" class="mt-2" />
                </div>
                <div class="my-5">
                    <x-input-label for="game_banner_video" :value="__('Video')" />
                    @if (!is_null($aplazo_game->id) && $aplazo_game->game_banner_video)
                    <div class="aspect-w-16 aspect-h-9 mb-6">
                        <iframe src="{{$aplazo_game->game_banner_video}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    @endif
                    <x-text-input id="game_banner_video" class="block mt-1 w-full" type="text" name="game_banner_video"
                        :value="old('game_banner_video', $aplazo_game->game_banner_video)" autofocus autocomplete="game_banner_video" />
                    <x-input-error :messages="$errors->get('game_banner_video')" class="mt-2" />
                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="game_banner_help">
                        {{__('If a video URL is set, it has precedence over the image.')}}<br>
                        {{ __('Use an embed URL as: https://www.youtube.com/embed/B-M3YlA2KDg') }}
                    </div>
                </div>

                <div class="my-5">
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>

    @if (!is_null($aplazo_game->id))
        <div class="py-6 mx-5">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 bg-white p-3 rounded shadow">
                <div class="flex justify-between">
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-5">
                        {{ __('Award') }}
                    </h2>
                    @if (is_null($aplazo_game->award))
                        <a href="{{ route('awards.create', ['tenant' => tenant('id'), 'awardable_id' => $aplazo_game, 'awardable_type' => 'App\Models\AplazoGame' ]) }}"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                            {{ __('Create') }} {{ __('Award') }}
                        </a>
                    @endif

                </div>
                @if (!is_null($aplazo_game->award))
                    <div class="relative overflow-x-auto shadow-md rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        {{ __('Title') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        {{ __('Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4">
                                        {!!$aplazo_game->award->title!!}
                                    </th>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('awards.edit', ['tenant' => tenant('id'), 'award' => $aplazo_game->award]) }}"
                                            class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ __('Edit') }}</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    @endif

    @section('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.0.0/datepicker.min.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const gradient1 = new ColorPicker({
                    color: '{{$aplazo_game->gradient_1}}',
                    background: '#fff',
                    el: document.getElementById('gradient1'),
                    width: 250,
                    height: 150,
                });
    
                gradient1.onChange(function(){
                    currentColor = gradient1.getHexString();
                    let inputColor = document.getElementById('gradient_1');
                    inputColor.value = currentColor;
    
                    let pickerViewer = document.getElementById('piker-viewer-1');
                    pickerViewer.style.backgroundColor = currentColor;
                });

                const gradient2 = new ColorPicker({
                    color: '{{$aplazo_game->gradient_2}}',
                    background: '#fff',
                    el: document.getElementById('gradient2'),
                    width: 250,
                    height: 150,
                });
    
                gradient2.onChange(function(){
                    currentColor = gradient2.getHexString();
                    let inputColor = document.getElementById('gradient_2');
                    inputColor.value = currentColor;
    
                    let pickerViewer = document.getElementById('piker-viewer-2');
                    pickerViewer.style.backgroundColor = currentColor;
                });

                const gradient3 = new ColorPicker({
                    color: '{{$aplazo_game->btn_background_color_1}}',
                    background: '#fff',
                    el: document.getElementById('btn_bg_color_1'),
                    width: 250,
                    height: 150,
                });

                gradient3.onChange(function(){
                    currentColor = gradient3.getHexString();
                    let inputColor = document.getElementById('btn_background_color_1');
                    inputColor.value = currentColor;
    
                    let pickerViewer = document.getElementById('piker-viewer-3');
                    pickerViewer.style.backgroundColor = currentColor;
                });

                const gradient4 = new ColorPicker({
                    color: '{{$aplazo_game->btn_background_color_2}}',
                    background: '#fff',
                    el: document.getElementById('btn_bg_color_2'),
                    width: 250,
                    height: 150,
                });
    
                gradient4.onChange(function(){
                    currentColor = gradient4.getHexString();
                    let inputColor = document.getElementById('btn_background_color_2');
                    inputColor.value = currentColor;

                    let pickerViewer = document.getElementById('piker-viewer-4');
                    pickerViewer.style.backgroundColor = currentColor;
                });

                const gradient5 = new ColorPicker({
                    color: '{{$aplazo_game->btn_border_color}}',
                    background: '#fff',
                    el: document.getElementById('btn_brd_color'),
                    width: 250,
                    height: 150,
                });
    
                gradient5.onChange(function(){
                    currentColor = gradient5.getHexString();
                    let inputColor = document.getElementById('btn_border_color');
                    inputColor.value = currentColor;

                    let pickerViewer = document.getElementById('piker-viewer-5');
                    pickerViewer.style.backgroundColor = currentColor;
                });

                const gradient6 = new ColorPicker({
                    color: '{{$aplazo_game->btn_text_color}}',
                    background: '#fff',
                    el: document.getElementById('btn_txt_color'),
                    width: 250,
                    height: 150,
                });
    
                gradient6.onChange(function(){
                    currentColor = gradient6.getHexString();
                    let inputColor = document.getElementById('btn_text_color');
                    inputColor.value = currentColor;

                    let pickerViewer = document.getElementById('piker-viewer-6');
                    pickerViewer.style.backgroundColor = currentColor;
                });
            });
        </script>
    @endsection
</x-panel-layout>
