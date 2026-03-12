@extends('layouts.v2.app')
<x-slot name="title">
    {{ !is_null($campaign->name) ? $campaign->name : trans('Create') . ' ' . trans('Campaign') }}
</x-slot>
<x-slot name="description">
    {{ $campaign->id == null ? '' : $campaign->description }}
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
            {{ $campaign->id == null ? trans('Create') : trans('Edit') }} {{ __('Campaign') }}
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
                {{ !is_null($campaign->title) ? $campaign->title : trans('Create') . '' . trans('Campaign') }}
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
            action="{{ $campaign->id == null ? route('campaigns.store', ['tenant' => tenant('id')]) : route('campaigns.update', ['tenant' => tenant('id'), 'campaign' => $campaign]) }}">
             <div class="mb-6 flex flex-col justify-between gap-6 rounded-2xl border border-gray-200 bg-white px-6 py-5 sm:flex-row sm:items-center dark:border-gray-800 dark:bg-white/3">
                <div class="flex flex-col gap-2.5 divide-gray-300 sm:flex-row sm:divide-x dark:divide-gray-700">
                    <div class="flex items-center gap-2 sm:pr-3">
                        @if ($campaign->active)
                            <span class="bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500 inline-flex items-center justify-center gap-1 rounded-full px-2.5 py-0.5 text-sm font-medium">Active</span>
                        @endif
                    </div>

                    @if (isset($campaign->is_expired) && $campaign->is_expired)
                        <p class="text-sm text-gray-500 sm:pl-3 dark:text-gray-400">Expires At:&nbsp;<strong>{{ $campaign->only_date }}</strong></p>
                    @endif
                    
                </div>
                    <div class="flex items-center gap-3 mt-6 lg:justify-end">
                        <button type="submit" aria-label="{{ __('Save changes') }}"
                        class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 disabled:opacity-50 disabled:cursor-not-allowed sm:w-auto transition-opacity">
                        <span >{{ !is_null($campaign->name) ? __('Save Changes') : __('Create Game') }}</span>
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
                    <div class="lg:col-span-8 2xl:col-span-9">
                        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/3">
                            <div class="px-2 overflow-y-auto ">
                                <div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2">
                                @csrf
                                @isset($campaign->id)
                                    @method('PATCH')
                                    <input type="hidden" name="id" value="{{ $campaign->id }}">
                                @endisset
                                <x-ui.forms.input-text label="{{ __('Campaign Name') }}" name="name" placeholder="" :value="$campaign->name" data-field="campaign.name" />
                                <x-ui.forms.input-text  label="{{ __('Slug') }}" name="slug" placeholder="Slug" :value="$campaign->slug" data-field="campaign.slug" />
                                <x-ui.forms.input-text cols="2" label="{{ __('Description') }}" name="description" placeholder="" :value="$campaign->description" data-field="campaign.description" />
                                <x-ui.forms.input-switch label="{{ __('Active') }}" name="active" value="1" data-field="campaign.active" :switcher="$campaign->active"/>
                                <x-ui.forms.input-switch label="{{ __('Games') }}" name="games" :value="$game_content_type->id" data-field="has_coupons" :switcher="$has_games"/>
                                <x-ui.forms.input-switch label="{{ __('Tickets') }}" name="tickets" :value="$tickets_content_type->id" data-field="has_games" :switcher="$has_tickets"/>
                                <x-ui.forms.input-switch label="{{ __('Coupons') }}" name="coupons" :value="$coupons_content_type->id" data-field="has_tickets" :switcher="$has_coupons"/>
                                <x-ui.forms.input-area-tinymce cols="2" label="{{ __('Instrucciones') }}" name="instructions" value="{!! $campaign->campaign_splash_page ? $campaign->campaign_splash_page->instructions : '' !!}" data-field="campaign.campaign_splash_page.instructions" />
                                <x-ui.forms.input-file isimg="true" data-is-img="true" label="{{ __('Image') }}" name="featured_image_url" :value="$campaign->campaign_splash_page ? $campaign->campaign_splash_page->featured_image_url : ''"  placeholder="" data-field="campaign.campaign_splash_page.featured_image_url" />
                                <x-ui.forms.input-text isvideo="true" data-is-video="true" label="{{ __('Video') }}" name="featured_video_url" placeholder="" :value="$campaign->campaign_splash_page ? $campaign->campaign_splash_page->featured_video_url : ''"  data-field="campaign.campaign_splash_page.featured_video_url" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-6 lg:col-span-4 2xl:col-span-3">
                        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/3">
                            <h2 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90">Date Configuration</h2>
                            <x-ui.forms.input-datetime label="{{ __('Select Start Date') }}" iddatepicker="datepickerInitDate" class="mb-3" name="init_date" placeholder="{{ __('Start Date') }}" :value="$campaign->init_date" data-field="campaign.init_date"/>
                            <x-ui.forms.input-datetime label="{{ __('Select End Date') }}" iddatepicker="datepickerEndDate" name="end_date" placeholder="{{ __('End Date') }}" :value="$campaign->end_date" data-field="campaign.end_date"/>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<x-footer.tinymce-config/>

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

