<x-panel-layout>
    <x-slot name="title">
        {{ !is_null($campaign->name) ? $campaign->name : trans('Create') . ' ' . trans('Campaign') }}
    </x-slot>
    <x-slot name="description">
        {{ $campaign->id == null ? '' : $campaign->description }}
    </x-slot>

    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $campaign->id == null ? trans('Create') : trans('Edit') }} {{ __('Campaign') }}
        </h1>
    </x-slot>

    <div class="py-6 mx-5">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 bg-white p-3 rounded shadow">
            <form method="POST" enctype="multipart/form-data"
                action="{{ $campaign->id == null ? route('panel.campaign.store', ['tenant' => tenant('id')]) : route('panel.campaign.update', ['tenant' => tenant('id'), 'campaign' => $campaign]) }}">
                @csrf
                @isset($campaign->id)
                    @method('PATCH')
                    <input type="hidden" name="id" value="{{ $campaign->id }}">
                @endisset

                <div class="my-5">
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                        :value="old('name', $campaign->name)" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="my-5">
                    <x-input-label for="description" :value="__('Description')" />
                    <x-text-input id="description" class="block mt-1 w-full" type="text" name="description"
                        :value="old('description', $campaign->description)" required autofocus autocomplete="description" />
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="my-5">
                    <x-input-label for="slug" :value="__('Slug')" />
                    <x-text-input id="slug" class="block mt-1 w-full" type="text" name="slug"
                        :value="old('slug', $campaign->slug)" required autofocus autocomplete="slug" />
                    <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                </div>

                <div class="my-5 grid grid-cols-4 gap-4">
                    <div>
                        <input id="active" name="active" type="checkbox" value="1" {{$campaign->active ? 'checked' : ''}}
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="active"
                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('Is active') }}</label>
                        <x-input-error :messages="$errors->get('active')" class="mt-2" />
                    </div>
                    <div>
                        <input id="games" name="games" type="checkbox" value="{{$game_content_type->id}}" {{$has_games ? 'checked' : ''}}
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="games"
                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('Has games') }}</label>
                        <x-input-error :messages="$errors->get('games')" class="mt-2" />
                    </div>
                    <div>
                        <input id="tickets" name="tickets" type="checkbox" value="{{$tickets_content_type->id}}" {{$has_tickets ? 'checked' : ''}}
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="tickets"
                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('Has tickets') }}</label>
                        <x-input-error :messages="$errors->get('tickets')" class="mt-2" />
                    </div>
                    <div>
                        <input id="coupons" name="coupons" type="checkbox" value="{{$coupons_content_type->id}}" {{$has_coupons ? 'checked' : ''}}
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="coupons"
                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('Has coupons') }}</label>
                        <x-input-error :messages="$errors->get('coupons')" class="mt-2" />
                    </div>
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
                        <input id="init_date" name="init_date" value="{{ old('init_date', $campaign->init_date) }}" type="text" 
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
                        <input id="end_date" name="end_date" value="{{ old('end_date', $campaign->end_date) }}" 
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

                <div class="mt-5">
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>

    @if (!is_null($campaign->id))
    <div class="py-6 mx-5">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 bg-white p-3 rounded shadow">
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-5">
                    {{ __('Welcome Page') }}
                </h2>
                @if (is_null($campaign->campaign_splash_page))
                    <a href="{{ route('campaign_splash_page.create', ['tenant' => tenant('id'), 'campaign' => $campaign->id]) }}"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        {{ __('Create') }} {{ __('Welcome Page') }}
                    </a>
                @endif

            </div>
            @if (!is_null($campaign->campaign_splash_page))
                <div class="relative overflow-x-auto rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead
                            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Instructions') }}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4">
                                    {!! $campaign->campaign_splash_page->instructions !!}
                                </th>
                                <td class="px-6 py-4">
                                    <a href="{{ route('campaign_splash_page.edit', ['tenant' => tenant('id'), 'campaign_splash_page' => $campaign->campaign_splash_page]) }}"
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
    @endsection
</x-panel-layout>
