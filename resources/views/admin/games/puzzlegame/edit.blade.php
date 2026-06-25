@extends('layouts.v2.app')

<x-slot name="title">
    {{ !is_null($puzzle->title) ? $puzzle->title : trans('Create') . '' . trans('Puzzle Game') }}
</x-slot>
<x-slot name="description">
    {{ $puzzle->id == null ? '' : $puzzle->description }}
</x-slot>
<x-slot name="header">
    <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
    {{ __('Content types') }}
    </h1>
</x-slot>
@section('content')
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">
            {{ $puzzle->id == null ? trans('Create') : trans('Edit') }} {{ __('Puzzle Game') }}
            </h2>
            <nav>
            <ol class="flex items-center gap-1.5">
                <li>
                <a class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400" href="{{ route('welcome', ['tenant' => tenant('id')]) }}">
                Home
                <svg class="stroke-current" width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366" stroke="" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                </a>
                </li>
                <li class="text-sm text-gray-800 dark:text-white/90">
                {{ !is_null($puzzle->title) ? $puzzle->title : trans('Create') . '' . trans('Puzzle Game') }}
                </li>
            </ol>
            </nav>
        </div>
        <div class="space-y-6">
            @if (session('status'))
                <x-v2.ui.alert
                variant="success"
                title="{{ session('status') }}"
                :showLink="false"
                />
            @endif
            <form id="form-campaign" method="POST" enctype="multipart/form-data"
            action="{{ $puzzle->id == null ? route('puzzlegames.store', ['tenant' => tenant('id')]) : route('puzzlegames.update', ['tenant' => tenant('id'), 'puzzlegame' => $puzzle]) }}">
            <div class="mb-6 flex flex-col justify-between gap-6 rounded-2xl border border-gray-200 bg-white px-6 py-5 sm:flex-row sm:items-center dark:border-gray-800 dark:bg-white/3">
                <div class="flex flex-col gap-2.5 divide-gray-300 sm:flex-row sm:divide-x dark:divide-gray-700">
                    <div class="flex items-center gap-2 sm:pr-3">
                        @if ($puzzle->active)
                            <span class="bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500 inline-flex items-center justify-center gap-1 rounded-full px-2.5 py-0.5 text-sm font-medium">Active</span>
                        @endif
                    </div>
                    @if (isset($puzzle->is_expired) && $puzzle->is_expired)
                        <p class="text-sm text-gray-500 sm:pl-3 dark:text-gray-400">Expires At:&nbsp;<strong>{{ $puzzle->only_date }}</strong></p>
                    @endif
                </div>
                <div class="flex items-center gap-3 mt-6 lg:justify-end">
                    <button type="button" aria-label="{{ __('Close modal') }}"
                    class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">
                    {{ __('Close') }}
                    </button>
                    <button type="submit" aria-label="{{ __('Save changes') }}"
                    class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 disabled:opacity-50 disabled:cursor-not-allowed sm:w-auto transition-opacity">
                    <span >{{ __('Save Changes') }}</span>
                    </button>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
                <div class="lg:col-span-8 2xl:col-span-9">
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/3">
                        <div class="px-2 overflow-y-auto ">
                            <div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2">
                                @csrf
                                @isset($puzzle->id)
                                @method('PATCH')
                                <input type="hidden" name="id" value="{{ $puzzle->id }}">
                                @endisset
                                <input type="hidden" name="content_type_id" value="{{ $content_type->id }}">
                                <h2 class="mt-6 text-lg col-span-2 font-semibold text-gray-800 dark:text-white/90">Game Details</h2>
                                <x-ui.forms.input-select label="{{ __('Campaign') }}" :options="$campaigns" name="campaign_id" placeholder="" :value="($puzzle->campaign->id ?? null)" data-field="campaign.campaign_id" />
                                <x-ui.forms.input-text label="{{ __('Title') }}" name="title" placeholder="" :value="$puzzle->title" data-field="campaign.title" />
                                <x-ui.forms.input-text label="{{ __('Description') }}" cols="2" name="description" placeholder="" :value="$puzzle->description" data-field="campaign.description" /><x-ui.forms.input-file label="{{ __('Image On') }}" dummy_img="/storage/dummy_assets/600x200.png" name="featured_image" placeholder="" :value="$puzzle->featured_image" data-field="campaign.featured_image" />
                                <x-ui.forms.input-file label="{{ __('Image Off') }}" dummy_img="/storage/dummy_assets/600x200.png" name="featured_image_disabled" placeholder="" :value="$puzzle->featured_image_disabled" data-field="campaign.featured_image_disabled" />
                                <h2 class="mt-6 text-lg col-span-2 font-semibold text-gray-800 dark:text-white/90">Top Banner Settings</h2>
                                <x-ui.forms.input-file label="{{ __('Top Banner') }}" dummy_img="/storage/dummy_assets/600x200.png" name="game_banner" placeholder="" :value="$puzzle->game_banner" data-field="campaign.game_banner" />
                                <x-ui.forms.input-text label="{{ __('Banner URL (Image)') }}" name="game_banner_url" placeholder="" :value="$puzzle->game_banner_url" data-field="campaign.game_banner_url" />
                                <x-ui.forms.input-text label="{{ __('Video') }}" name="game_banner_video" placeholder="" :value="$puzzle->game_banner_video" data-field="campaign.game_banner_video" />
                                <h2 class="mt-6 text-lg col-span-2 font-semibold text-gray-800 dark:text-white/90">Game Settings</h2>
                                <x-ui.forms.input-text label="{{ __('Slug') }}" name="slug" placeholder="" :value="$puzzle->slug" data-field="campaign.slug" />
                                <x-ui.forms.input-number label="{{ __('Seconds') }}" name="seconds" placeholder="" :value="$puzzle->seconds" data-field="campaign.seconds" />
                                <x-ui.forms.input-number label="{{ __('Pieces') }}" name="pieces" placeholder="" :value="$puzzle->pieces" data-field="campaign.pieces" />
                                <x-ui.forms.input-file label="{{ __('Failed Image') }}" dummy_img="/storage/dummy_assets/800x1180.png" name="failed_image" placeholder="" :value="$puzzle->failed_image" data-field="campaign.failed_image" />
                                <x-ui.forms.input-file label="{{ __('Puzzle Image') }}" dummy_img="/storage/dummy_assets/800x1180.png" name="puzzle_image" placeholder="" :value="$puzzle->puzzle_image" data-field="campaign.puzzle_image" />
                            </div>
                        </div>

                    </div>
                    @if (!is_null($puzzle->id))
                        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/3 mt-6">
                            <div class="flex justify-between">
                                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-5">
                                {{ __('Award') }}
                                </h2>
                                @if (is_null($puzzle->award))
                                    <a href="{{ route('v2awards.create', ['tenant' => tenant('id'), 'awardable_id' => $puzzle->id, 'awardable_type' => 'App\Models\Puzzle' ]) }}"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                    {{ __('Create') }} {{ __('Award') }}
                                    </a>
                                @endif

                            </div>
                            @if (!is_null($puzzle->award))
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
                                        {!!$puzzle->award->title!!}
                                        </th>
                                        <td class="px-6 py-4">
                                        <a href="{{ route('v2awards.edit', ['tenant' => tenant('id'), 'v2award' => $puzzle->award]) }}"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ __('Edit') }}</a>
                                        </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
            <div class="space-y-6 lg:col-span-4 2xl:col-span-3">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/3">
                    <h2 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90">Date Configuration</h2>
                    <x-ui.forms.input-datetime label="{{ __('Select Start Date') }}" iddatepicker="datepickerInitDate" class="mb-3" name="init_date" placeholder="{{ __('Start Date') }}" :value="$puzzle->init_date" data-field="campaign.init_date"/>
                    <x-ui.forms.input-datetime label="{{ __('Select End Date') }}" iddatepicker="datepickerEndDate" name="end_date" placeholder="{{ __('End Date') }}" :value="$puzzle->end_date" data-field="campaign.end_date"/>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/3 space-y-3">
                    <h2 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90">Button Settings</h2>
                    <x-ui.forms.input-color label="{{ __('Gradient Button Background 1') }}" name="btn_background_color_1" placeholder="" :value="$puzzle->btn_background_color_1" data-field="campaign.btn_background_color_1" />
                    <x-ui.forms.input-color label="{{ __('Gradient Button Background 2') }}" name="btn_background_color_2" placeholder="" :value="$puzzle->btn_background_color_2" data-field="campaign.btn_background_color_2" />
                    <x-ui.forms.input-color label="{{ __('Button Border Color') }}" name="btn_border_color" placeholder="" :value="$puzzle->btn_border_color" data-field="campaign.btn_border_color" />
                    <x-ui.forms.input-color label="{{ __('Button Text Color') }}" name="btn_text_color" placeholder="" :value="$puzzle->btn_text_color" data-field="campaign.btn_text_color" />
                    <x-ui.forms.input-switch label="{{ __('Has shadow') }}" name="btn_shadow" placeholder="" :value="$puzzle->btn_shadow" data-field="campaign.btn_shadow" />
                        <x-ui.forms.input-switch label="{{ __('Has Border') }}" name="btn_border" placeholder="" :value="$puzzle->btn_border" data-field="campaign.btn_border" />
                    <x-ui.forms.input-text label="{{ __('Text Active') }}" name="btn_text_active" placeholder="" :value="$puzzle->btn_text_active ?? 'Jugar Ahora'" data-field="campaign.btn_text_active" />
                    <x-ui.forms.input-switch label="{{ __('Enable Button Shadow') }}" name="btn_enable_shadow" placeholder="" :value="$puzzle->btn_enable_shadow" data-field="campaign.btn_enable_shadow" />
                    <x-ui.forms.input-text label="{{ __('Text Inactive') }}" name="btn_text_inactive" placeholder="" :value="$puzzle->btn_text_inactive ?? 'Ver Resultado'" data-field="campaign.btn_text_inactive" />
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/3">
                    <h2 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90">Card Settings</h2>
                    <x-ui.forms.input-color label="{{ __('Gradient Background 1') }}" name="gradient_1" placeholder="" :value="$puzzle->gradient_1" data-field="campaign.gradient_1" />
                    <x-ui.forms.input-color label="{{ __('Gradient Background 2') }}" name="gradient_2" placeholder="" :value="$puzzle->gradient_2" data-field="campaign.gradient_2" />
                </div>
            </div>
        </div>
    </form>
