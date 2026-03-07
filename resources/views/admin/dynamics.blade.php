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
    <x-v2.common.page-breadcrumb pageTitle="{{ $title }}" desc="Edita y personaliza cada dinámica." />
    <div class="space-y-6">
        <x-v2.common.component-card>
            <div class="max-w-full overflow-x-auto custom-scrollbar">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                        @foreach (['Dynamic', 'Icon On', 'Icon Off', 'Colors', 'Actions'] as $header)
                            <th class="px-5 py-3 text-left sm:px-6" scope="col">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                            {{ __($header) }}
                            </p>
                            </th>
                        @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($content_types as $type)
                            <tr class="border-b border-gray-100 dark:border-gray-800">
                            <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center gap-3">
                                <div class="">
                                    <span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90" >{{ $type->name }}</span>
                                    <span class="block text-gray-500 text-theme-xs dark:text-gray-400" >{{ $type->description }}</span>
                                </div>
                            </div>
                            </td>
                            <td class="px-5 py-4 sm:px-6">
                            <div class="w-14 h-14 overflow-hidden rounded-full bg-gray-300 flex items-center justify-center">
                                <img src="{{ $type->icon_active }}" alt="{{ $type->name }} active icon" loading="lazy" class="w-8 h-8 object-cover">
                            </div>
                            </td>
                            <td class="px-5 py-4 sm:px-6">
                            <div class="w-14 h-14 overflow-hidden rounded-full bg-gray-300 flex items-center justify-center">
                                <img src="{{ $type->icon }}" alt="{{ $type->name }} icon" loading="lazy" class="w-8 h-8 object-cover">
                            </div>
                            </td>
                            <td class="px-5 py-4 sm:px-6">
                            <div class="flex justify-between gap-3">
                                @foreach ([$type->gradient_1, $type->gradient_2] as $color)
                                    <div class="flex items-center gap-2">
                                        <span class="w-5 h-5 rounded-full flex-shrink-0 border border-gray-300 dark:border-gray-600" style="background-color: {{ $color }};" title="{{ $color }}" aria-label="Color: {{ $color }}"></span>
                                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ $color }}</span>
                                    </div>
                                @endforeach
                            </div>
                            </td>
                            <td class="px-5 py-4 sm:px-6">
                            <button
                            class="border border-black inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-black   dark:bg-blue-700 dark:hover:bg-blue-800 rounded-lg transition-colors"
                            @click="$dispatch('open-dynamic-modal', { id: '{{ $type->id }}', editRoute: '{{ route('dynamics.edit', ['tenant' => tenant('id'), 'dynamic' => $type]) }}', saveRoute: '{{ $type->id == null ? route('dynamics.store', ['tenant' => tenant('id')]) : route('dynamics.update', ['tenant' => tenant('id'), 'dynamic' => $type]) }}' })"
                            aria-label="{{ __('Edit') }} {{ $type->name }}"
                            >
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M15.0911 2.78206C14.2125 1.90338 12.7878 1.90338 11.9092 2.78206L4.57524 10.116C4.26682 10.4244 4.0547 10.8158 3.96468 11.2426L3.31231 14.3352C3.25997 14.5833 3.33653 14.841 3.51583 15.0203C3.69512 15.1996 3.95286 15.2761 4.20096 15.2238L7.29355 14.5714C7.72031 14.4814 8.11172 14.2693 8.42013 13.9609L15.7541 6.62695C16.6327 5.74827 16.6327 4.32365 15.7541 3.44497L15.0911 2.78206ZM12.9698 3.84272C13.2627 3.54982 13.7376 3.54982 14.0305 3.84272L14.6934 4.50563C14.9863 4.79852 14.9863 5.2734 14.6934 5.56629L14.044 6.21573L12.3204 4.49215L12.9698 3.84272ZM11.2597 5.55281L5.6359 11.1766C5.53309 11.2794 5.46238 11.4099 5.43238 11.5522L5.01758 13.5185L6.98394 13.1037C7.1262 13.0737 7.25666 13.003 7.35947 12.9002L12.9833 7.27639L11.2597 5.55281Z" />
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
    <div x-data="dynamicModal()" class="dynamic-modal-container">
        <x-v2.ui.modal x-data="{ open: false }" @open-dynamic-modal.window="handleOpenModal($event)" :isOpen="false" class="max-w-[700px]">
        <div
        class="no-scrollbar relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11">
        <div class="px-2 pr-14">
            <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90" x-text="dynamicData ? dynamicData.name : 'Loading...'">
            Editar Dinamica
            </h4>
            <p class="mb-6 text-sm text-gray-500 dark:text-gray-400 lg:mb-7" >
            Update details of dynamic.
            </p>
        </div>
        <form :action="saveRoute" class="flex flex-col" id="form-dynamic" method="POST" enctype="multipart/form-data" @submit.prevent="handleSubmit">
            <div class="px-2 overflow-y-auto custom-scrollbar h-[510px]">
                <div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2" >
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="id" x-model="dynamicData.id">
                    <input type="hidden" name="delete_image_holder_hidden" value="0">
                    <x-ui.forms.input-text label="Nombre de la dinamica" name="name" placeholder="Juegos" value="" x-model="dynamicData.name" />
                    <x-ui.forms.input-text label="Descripción" name="description" placeholder="" value=""  x-model="dynamicData.description" />
                    <x-ui.forms.input-file label="Menu icon" name="icon" value=""  placeholder=""  />
                    <x-ui.forms.input-file label="Menu icon (active)" name="icon_active" value=""  placeholder=""   />
                    <x-ui.forms.input-color label="Gradient Background 1" name="gradient_1" value="" x-model="dynamicData.gradient_1" />
                    <x-ui.forms.input-color label="Gradient Background 2" name="gradient_2" value="" x-model="dynamicData.gradient_2" />
                    <x-ui.forms.input-file label="Section banner" name="section_banner" value=""  placeholder=""  />
                    <x-ui.forms.input-text label="Banner URL (Image)" name="game_banner_url" placeholder="" value=""  x-model="dynamicData.game_banner_url" />
                    <x-ui.forms.input-text label="Video" name="game_banner_video" placeholder="" value=""  x-model="dynamicData.game_banner_video" />
                </div>
            </div>
            <div class="flex items-center gap-3 mt-6 lg:justify-end">
                <button @click="open = false" type="button" aria-label="Close modal"
                class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">
                {{ __('Close') }}
                </button>
                <button type="submit" aria-label="Save changes" :disabled="isSubmitting"
                class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 disabled:opacity-50 disabled:cursor-not-allowed sm:w-auto transition-opacity">
                <span x-show="!isSubmitting">{{ __('Save Changes') }}</span>
                <span x-show="isSubmitting">{{ __('Saving...') }}</span>
                </button>
            </div>
        </form>
    </div>
    </x-v2.ui.modal>
