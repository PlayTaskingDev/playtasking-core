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
            <button @click="$dispatch('open-aplazogame-modal', { isNew: true })" class="btn bg-brand-500 hover:bg-brand-600 flex w-full justify-center rounded-lg px-4 py-2.5 text-sm font-medium text-white sm:w-auto" aria-label="{{ __('Add new aplazogame') }}">
                {{ __('Add A Plazo Game +') }}
            </button>
        </div>
    <x-v2.common.component-card title="{{ $title }}">
        <div class="max-w-full overflow-x-auto custom-scrollbar">
        <table class="w-full ">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800">
                    @foreach (['A Plazo game', 'Expiration', 'Active', 'Actions'] as $header)
                        <th class="px-5 py-3 text-left sm:px-6" scope="col">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                {{ __($header) }}
                            </p>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($aplazo_games as $aplazogame)
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <td class="px-5 py-4 sm:px-6" colspan="1">
                            <div class="flex items-center gap-3">
                                <div class="w-lg">
                                    <span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90" >{{ $aplazogame->name }}</span>
                                    <span class="block text-gray-500 text-theme-sm dark:text-gray-400" >{{ $aplazogame->description }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center gap-3">
                                <span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90" >{{ $aplazogame->only_date }}</span>
                            </div>
                        </td>
                        
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center gap-3">
                                <span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90" >{{ $aplazogame->active ? trans('Yes') : trans('No') }}</span>     
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <button class="edit-button inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 rounded-lg transition-colors" 
                                @click="$dispatch('open-aplazogame-modal', { editRoute: '{{ route('aplazogames.edit', ['tenant' => tenant('id'), 'aplazogame' => $aplazogame]) }}', saveRoute: '{{ $aplazogame->id == null ? route('aplazogames.store', ['tenant' => tenant('id')]) : route('aplazogames.update', ['tenant' => tenant('id'), 'aplazogame' => $aplazogame]) }}' })"
                                aria-label="{{ __('Edit') }} {{ $aplazogame->name }}"
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
<div x-data="campaignModal()" class="aplazogame-modal-container">
    <x-v2.ui.modal x-data="{ open: false }" @open-aplazogame-modal.window="handleOpenModal($event)" :isOpen="false" class="max-w-[700px]">
        <div
            class="no-scrollbar relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11">
            <div class="px-2 pr-14">
                <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90" x-text="campaignData.aplazogame.name || 'Nueva Campaña'">
                    Nueva Campaña
                </h4>
                <p class="mb-6 text-sm text-gray-500 dark:text-gray-400 lg:mb-7">
                    {{ __('Update details of aplazogame.') }}
                </p>
            </div>
            <form :action="saveRoute" class="flex flex-col" id="form-aplazogame" method="POST" enctype="multipart/form-data" @submit.prevent="handleSubmit">
                <div class="px-2 overflow-y-auto custom-scrollbar h-[510px]">
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="id" x-model="campaignData.aplazogame.id">
                        <input type="hidden" name="delete_image_holder_hidden" value="0">
                        <x-ui.forms.input-text label="{{ __('Campaign Name') }}" name="name" placeholder="" value="" x-model="campaignData.aplazogame.name" />
                        <x-ui.forms.input-text label="{{ __('Description') }}" name="description" placeholder="" value="" x-model="campaignData.aplazogame.description" />
                        <x-ui.forms.input-text cols="2" label="{{ __('Resource') }}" name="slug" placeholder="Resource" value="" x-model="campaignData.aplazogame.slug" />
                        <x-ui.forms.input-switch label="{{ __('Active') }}" name="active" model="campaignData.aplazogame.active" switcher="0"/>
                        <x-ui.forms.input-switch label="{{ __('Games') }}" name="games" model="campaignData.game_content_type.id" switcher="0"/>
                        <x-ui.forms.input-switch label="{{ __('Tickets') }}" name="tickets" model="campaignData.game_content_type.id" switcher="0"/>
                        <x-ui.forms.input-switch label="{{ __('Coupons') }}" name="coupons" model="campaignData.coupons_content_type.id" switcher="0"/>
                        <x-ui.forms.input-datetime label="{{ __('Select Start Date') }}" name="init_date" placeholder="{{ __('Start Date') }}" value="" x-model="campaignData.aplazogame.init_date"/>
                        <x-ui.forms.input-datetime label="{{ __('Select End Date') }}" name="end_date" placeholder="{{ __('End Date') }}" value="" x-model="campaignData.aplazogame.end_date"/>
                        <x-ui.forms.input-area-tinymce cols="2" label="{{ __('Instrucciones') }}" name="instructions" value="" />
                        <x-ui.forms.input-file label="{{ __('Image') }}" name="featured_image_url" value=""  placeholder="" />
                        <x-ui.forms.input-text isvideo="true" label="{{ __('Video') }}" name="featured_video_url" placeholder="" value="" x-model="campaignData.aplazogame.campaign_splash_page.featured_video_url" />
                    </div>
                </div>
                <div class="flex items-center gap-3 mt-6 lg:justify-end">
                    <button @click="open = false" type="button" aria-label="{{ __('Close modal') }}"
                        class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">
                        {{ __('Close') }}
                    </button>
                    <button type="submit" aria-label="{{ __('Save changes') }}" :disabled="isSubmitting"
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
    function campaignModal() {
        return {
            campaignData: {
                aplazogame: {
                    id: null,
                    name: null,
                    description: null,
                    init_date: null,
                    end_date: null,
                    active: null,
                    slug: null,
                    created_at: null,
                    updated_at: null,
                    only_date: null,
                    content_types: null,
                    campaign_splash_page: {
                        id: null,
                        campaign_id: null,
                        featured_video_url: null,
                        featured_image_url: null,
                        instructions: null
                    },
                },
                has_coupons: false,
                has_games: false,
                has_tickets: false,
                coupons_content_type:{
                    id: null
                },
                game_content_type:{
                    id: null
                },
                tickets_content_type:{
                    id: null
                }
                
            },
            saveRoute: null,
            isSubmitting: false,
            
            handleOpenModal(event) {
                this.open = true;
                const detail = event.detail;
                const isNew = detail.isNew || false;
                
                if (isNew) {
                    // Nueva campaña - limpiar formulario
                    this.resetFormData();
                    this.saveRoute = '{{ route('aplazogames.store', ['tenant' => tenant('id')]) }}';
                } else {
                    // Editar campaña existente
                    this.saveRoute = detail.saveRoute;
                    const editRoute = detail.editRoute;
                    this.fetchCampaignData(editRoute);
                }
            },
            
            resetFormData() {
                this.campaignData = {
                    aplazogame: {
                        id: null,
                        name: null,
                        description: null,
                        init_date: null,
                        end_date: null,
                        active: false,
                        slug: null,
                        created_at: null,
                        updated_at: null,
                        only_date: null,
                        content_types: null,
                        campaign_splash_page: {
                            id: null,
                            campaign_id: null,
                            featured_video_url: null,
                            featured_image_url: null,
                            instructions: null
                        },
                    },
                    has_coupons: false,
                    has_games: false,
                    has_tickets: false,
                    coupons_content_type: { id: null },
                    game_content_type: { id: null },
                    tickets_content_type: { id: null }
                };
                
                this.clearSwitches();
                this.clearImages();
            },
            
            clearSwitches() {
                const switchCoupons = Alpine.$data(document.getElementById('data-coupons'));
                const switchTickets = Alpine.$data(document.getElementById('data-tickets'));
                const switchGames = Alpine.$data(document.getElementById('data-games'));
                
                if (switchCoupons) switchCoupons.coupons = false;
                if (switchTickets) switchTickets.tickets = false;
                if (switchGames) switchGames.games = false;
            },
            
            clearImages() {
                const imgFeaturedSplashPage = document.getElementById('img-featured_image_url');
                const videoFeaturedSplashPage = document.getElementById('video-featured_video_url');
                
                if (imgFeaturedSplashPage) imgFeaturedSplashPage.src = '/storage/dummy_assets/600x200.png';
                if (videoFeaturedSplashPage) videoFeaturedSplashPage.src = '';
            },
            
            fetchCampaignData(editRoute) {
                const switchCoupons = Alpine.$data(document.getElementById('data-coupons'));
                const switchTickets = Alpine.$data(document.getElementById('data-tickets'));
                const switchGames = Alpine.$data(document.getElementById('data-games'));
                const imgFeaturedSplashPage = document.getElementById('img-featured_image_url');
                const videoFeaturedSplashPage = document.getElementById('video-featured_video_url');
                
                axios.get(editRoute)
                    .then(response => {
                        this.campaignData = response.data.data;
                        
                        if (switchCoupons) switchCoupons.coupons = this.campaignData.has_coupons;
                        if (switchTickets) switchTickets.tickets = this.campaignData.has_tickets;
                        if (switchGames) switchGames.games = this.campaignData.has_games;
                        
                        if (imgFeaturedSplashPage) {
                            imgFeaturedSplashPage.src = this.campaignData.aplazogame.campaign_splash_page.featured_image_url 
                                ? this.campaignData.aplazogame.campaign_splash_page.featured_image_url 
                                : '/storage/dummy_assets/600x200.png';
                        }
                        
                        if (videoFeaturedSplashPage) {
                            videoFeaturedSplashPage.src = this.campaignData.aplazogame.campaign_splash_page.featured_video_url;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching aplazogame data:', error);
                        this.showErrorNotification('{{ __('Error loading data') }}');
                    });
            },
            
            handleSubmit() {
                this.isSubmitting = true;
                document.getElementById('form-aplazogame').submit();
            },
            
            showErrorNotification(message) {
                console.error(message);
            }
        };
    }
</script>

<x-footer.tinymce-config/>

@endsection
