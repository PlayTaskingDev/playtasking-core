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
            data-modal-target="campaign-modal"
            data-modal-toggle="campaign-modal"
            data-save-route="{{ route('campaigns.store', tenant('id')) }}"
            class="btn bg-brand-500 hover:bg-brand-600 flex w-full justify-center rounded-lg px-4 py-2.5 text-sm font-medium text-white sm:w-auto" aria-label="{{ __('Add new campaign') }}">
                {{ __('Add Campaign +') }}
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
                @foreach ($campaigns as $campaign)
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <td class="px-5 py-4 sm:px-6" colspan="1">
                            <div class="flex items-center gap-3">
                                <div class="w-lg">
                                    <span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90" >{{ $campaign->name }}</span>
                                    <span class="block text-gray-500 text-theme-sm dark:text-gray-400" >{{ $campaign->description }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center gap-3">
                                <span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90" >{{ $campaign->only_date }}</span>
                            </div>
                        </td>
                        
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center gap-3">
                                <span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90" >{{ $campaign->active ? trans('Yes') : trans('No') }}</span>     
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <button 
                            data-action="edit"
                            data-modal-target="campaign-modal"
                            data-modal-toggle="campaign-modal"
                            data-edit-route="{{ route('campaigns.edit', [tenant('id'), $campaign]) }}"
                            data-save-route="{{ route('campaigns.update', [tenant('id'), $campaign]) }}"
                            class="edit-button inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 rounded-lg transition-colors" 
                                aria-label="{{ __('Edit') }} {{ $campaign->name }}"
                            >
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M15.0911 2.78206C14.2125 1.90338 12.7878 1.90338 11.9092 2.78206L4.57524 10.116C4.26682 10.4244 4.0547 10.8158 3.96468 11.2426L3.31231 14.3352C3.25997 14.5833 3.33653 14.841 3.51583 15.0203C3.69512 15.1996 3.95286 15.2761 4.20096 15.2238L7.29355 14.5714C7.72031 14.4814 8.11172 14.2693 8.42013 13.9609L15.7541 6.62695C16.6327 5.74827 16.6327 4.32365 15.7541 3.44497L15.0911 2.78206ZM12.9698 3.84272C13.2627 3.54982 13.7376 3.54982 14.0305 3.84272L14.6934 4.50563C14.9863 4.79852 14.9863 5.2734 14.6934 5.56629L14.044 6.21573L12.3204 4.49215L12.9698 3.84272ZM11.2597 5.55281L5.6359 11.1766C5.53309 11.2794 5.46238 11.4099 5.43238 11.5522L5.01758 13.5185L6.98394 13.1037C7.1262 13.0737 7.25666 13.003 7.35947 12.9002L12.9833 7.27639L11.2597 5.55281Z"
                                        fill="" />
                                </svg>
                                {{ __('Edit') }}
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </x-v2.common.component-card>
</div>

<div id="campaign-modal" tabindex="-1"
     class="hidden fixed inset-0 z-9999  items-center justify-center bg-black/50">

    <div class="relative w-full max-w-3xl rounded-3xl bg-white p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 id="campaign-modal-title"
                class="text-2xl font-semibold text-gray-800">
                Nueva Campaña
            </h3>
            <button data-modal-hide="campaign-modal" class="text-gray-400 hover:text-gray-600">
                ✕
            </button>
        </div>
        <form id="form-campaign" method="POST" enctype="multipart/form-data">
                <div class="px-2 overflow-y-auto custom-scrollbar h-[510px]">
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2">
                        @csrf
                        <input type="hidden" name="_method" value="POST" id="method-field">
                        <input type="hidden" name="id" data-field="campaign.id">
                        <input type="hidden" name="delete_image_holder_hidden" value="0">
                        <x-ui.forms.input-text label="{{ __('Campaign Name') }}" name="name" placeholder="" value="" data-field="campaign.name" />
                        <x-ui.forms.input-text label="{{ __('Description') }}" name="description" placeholder="" value="" data-field="campaign.description" />
                        <x-ui.forms.input-text cols="2" label="{{ __('Resource') }}" name="slug" placeholder="Resource" value="" data-field="campaign.slug" />
                        <x-ui.forms.input-switch label="{{ __('Active') }}" name="active" data-field="campaign.active" switcher="0"/>
                        <x-ui.forms.input-switch label="{{ __('Games') }}" name="games" data-field="game_content_type.id" switcher="0"/>
                        <x-ui.forms.input-switch label="{{ __('Tickets') }}" name="tickets" data-field="game_content_type.id" switcher="0"/>
                        <x-ui.forms.input-switch label="{{ __('Coupons') }}" name="coupons" data-field="coupons_content_type.id" switcher="0"/>
                        <x-ui.forms.input-datetime label="{{ __('Select Start Date') }}" name="init_date" placeholder="{{ __('Start Date') }}" value="" data-field="campaign.init_date"/>
                        <x-ui.forms.input-datetime label="{{ __('Select End Date') }}" name="end_date" placeholder="{{ __('End Date') }}" value="" data-field="campaign.end_date"/>
                        <x-ui.forms.input-area-tinymce cols="2" label="{{ __('Instrucciones') }}" name="instructions" value="" />
                        <x-ui.forms.input-file label="{{ __('Image') }}" name="featured_image_url" value=""  placeholder="" />
                        <x-ui.forms.input-text isvideo="true" label="{{ __('Video') }}" name="featured_video_url" placeholder="" value="" data-field="campaign.campaign_splash_page.featured_video_url" />
                    </div>
                </div>
                <div class="flex items-center gap-3 mt-6 lg:justify-end">
                    <button @click="open = false" type="button" aria-label="{{ __('Close modal') }}"
                        class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">
                        {{ __('Close') }}
                    </button>
                    <button type="submit" aria-label="{{ __('Save changes') }}" 
                        class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 disabled:opacity-50 disabled:cursor-not-allowed sm:w-auto transition-opacity">
                        <span >{{ __('Save Changes') }}</span>
                    </button>
                </div>
            </form>
    </div>
</div>

<script>

</script>

<x-footer.tinymce-config/>

@endsection
