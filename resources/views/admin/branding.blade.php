@extends('layouts.v2.app')

@php
    use Illuminate\Support\HtmlString;

    $SaveIcon = new HtmlString('
        <svg  width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg"><path class="fill-white" fill-rule="evenodd" clip-rule="evenodd" d="M5.8125 3.47852C4.56986 3.47852 3.5625 4.48587 3.5625 5.72852V18.7285C3.5625 19.9712 4.56986 20.9785 5.8125 20.9785H18.8124C20.055 20.9785 21.0624 19.9712 21.0624 18.7285L21.0624 8.8427C21.0624 8.24536 20.8249 7.67254 20.4022 7.25049L17.2832 4.1363C16.8613 3.71509 16.2895 3.47852 15.6934 3.47852H5.8125ZM5.8125 4.97852C5.39829 4.97852 5.0625 5.3143 5.0625 5.72852V18.7285C5.0625 19.1427 5.39829 19.4785 5.8125 19.4785H7.56251L7.5625 15.7285C7.5625 14.4859 8.56986 13.4785 9.8125 13.4785L14.8125 13.4785C16.0551 13.4785 17.0625 14.4859 17.0625 15.7285V19.4785H18.8124C19.2266 19.4785 19.5624 19.1427 19.5624 18.7285L19.5624 8.8427C19.5624 8.64359 19.4832 8.45265 19.3423 8.31196L16.2233 5.19778C16.0827 5.05737 15.8921 4.97852 15.6934 4.97852H14.0625L14.0625 5.72851C14.0625 6.97115 13.0551 7.97852 11.8125 7.97852H8.8125C7.56986 7.97852 6.5625 6.97116 6.5625 5.72852V4.97852H5.8125ZM8.0625 4.97852V5.72852C8.0625 6.14273 8.39829 6.47852 8.8125 6.47852H11.8125C12.2267 6.47852 12.5625 6.14273 12.5625 5.72851L12.5625 4.97852H8.0625ZM15.5625 19.4785L9.06251 19.4785L9.0625 15.7285C9.0625 15.3143 9.39829 14.9785 9.8125 14.9785L14.8125 14.9785C15.2267 14.9785 15.5625 15.3143 15.5625 15.7285V19.4785Z" fill="#323544"/></svg>');
@endphp

@section('content')
 
<div class="flex flex-col lg:flex-row lg:space-x-12">
 
    <form action="{{route('v2.save.branding', ['tenant' => tenant('id')])}}" enctype="multipart/form-data" method="POST">
      @if (session('status'))
          <x-v2.ui.alert
              variant="success"
              title="{{ session('status') }}"
              :showLink="false"
          />
      @endif
      @csrf
      <div class="flex max-w-full w-[700px] rounded-2xl border border-gray-200 bg-white p-6 mt-2">
            <section class="max-w-full w-full space-y-6 ">
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                    Branding
                </h3>
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
        </div> 
        <div class="flex items-end gap-5 mt-4 justify-end">
            <x-v2.ui.button type="submit" size="sm" variant="primary" :startIcon="$SaveIcon">Guardar</x-v2.ui.button>
        </div>
    </form>
    <!-- preview app -->
    <div class="rounded-2xl border border-gray-200 bg-white p-6 mt-2">
        <iframe src="/{{tenant('id')}}" class="sticky top-20" credentialless width="640" height="780"></iframe>
    </div>
</div>
<x-footer.tinymce-config/>
@endsection