</div>


<script>
function dynamicModal() {
return {
dynamicData: {
id: null,
name: null,
description: null,
icon: null,
icon_active: null,
gradient_1: null,
gradient_2: null,
game_banner_url: null,
game_banner_video: null,
section_banner: null,
},
saveRoute: null,
isSubmitting: false,

handleOpenModal(event) {
this.open = true;
const detail = event.detail;
this.saveRoute = detail.saveRoute;
const editRoute = detail.editRoute;

axios.get(editRoute)
.then(response => {
this.dynamicData = response.data.data;
this.updateImageDisplays();
})
.catch(error => {
console.error('Error fetching dynamic data:', error);
this.showErrorNotification('{{ __('Error loading data') }}');
});
},

updateImageDisplays() {
const img = document.getElementById('img-icon');
if (img) img.src = this.dynamicData.icon;

const imgActive = document.getElementById('img-icon_active');
if (imgActive) imgActive.src = this.dynamicData.icon_active;

const imgBanner = document.getElementById('img-section_banner');
if (imgBanner) imgBanner.src = this.dynamicData.section_banner;

const colorEl = document.getElementById('color-gradient_1');
if (colorEl) colorEl.style.backgroundColor = this.dynamicData.gradient_1;

const gradientInput = document.getElementById('gradient_1');
if (gradientInput) gradientInput.value = this.dynamicData.gradient_1;
},

handleSubmit() {
this.isSubmitting = true;
document.getElementById('form-dynamic').submit();
},

showErrorNotification(message) {
console.error(message);
}
};
}
</script>
@endsection

