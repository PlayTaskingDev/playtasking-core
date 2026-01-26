@extends('layouts.v2.app')

@php
    use Illuminate\Support\HtmlString;

    $SaveIcon = new HtmlString('
        <svg  width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg"><path class="fill-white" fill-rule="evenodd" clip-rule="evenodd" d="M5.8125 3.47852C4.56986 3.47852 3.5625 4.48587 3.5625 5.72852V18.7285C3.5625 19.9712 4.56986 20.9785 5.8125 20.9785H18.8124C20.055 20.9785 21.0624 19.9712 21.0624 18.7285L21.0624 8.8427C21.0624 8.24536 20.8249 7.67254 20.4022 7.25049L17.2832 4.1363C16.8613 3.71509 16.2895 3.47852 15.6934 3.47852H5.8125ZM5.8125 4.97852C5.39829 4.97852 5.0625 5.3143 5.0625 5.72852V18.7285C5.0625 19.1427 5.39829 19.4785 5.8125 19.4785H7.56251L7.5625 15.7285C7.5625 14.4859 8.56986 13.4785 9.8125 13.4785L14.8125 13.4785C16.0551 13.4785 17.0625 14.4859 17.0625 15.7285V19.4785H18.8124C19.2266 19.4785 19.5624 19.1427 19.5624 18.7285L19.5624 8.8427C19.5624 8.64359 19.4832 8.45265 19.3423 8.31196L16.2233 5.19778C16.0827 5.05737 15.8921 4.97852 15.6934 4.97852H14.0625L14.0625 5.72851C14.0625 6.97115 13.0551 7.97852 11.8125 7.97852H8.8125C7.56986 7.97852 6.5625 6.97116 6.5625 5.72852V4.97852H5.8125ZM8.0625 4.97852V5.72852C8.0625 6.14273 8.39829 6.47852 8.8125 6.47852H11.8125C12.2267 6.47852 12.5625 6.14273 12.5625 5.72851L12.5625 4.97852H8.0625ZM15.5625 19.4785L9.06251 19.4785L9.0625 15.7285C9.0625 15.3143 9.39829 14.9785 9.8125 14.9785L14.8125 14.9785C15.2267 14.9785 15.5625 15.3143 15.5625 15.7285V19.4785Z" fill="#323544"/></svg>');
@endphp

@section('content')
 
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<div class="flex flex-col lg:flex-row lg:space-x-12">
  <aside class="rounded-2xl p-5 dark:border-gray-800 sm:p-6">
    <nav class="flex flex-row md:flex-col space-y-1 space-x-0" id="default-tab" data-tabs-toggle="#default-tab-content" role="tablist">
      <a data-slot="button" id="general-tab" data-tabs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="false" 
        class="inline-flex items-center gap-2 whitespace-nowrap text-sm font-medium transition-[color,box-shadow] disabled:pointer-events-none disabled:opacity-50 outline-none focus-visible:ring-[3px] h-8 rounded-md px-3 w-full justify-start bg-muted cursor-pointer" >
        General
      </a>
      <a data-slot="button" id="inicio-tab" data-tabs-target="#inicio" type="button" role="tab" aria-controls="inicio" aria-selected="false" 
        class="inline-flex items-center gap-2 whitespace-nowrap text-sm font-medium transition-[color,box-shadow] disabled:pointer-events-none disabled:opacity-50 outline-none focus-visible:ring-[3px] h-8 rounded-md px-3 w-full justify-start cursor-pointer">
        Inicio
      </a>
      <a data-slot="button" id="registro-tab" data-tabs-target="#registro" type="button" role="tab" aria-controls="registro" aria-selected="false" 
        class="inline-flex items-center gap-2 whitespace-nowrap text-sm font-medium transition-[color,box-shadow] disabled:pointer-events-none disabled:opacity-50 outline-none focus-visible:ring-[3px] h-8 rounded-md px-3 w-full justify-start cursor-pointer">
        Regístro
      </a>
      <a data-slot="button" id="campanas-tab" data-tabs-target="#campanas" type="button" role="tab" aria-controls="campanas" aria-selected="false" 
        class="inline-flex items-center gap-2 whitespace-nowrap text-sm font-medium transition-[color,box-shadow] disabled:pointer-events-none disabled:opacity-50 outline-none focus-visible:ring-[3px] h-8 rounded-md px-3 w-full justify-start cursor-pointer">
        Campañas
      </a>
      <a data-slot="button" id="tickets-tab" data-tabs-target="#tickets" type="button" role="tab" aria-controls="tickets" aria-selected="false" 
        class="inline-flex items-center gap-2 whitespace-nowrap text-sm font-medium transition-[color,box-shadow] disabled:pointer-events-none disabled:opacity-50 outline-none focus-visible:ring-[3px] h-8 rounded-md px-3 w-full justify-start cursor-pointer">
        Tickets
      </a>
      <a data-slot="button" id="code-hunter-tab" data-tabs-target="#code-hunter" type="button" role="tab" aria-controls="code-hunter" aria-selected="false" 
        class="inline-flex items-center gap-2 whitespace-nowrap text-sm font-medium transition-[color,box-shadow] disabled:pointer-events-none disabled:opacity-50 outline-none focus-visible:ring-[3px] h-8 rounded-md px-3 w-full justify-start cursor-pointer">
        Code Hunter
      </a>
    </nav>
  </aside>
            
    <form action="{{route('v2.save.options', ['tenant' => tenant('id')])}}" enctype="multipart/form-data" method="POST">
      @if (session('status'))
          <x-v2.ui.alert
              variant="success"
              title="{{ session('status') }}"
              :showLink="false"
          />
      @endif
      @csrf
      <div class="flex-1 max-w-full md:max-w-fit rounded-2xl border border-gray-200 bg-white p-6 mt-2">
            {{-- General Options --}}
            <section class="max-w-full w-[500px] space-y-6" id="general" role="tabpanel" aria-labelledby="general-tab">
                <x-ui.forms.input-switch label="App Enabled" name="app_active" switcher="{{ $settings->app_active }}"/>
                <x-ui.forms.input-switch label="Ranking Enabled" name="ranking_enabled" switcher="{{ $settings->ranking_enabled }}" />
                <x-ui.forms.input-text label="Nombre del Sitio" name="app_name" value="{{$settings->app_name}}"  placeholder="PlayTasking" />
                <x-ui.forms.input-text label="Descripción" name="app_description" value="{{$settings->app_description}}"  placeholder="El Mejor Portal donde Siempre Ganas" />
                <x-ui.forms.input-text label="Google Gtag ID" name="ga4_id" value="{{$settings->ga4_id}}" placeholder="G4-1234-12132" />
            </section>
            {{-- End General Options --}}
            {{-- Inicio Options --}}
            <section class="max-w-full space-y-12" id="inicio" role="tabpanel" aria-labelledby="inicio-tab">
                <x-ui.forms.input-area-tinymce label="Contenido de Página de Inicio" name="home_content" value="{{$settings->home_content}}"  />
                <x-ui.forms.input-area-tinymce label="Texto Términos y Condiciones" name="terms_text" value="{{$settings->terms_text}}" />
                <x-ui.forms.input-area-tinymce label="Texto Página de Privacidad" name="privacy_text" value="{{$settings->privacy_text}}" />
            </section>
            {{-- End Inicio Options --}}
            {{-- Registro Options --}}
            <section class="max-w-full w-[500px] space-y-6" id="registro" role="tabpanel" aria-labelledby="registro-tab">
                <x-ui.forms.input-switch label="Agregar Campo Ciudad" name="social_login_active" switcher=""/>
                <x-ui.forms.input-switch label="Permitir registro con redes sociales" name="social_login_active" switcher=""/>
                <x-ui.forms.input-switch label="Solicitar ID de miembros" name="members_number" switcher=""/>
                <x-ui.forms.input-text label="Members legend" name="members_legend" value="{{$settings->members_legend}}"  placeholder="" />
                <x-ui.forms.input-text label="Members placeholder" name="members_placeholder" value="{{$settings->members_placeholder}}"  placeholder="" />
                <x-ui.forms.input-text label="Members URL" name="members_url" value="{{$settings->members_url}}"  placeholder="" />
                <x-ui.forms.input-text label="Register form Name Label" name="reg_form_name_label" value="{{$settings->reg_form_name_label}}"  placeholder="" />
                <x-ui.forms.input-text label="Register form Email Label" name="reg_form_email_label" value="{{$settings->reg_form_email_label}}"  placeholder="" />
                <x-ui.forms.input-text label="Register form Email Confirmation Label" name="reg_form_email_conf_label" value="{{$settings->reg_form_email_conf_label}}"  placeholder="" />
            </section>
            {{-- End Registro Options --}}
            {{-- Campañas Options --}}
            <section class="max-w-full w-[500px] space-y-6" id="campanas" role="tabpanel" aria-labelledby="campanas-tab">
                <x-ui.forms.input-text label="Awards title" name="award_show_title" value="{{$settings->award_show_title}}"  placeholder="" />
                <x-ui.forms.input-text label="Awards user panel title" name="awards_section_title" value="{{$settings->awards_section_title}}"  placeholder="" />
                <x-ui.forms.input-text label="Out of benefits title" name="out_of_coupons_title" value="{{$settings->out_of_coupons_title}}"  placeholder="" />
                <x-ui.forms.input-file label="Out of benefits image" name="out_of_coupons_image" value="{{$settings->out_of_coupons_image}}"  placeholder="" />
            </section>
            {{-- End Campañas Options --}}
            {{-- Tickets Options --}}
            <section class="max-w-full w-[700px] space-y-6" id="tickets" role="tabpanel" aria-labelledby="tickets-tab">
               <x-ui.forms.input-switch label="Ranking Tickets Enabled" name="ranking_enabled_tickets" switcher="{{ $settings->ranking_enabled_tickets }}"/>
                <h6 class="mb-2 text-sm font-bold text-black dark:text-white">
                    {{ __('Module type') }}
                </h6>
                <div  x-data="{ isTrivia: {{ $settings->ocr_ticket_active }} }">
                  <x-ui.forms.input-switch label="Trivia/OCR" model="isTrivia" name="ocr_ticket_active" switcher="{{ $settings->ocr_ticket_active }}"/>
                    <div x-show="!isTrivia" class="mt-4 p-4 border border-gray-400 rounded-lg space-y-6">
                      <x-ui.forms.input-text label="Points per ticket" name="tickets_points" value="{{$settings->tickets_points}}"  placeholder="" />
                      <x-ui.forms.input-switch label="Ticket quiz validation enabled" name="tickets_quiz_validation" switcher="{{ $settings->tickets_quiz_validation }}"/>
                    </div>
                    <div x-show="isTrivia" class="mt-4 p-4 border border-gray-400 rounded-lg">
                        <x-ui.forms.input-text-area label="__('Use breaklines to separate phrases')" name="ocr_ticket_phrases" value="{{$settings->ocr_ticket_phrases}}"  placeholder="" />
                          <h4 class="mt-4">Date parameters</h4>
                          <div class="grid grid-cols-2 space-x-2 space-y-6 mt-2">
                            <x-ui.forms.input-text label="String to find" name="ocr_date_string" value="{{$settings->ocr_date_string}}"  placeholder="" />
                            <x-ui.forms.input-text label="Characters after" name="ocr_date_characters" value="{{$settings->ocr_date_characters}}"  placeholder="" />
                            <x-ui.forms.input-text label="Date format" name="ocr_date_format" value="{{$settings->ocr_date_format}}"  placeholder="" />
                          </div>
                          <h4 class="mt-4">Time parameters</h4>
                          <div class="grid grid-cols-2 space-x-2 space-y-6 mt-2">
                            <x-ui.forms.input-text label="String to find" name="ocr_time_string" value="{{$settings->ocr_time_string}}"  placeholder="" />
                            <x-ui.forms.input-text label="Characters after" name="ocr_time_characters" value="{{$settings->ocr_time_characters}}"  placeholder="" />
                            <x-ui.forms.input-text label="Date format" name="ocr_date_format" value="{{$settings->ocr_date_format}}"  placeholder="" />
                          </div>
                    </div>
                </div>
                   
                <x-ui.forms.input-text label="Ticket form legend" name="tickets_form_legend" value="{{$settings->tickets_form_legend}}"  placeholder="" />
                <div class="grid grid-cols-3 space-x-2">
                  <x-ui.forms.input-file label="Ticket quiz correct image" name="tickets_success_response" value="{{$settings->tickets_success_response}}"  placeholder="" />
                  <x-ui.forms.input-file label="Ticket quiz failed image" name="tickets_failed_response" value="{{$settings->tickets_failed_response}}"  placeholder="" />
                  <x-ui.forms.input-file label="Ticket duplicated image" name="tickets_duplicated_image" value="{{$settings->tickets_duplicated_image}}"  placeholder="" />
                </div>
                <div class="grid grid-cols-3 space-x-2">
                  <x-ui.forms.input-file label="First place image" name="first_place_icon" value="{{$settings->first_place_icon}}"  placeholder="" />
                  <x-ui.forms.input-file label="Second place image" name="second_place_icon" value="{{$settings->second_place_icon}}"  placeholder="" />
                  <x-ui.forms.input-file label="Third place image" name="third_place_icon" value="{{$settings->third_place_icon}}"  placeholder="" />
                </div>
            </section>
            {{-- End Tickets Options --}}
            {{-- Code Hunter Options --}}
            <section class="max-w-full w-[500px] space-y-12" id="code-hunter" role="tabpanel" aria-labelledby="code-hunter-tab">
               <x-ui.forms.input-text label="Code Hunter form legend" name="coupons_form_legend" value="{{$settings->coupons_form_legend}}"  placeholder="" />
                <x-ui.forms.input-text label="Code Hunter form placeholder" name="coupons_field_placeholder" value="{{$settings->coupons_field_placeholder}}"  placeholder="" />
                <div class="grid grid-cols-2 space-x-2">
                  <x-ui.forms.input-file label="Code incorrect image" name="code_hunter_incorrect" value="{{$settings->code_hunter_incorrect}}"  placeholder="" />
                  <x-ui.forms.input-file label="Code duplicated image" name="code_hunter_duplicated" value="{{$settings->code_hunter_duplicated}}"  placeholder="" />
                </div>
            </section>
            {{-- End Code Hunter Options --}}
        </div> 
        <div class="flex items-end gap-5 mt-4 justify-end">
            <x-v2.ui.button type="submit" size="sm" variant="primary" :startIcon="$SaveIcon">Guardar</x-v2.ui.button>
        </div>
    </form>
    <!-- preview app -->
    <iframe src="/{{tenant('id')}}" credentialless width="640" height="780"></iframe>
</div>
=======
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] py-10 px-6">
  <div class="flex flex-col lg:flex-row lg:space-x-12">
    <aside class="rounded-2xl p-5 dark:border-gray-800 sm:p-6 w-60">
      <nav class="flex flex-row md:flex-col space-y-5 space-x-0" id="default-tab" data-tabs-toggle="#default-tab-content" role="tablist">
        <a data-slot="button" id="general-tab" data-tabs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="false" 
          class="inline-flex items-center gap-2 whitespace-nowrap text-sm font-medium transition-[color,box-shadow] disabled:pointer-events-none disabled:opacity-50 outline-none focus-visible:ring-[3px] h-8 rounded-md px-3 w-full justify-start bg-muted cursor-pointer" >
          <x-heroicon-o-adjustments-horizontal class="w-5"/>
          General
        </a>
        <a data-slot="button" id="branding-tab" data-tabs-target="#branding" type="button" role="tab" aria-controls="branding" aria-selected="false" 
          class="inline-flex items-center gap-2 whitespace-nowrap text-sm font-medium transition-[color,box-shadow] disabled:pointer-events-none disabled:opacity-50 outline-none focus-visible:ring-[3px] h-8 rounded-md px-3 w-full justify-start bg-muted cursor-pointer" >
          <x-heroicon-o-paint-brush class="w-5"/>
          Branding
        </a>
        <a data-slot="button" id="inicio-tab" data-tabs-target="#inicio" type="button" role="tab" aria-controls="inicio" aria-selected="false" 
          class="inline-flex items-center gap-2 whitespace-nowrap text-sm font-medium transition-[color,box-shadow] disabled:pointer-events-none disabled:opacity-50 outline-none focus-visible:ring-[3px] h-8 rounded-md px-3 w-full justify-start cursor-pointer">
          <x-heroicon-o-document class="w-5"/>
          Inicio
        </a>
        <a data-slot="button" id="registro-tab" data-tabs-target="#registro" type="button" role="tab" aria-controls="registro" aria-selected="false" 
          class="inline-flex items-center gap-2 whitespace-nowrap text-sm font-medium transition-[color,box-shadow] disabled:pointer-events-none disabled:opacity-50 outline-none focus-visible:ring-[3px] h-8 rounded-md px-3 w-full justify-start cursor-pointer">
          <x-heroicon-o-document-text class="w-5"/>
          Regístro
        </a>
        <a data-slot="button" id="campanas-tab" data-tabs-target="#campanas" type="button" role="tab" aria-controls="campanas" aria-selected="false" 
          class="inline-flex items-center gap-2 whitespace-nowrap text-sm font-medium transition-[color,box-shadow] disabled:pointer-events-none disabled:opacity-50 outline-none focus-visible:ring-[3px] h-8 rounded-md px-3 w-full justify-start cursor-pointer">
          <x-heroicon-o-hashtag class="w-5"/>
          Campañas
        </a>
        <a data-slot="button" id="tickets-tab" data-tabs-target="#tickets" type="button" role="tab" aria-controls="tickets" aria-selected="false" 
          class="inline-flex items-center gap-2 whitespace-nowrap text-sm font-medium transition-[color,box-shadow] disabled:pointer-events-none disabled:opacity-50 outline-none focus-visible:ring-[3px] h-8 rounded-md px-3 w-full justify-start cursor-pointer">
          <x-heroicon-o-ticket class="w-5"/>
          Tickets
        </a>
        <a data-slot="button" id="code-hunter-tab" data-tabs-target="#code-hunter" type="button" role="tab" aria-controls="code-hunter" aria-selected="false" 
          class="inline-flex items-center gap-2 whitespace-nowrap text-sm font-medium transition-[color,box-shadow] disabled:pointer-events-none disabled:opacity-50 outline-none focus-visible:ring-[3px] h-8 rounded-md px-3 w-full justify-start cursor-pointer">
          <x-heroicon-o-viewfinder-circle class="w-5"/>
          Code Hunter
        </a>
      </nav>
    </aside>
              
      <form action="{{route('v2.save.options', ['tenant' => tenant('id')])}}" enctype="multipart/form-data" method="POST">
        @if (session('status'))
            <x-v2.ui.alert
                variant="success"
                title="{{ session('status') }}"
                :showLink="false"
            />
        @endif
        @csrf
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes

        <div class="flex-1 max-w-full md:max-w-fit rounded-2xl border border-gray-200 bg-white p-6 mt-2">
              {{-- General Options --}}
              <section class="max-w-full w-[600px] space-y-6 transition-all ease-in duration-200" id="general" role="tabpanel" aria-labelledby="general-tab">
                  <x-ui.forms.input-switch label="App Enabled" name="app_active" switcher="{{ $settings->app_active }}"/>
                  <x-ui.forms.input-switch label="Ranking Enabled" name="ranking_enabled" switcher="{{ $settings->ranking_enabled }}" />
                  <x-ui.forms.input-text label="Nombre del Sitio" name="app_name" value="{{$settings->app_name}}"  placeholder="PlayTasking" />
                  <x-ui.forms.input-text label="Descripción" name="app_description" value="{{$settings->app_description}}"  placeholder="El Mejor Portal donde Siempre Ganas" />
                  <x-ui.forms.input-text label="Google Gtag ID" name="ga4_id" value="{{$settings->ga4_id}}" placeholder="G4-1234-12132" />
              </section>
              {{-- End General Options --}}
              {{-- Branding Options --}}
              <section class="max-w-full w-full space-y-6 " id="branding" role="tabpanel" aria-labelledby="branding-tab">
                <div class="grid grid-cols-2 space-x-6">
                    <x-ui.forms.input-file label="Site Logo" name="app_logo" value="{{$settings->app_logo}}"   />
                    <x-ui.forms.input-file label="Favicon" name="favicon" value="{{$settings->favicon}}"  />
                </div>
                <div class="grid grid-cols-2 space-x-6">
                    <x-ui.forms.input-file label="Imagen de fondo" name="app_background" value="{{$settings->app_background}}"   />
                    <x-ui.forms.input-file label="Imagen de fondo (animada)" name="app_animated_background" value="{{$settings->app_animated_background}}"  />
                </div>
                <div class="grid grid-cols-2 space-x-6">
                    <x-ui.forms.input-file label="Ranking menu icon" name="ranking_icon" value="{{$settings->ranking_icon}}"   />
                    <x-ui.forms.input-file label="Ranking menu icon (active)" name="ranking_icon_active" value="{{$settings->ranking_icon_active}}"  />
                </div>
                <x-ui.forms.input-file label="Ranking banner" name="ranking_banner" value="{{$settings->ranking_banner}}"  />   
                <x-ui.forms.input-switch label="Cards shadow enabled" name="cards_shadow" switcher="{{$settings->cards_shadow}}" />
                <div class="grid grid-cols-2 space-x-6">
                    <x-ui.forms.input-color label="Fondo del header" name="header_background_color" value="{{$settings->header_background_color}}" />
                    <x-ui.forms.input-color label="Fondo del body" name="app_background_color" value="{{$settings->app_background_color}}" />
                </div>
                <div class="grid grid-cols-2 space-x-6">
                    <x-ui.forms.input-color label="Disabled game gradient 1" name="disabled_gradient_1" value="{{$settings->disabled_gradient_1}}" />
                    <x-ui.forms.input-color label="Disabled game gradient 2" name="disabled_gradient_2" value="{{$settings->disabled_gradient_2}}" />
                </div>
                <div class="grid grid-cols-2 space-x-6">
                    <x-ui.forms.input-color label="Cards background" name="cards_background_color" value="{{$settings->cards_background_color}}" />
                    <x-ui.forms.input-color label="Cards font color" name="cards_font_color" value="{{$settings->cards_font_color}}" />
                </div>
                <div class="grid grid-cols-2 space-x-6">
                    <x-ui.forms.input-color label="Auth user gradient 1" name="ranking_color_1" value="{{$settings->ranking_color_1}}" />
                    <x-ui.forms.input-color label="Auth user gradient 2" name="ranking_color_2" value="{{$settings->ranking_color_2}}" />
                </div>
                <div class="grid grid-cols-2 space-x-6">
                    <x-ui.forms.input-color label="Primary button color" name="primary_button_color" value="{{$settings->primary_button_color}}" />
                    <x-ui.forms.input-color label="Primary button background color" name="primary_button_background" value="{{$settings->primary_button_background}}" />
                </div>
                <x-ui.forms.input-text-area label="Custom CSS" name="custom_css" value="{{$settings->custom_css}}" placeholder="" />
              </section>
              {{-- End Branding Options --}}
              {{-- Inicio Options --}}
              <section class="max-w-full space-y-12  transition-all ease-in duration-200" id="inicio" role="tabpanel" aria-labelledby="inicio-tab">
                  <x-ui.forms.input-area-tinymce label="Contenido de Página de Inicio" name="home_content" value="{{$settings->home_content}}"  />
                  <x-ui.forms.input-area-tinymce label="Texto Términos y Condiciones" name="terms_text" value="{{$settings->terms_text}}" />
                  <x-ui.forms.input-area-tinymce label="Texto Página de Privacidad" name="privacy_text" value="{{$settings->privacy_text}}" />
              </section>
              {{-- End Inicio Options --}}
              {{-- Registro Options --}}
              <section class="max-w-full w-[600px] space-y-6" id="registro" role="tabpanel" aria-labelledby="registro-tab">
                  <x-ui.forms.input-switch label="Agregar Campo Ciudad" name="social_login_active" switcher="{{ $settings->social_login_active }}"/>
                  <x-ui.forms.input-switch label="Permitir registro con redes sociales" name="social_login_active" switcher="{{ $settings->social_login_active }}"/>
                  <x-ui.forms.input-switch label="Solicitar ID de miembros" name="members_number" switcher="{{ $settings->members_number }}"/>
                  <x-ui.forms.input-text label="Members legend" name="members_legend" value="{{$settings->members_legend}}"  placeholder="" />
                  <x-ui.forms.input-text label="Members placeholder" name="members_placeholder" value="{{$settings->members_placeholder}}"  placeholder="" />
                  <x-ui.forms.input-text label="Members URL" name="members_url" value="{{$settings->members_url}}"  placeholder="" />
                  <x-ui.forms.input-text label="Register form Name Label" name="reg_form_name_label" value="{{$settings->reg_form_name_label}}"  placeholder="" />
                  <x-ui.forms.input-text label="Register form Email Label" name="reg_form_email_label" value="{{$settings->reg_form_email_label}}"  placeholder="" />
                  <x-ui.forms.input-text label="Register form Email Confirmation Label" name="reg_form_email_conf_label" value="{{$settings->reg_form_email_conf_label}}"  placeholder="" />
              </section>
              {{-- End Registro Options --}}
              {{-- Campañas Options --}}
              <section class="max-w-full w-[600px] space-y-6" id="campanas" role="tabpanel" aria-labelledby="campanas-tab">
                  <x-ui.forms.input-text label="Awards title" name="award_show_title" value="{{$settings->award_show_title}}"  placeholder="" />
                  <x-ui.forms.input-text label="Awards user panel title" name="awards_section_title" value="{{$settings->awards_section_title}}"  placeholder="" />
                  <x-ui.forms.input-text label="Out of benefits title" name="out_of_coupons_title" value="{{$settings->out_of_coupons_title}}"  placeholder="" />
                  <x-ui.forms.input-file label="Out of benefits image" name="out_of_coupons_image" value="{{$settings->out_of_coupons_image}}"  placeholder="" />
              </section>
              {{-- End Campañas Options --}}
              {{-- Tickets Options --}}
              <section class="max-w-full w-[700px] space-y-6" id="tickets" role="tabpanel" aria-labelledby="tickets-tab">
                <x-ui.forms.input-switch label="Ranking Tickets Enabled" name="ranking_enabled_tickets" switcher="{{ $settings->ranking_enabled_tickets }}"/>
                  <h6 class="mb-2 text-sm font-bold text-black dark:text-white">
                      {{ __('Module type') }}
                  </h6>
                  <div  x-data="{ isTrivia: {{ $settings->ocr_ticket_active }} }">
                    <x-ui.forms.input-switch label="Trivia/OCR" model="isTrivia" name="ocr_ticket_active" switcher="{{ $settings->ocr_ticket_active }}"/>
                      <div x-show="!isTrivia" class="mt-4 p-4 border border-gray-400 rounded-lg space-y-6">
                        <x-ui.forms.input-text label="Points per ticket" name="tickets_points" value="{{$settings->tickets_points}}"  placeholder="" />
                        <x-ui.forms.input-switch label="Ticket quiz validation enabled" name="tickets_quiz_validation" switcher="{{ $settings->tickets_quiz_validation }}"/>
                      </div>
                      <div x-show="isTrivia" class="mt-4 p-4 border border-gray-400 rounded-lg">
                          <x-ui.forms.input-text-area label="__('Use breaklines to separate phrases')" name="ocr_ticket_phrases" value="{{$settings->ocr_ticket_phrases}}"  placeholder="" />
                            <h4 class="mt-4">Date parameters</h4>
                            <div class="grid grid-cols-2 space-x-2 space-y-6 mt-2">
                              <x-ui.forms.input-text label="String to find" name="ocr_date_string" value="{{$settings->ocr_date_string}}"  placeholder="" />
                              <x-ui.forms.input-text label="Characters after" name="ocr_date_characters" value="{{$settings->ocr_date_characters}}"  placeholder="" />
                              <x-ui.forms.input-text label="Date format" name="ocr_date_format" value="{{$settings->ocr_date_format}}"  placeholder="" />
                            </div>
                            <h4 class="mt-4">Time parameters</h4>
                            <div class="grid grid-cols-2 space-x-2 space-y-6 mt-2">
                              <x-ui.forms.input-text label="String to find" name="ocr_time_string" value="{{$settings->ocr_time_string}}"  placeholder="" />
                              <x-ui.forms.input-text label="Characters after" name="ocr_time_characters" value="{{$settings->ocr_time_characters}}"  placeholder="" />
                              <x-ui.forms.input-text label="Date format" name="ocr_date_format" value="{{$settings->ocr_date_format}}"  placeholder="" />
                            </div>
                      </div>
                  </div>
                    
                  <x-ui.forms.input-text label="Ticket form legend" name="tickets_form_legend" value="{{$settings->tickets_form_legend}}"  placeholder="" />
                  <div class="grid grid-cols-3 space-x-2">
                    <x-ui.forms.input-file label="Ticket quiz correct image" name="tickets_success_response" value="{{$settings->tickets_success_response}}"  placeholder="" />
                    <x-ui.forms.input-file label="Ticket quiz failed image" name="tickets_failed_response" value="{{$settings->tickets_failed_response}}"  placeholder="" />
                    <x-ui.forms.input-file label="Ticket duplicated image" name="tickets_duplicated_image" value="{{$settings->tickets_duplicated_image}}"  placeholder="" />
                  </div>
                  <div class="grid grid-cols-3 space-x-2">
                    <x-ui.forms.input-file label="First place image" name="first_place_icon" value="{{$settings->first_place_icon}}"  placeholder="" />
                    <x-ui.forms.input-file label="Second place image" name="second_place_icon" value="{{$settings->second_place_icon}}"  placeholder="" />
                    <x-ui.forms.input-file label="Third place image" name="third_place_icon" value="{{$settings->third_place_icon}}"  placeholder="" />
                  </div>
              </section>
              {{-- End Tickets Options --}}
              {{-- Code Hunter Options --}}
              <section class="max-w-full w-[600px] space-y-12" id="code-hunter" role="tabpanel" aria-labelledby="code-hunter-tab">
                <x-ui.forms.input-text label="Code Hunter form legend" name="coupons_form_legend" value="{{$settings->coupons_form_legend}}"  placeholder="" />
                  <x-ui.forms.input-text label="Code Hunter form placeholder" name="coupons_field_placeholder" value="{{$settings->coupons_field_placeholder}}"  placeholder="" />
                  <div class="grid grid-cols-2 space-x-2">
                    <x-ui.forms.input-file label="Code incorrect image" name="code_hunter_incorrect" value="{{$settings->code_hunter_incorrect}}"  placeholder="" />
                    <x-ui.forms.input-file label="Code duplicated image" name="code_hunter_duplicated" value="{{$settings->code_hunter_duplicated}}"  placeholder="" />
                  </div>
              </section>
              {{-- End Code Hunter Options --}}
          </div> 
          <div class="flex items-end gap-5 mt-4 justify-end">
              <x-v2.ui.button type="submit" size="sm" variant="primary" :startIcon="$SaveIcon">Guardar</x-v2.ui.button>
          </div>
      </form>
      <!-- preview app -->
      {{-- <iframe src="/{{tenant('id')}}" credentialless width="640" height="780"></iframe> --}}
  </div>
</div>

<x-footer.tinymce-config/>
@endsection