</div>
</div>
{{-- <script>
document.addEventListener('DOMContentLoaded', function() {
const dates = document.querySelectorAll('[type="datetime-local"]');
dates.forEach(dateInput => {
const value = dateInput.value
if (value) {
dateInput.value = fromBackendToDatetimeLocal(value);
}
});
});

function fromBackendToDatetimeLocal(value) {
const date = new Date(value.replace(' ', 'T'));
return this.formatDateTimeLocal(date);
}
function formatDateTimeLocal(date = new Date()) {
const pad = n => String(n).padStart(2, '0');
return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
}
</script>   --}}
<script>

function setDateTimeFromPicker(target) {
// Get the selected date from the datepicker
const datepickerElement = document.getElementById(`datepicker-${target}`);
const selectedDate = datepickerElement.datepicker.getDate();

var date = new Date(selectedDate);
var dateString = date.toISOString().split('T')[0];
// Get the selected time from the timetable
const inputElement = document.getElementById(target);
const selectedTimeRadio = document.querySelector(`input[name="timetable-${target}"]:checked`);
const selectedTimeLabel = selectedTimeRadio ? selectedTimeRadio.nextElementSibling.textContent.trim() : null;

// Set the value in the main input
if (dateString && selectedTimeLabel) {
const dateTimeString = `${dateString} ${selectedTimeLabel}:00`;
inputElement.value = dateTimeString;
inputElement.dispatchEvent(new Event('change', { bubbles: true }));
}
}

function saveDateTimePicker(e) {
setDateTimeFromPicker(e.dataset.targetCalendar);
// Close the modal after saving
const modal = document.getElementById('timepicker-modal');
const modalToggleBtn = modal.querySelector('[data-modal-hide="timepicker-modal"]');
if (modalToggleBtn) {
modalToggleBtn.click();
}
}
</script>
@endsection

