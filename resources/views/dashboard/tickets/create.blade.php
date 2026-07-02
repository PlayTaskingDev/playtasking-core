@php
    use Carbon\Carbon;
    $minDate = Carbon::parse($campaign->init_date)->format('Y-m-d');
    $maxDate = now()->format('Y-m-d');
    $oldTransactionDate = old('transaction_date');
    $displayDate = $oldTransactionDate
        ? Carbon::parse($oldTransactionDate)->format('d/m/Y')
        : '';
@endphp
<x-app-layout>
    <x-slot name="title">
        {{ __('Tickets') }}
    </x-slot>
    <x-slot name="description">
        {{ __('Tickets') }}
    </x-slot>

    {{-- @section('header_scripts')
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />
    @endsection --}}

<style>
        /* Upload Area */
        .upload-area {
            width: 100%;
            border-radius: 6px;
            padding: 1rem 1.275rem 2rem 1.875rem;
            text-align: center;
            margin-top: 10px;
            border: solid 1px #000;
            position: relative;
        }
        .upload-area__header{
            position: absolute;
            left: 45px;
            top: 30px;
            background: {{ get_app_setting('cards_background_color') }} ;
            z-index: 10;
            padding: 0 5px;
        }

        .upload-area--open {
            /* Slid Down Animation */
            animation: slidDown 500ms ease-in-out;
        }

        @keyframes slidDown {
        from {
            height: 28.125rem; /* 450px */
        }

        to {
            height: 35rem; /* 560px */
        }
        }

        /* Header */

        .upload-area__title {
            font-size: 1.2rem;
            margin-bottom: 0.3125rem;
            color: #000;
        }

        .upload-area__paragraph {
            font-size: 0.9375rem;
            color: #929292 !important;
            margin-top: 0;
        }

        .upload-area__tooltip {
        position: relative;
        color: var(--color-green-400);
        cursor: pointer;
        transition: color 300ms ease-in-out;
        }

        .upload-area__tooltip:hover {
        color: #929292 !important;
        }

        .upload-area__tooltip-data {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -125%);
            min-width: max-content;
            background-color: var(--clr-white);
            color: #929292 !important;
            border: 1px solid var(--color-green-400);
            padding: 0.625rem 1.25rem;
            font-weight: 500;
            opacity: 0;
            visibility: hidden;
            transition: none 300ms ease-in-out;
            transition-property: opacity, visibility;
        }

        .upload-area__tooltip:hover .upload-area__tooltip-data {
            opacity: 1;
            visibility: visible;
        }

        /* Drop Zoon */
        .upload-area__drop-zoon {
            position: relative;
            height: 3.25rem;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            border: 1px solid #000;
            background-color: #fff;
            border-radius: 6px;
            margin-top: 2.1875rem;
            cursor: pointer;
            transition: border-color 300ms ease-in-out;
        }

        .upload-area__drop-zoon:hover {
        border-color: #929292 !important;
        }
        .drop-zoon{
            position: relative;
        }
        .drop-zoon__icon {
            display: flex;
            transition: opacity 300ms ease-in-out;
            position: absolute;
            right: 8px;
        }

        .drop-zoon__paragraph {
            font-size: 0.9375rem;
            color: #929292 !important;
            margin: 0;
            margin-top: 0.625rem;
            transition: opacity 300ms ease-in-out;
        }

        .drop-zoon:hover .drop-zoon__icon,
        .drop-zoon:hover .drop-zoon__paragraph {
        opacity: 0.7;
        }

        .drop-zoon__loading-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: none;
        color: var(--color-green-400);
        z-index: 10;
        }

        .drop-zoon__preview-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 0.3125rem;
            border-radius: 10px;
            display: none;
            z-index: 20;
            transition: opacity 300ms ease-in-out;
        }

        .drop-zoon:hover .drop-zoon__preview-image {
        opacity: 0.8;
        }

        .drop-zoon__file-input {
        display: none;
        }

        /* (drop-zoon--over) Modifier Class */
        .drop-zoon--over {
        border-color: #929292 !important;
        }

        .drop-zoon--over .drop-zoon__icon,
        .drop-zoon--over .drop-zoon__paragraph {
        opacity: 0.7;
        }

        /* (drop-zoon--over) Modifier Class */
        .drop-zoon--Uploaded {
            height: 18rem;
        }

        .drop-zoon--Uploaded .drop-zoon__icon,
        .drop-zoon--Uploaded .drop-zoon__paragraph {
        display: none;
        }

        /* File Details Area */
        .upload-area__file-details {
            height: 0;
            visibility: hidden;
            opacity: 0;
            text-align: left;
            transition: none 500ms ease-in-out;
            transition-property: opacity, visibility;
            transition-delay: 500ms;
        }

        /* (duploaded-file--open) Modifier Class */
        .file-details--open {
        height: auto;
        visibility: visible;
        opacity: 1;
        }

        .file-details__title {
            font-size: 1.125rem;
            font-weight: 500;
            color: #929292 !important;
        }

        /* Uploaded File */
        .uploaded-file {
            display: flex;
            align-items: center;
            padding: 0.625rem 0;
            visibility: hidden;
            opacity: 0;
            transition: none 500ms ease-in-out;
            transition-property: visibility, opacity;
        }

        /* (duploaded-file--open) Modifier Class */
        .uploaded-file--open {
            visibility: visible;
            opacity: 1;
        }

        .uploaded-file__icon-container {
            position: relative;
            margin-right: 0.3125rem;
        }

        .uploaded-file__icon {
            font-size: 3.4375rem;
            color: #929292 !important;
        }

        .uploaded-file__icon-text {
            position: absolute;
            top: 1rem;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.7rem;
            font-weight: 500;
            color: #929292 !important;
        }

        .uploaded-file__info {
            position: relative;
            top: -0.3125rem;
            width: 100%;
            display: flex;
            justify-content: space-between;
        }

        .uploaded-file__info::before,
        .uploaded-file__info::after {
            content: "";
            position: absolute;
            bottom: -0.9375rem;
            width: 0;
            height: 0.5rem;
            background-color: #ebf2ff;
            border-radius: 0.625rem;
        }

        .uploaded-file__info::before {
            width: 100%;
        }

        .uploaded-file__info::after {
            width: 100%;
            background-color: var(--color-green-400) !important;
        }

        /* Progress Animation */
        .uploaded-file__info--active::after {
            animation: progressMove 800ms ease-in-out;
            animation-delay: 300ms;
        }

        @keyframes progressMove {
        from {
            width: 0%;
            background-color: transparent;
        }

        to {
            width: 100%;
            background-color: var(--color-green-400) !important;
        }
        }

        .uploaded-file__name {
            width: 100%;
            max-width: 6.25rem; /* 100px */
            display: inline-block;
            font-size: 1rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color:#000 !important;
        }

        .uploaded-file__counter {
            font-size: 1rem;
            color:#000 !important;
        }
     </style>
    <div class="py-6">
        <div class="max-w-2xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 overflow-hidden">
                <div
                    class="game-card rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-3 p-3">

                    <x-campaign-menu :campaign-games="$campaign_games" :campaign-tickets="$campaign_tickets" :campaign-coupons="$campaign_coupons" :campaign-url="route('campaign.show', ['tenant' => tenant('id'), 'slug' => $campaign->slug])" :active="'tickets'" />
                    <h1 class="text-4xl font-bold italic text-center">TICKETS</h1>
                    <hr class="separator">
                    <h2 class="mb-5 font-bold text-xl">
                        {{get_app_setting('tickets_form_legend')}}
                    </h2>
                    <form id="ticket_create_form" action="{{route('tickets.store', ['tenant' => tenant('id')])}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <x-input-label for="transaction_number" :value="__('Transaction number')" />
                            <x-text-input id="transaction_number" class="block mt-1 w-full text-black" type="text" name="transaction_number" placeholder="{{__('Enter the transaction number')}}" :
                                :value="old('transaction_number')" required autofocus autocomplete="transaction_number" />
                            <x-input-error :messages="$errors->get('transaction_number')" class="mt-2" />
                        </div>
                        <div class="mt-3">
                            <x-input-label for="transaction_date" :value="__('Transaction date')" />
                            <x-text-input id="transaction_date" class="block mt-1 w-full text-black" type="text" name="transaction_date"
                                :value="old('transaction_date')" required autofocus autocomplete="off" datepicker
                                datepicker-autohide datepicker-format="yyyy-mm-dd" datepicker-min-date="{{$init_date}}" datepicker-max-date="{{$today}}" />
                            <x-input-error :messages="$errors->get('transaction_date')" class="mt-2" />
                        </div>
                            <div class="mt-3">
                                <x-input-label for="store" :value="__('Store')" />
                                <x-text-input id="store" class="block mt-1 w-full text-black" type="text" name="store" placeholder="{{__('Enter the store name')}}"
                                    :value="old('store')" required autofocus autocomplete="store" />
                                <x-input-error :messages="$errors->get('store')" class="mt-2" />
                            </div>
                        <div class="mt-3">
                            <x-input-label for="amount" :value="__('Amount')" />
                            <x-text-input id="amount" class="block mt-1 w-full text-black" type="text" name="amount" placeholder="{{__('Enter the total amount')}}"
                                :value="old('amount')" required autofocus autocomplete="amount" />
                            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                        </div>
                        <div class="mt-3">
                                <div id="uploadArea" class="upload-area">
                                    <!-- Header -->
                                    <div class="upload-area__header">
                                        <h3 class="upload-area__title font-bold">Sube la imagen de tu ticket aqui.</h3>
                                    </div>
                                    <!-- End Header -->
                                    <!-- Drop Zoon -->
                                    <div id="dropZoon" class="upload-area__drop-zoon drop-zoon">
                                        <span class="drop-zoon__icon">
                                            <img src="/storage/assets/images/icon-upload-ticket.jpg" alt="Upload Icon" class="w-7 h-7">
                                        </span>
                                        <span id="loadingText" class="drop-zoon__loading-text">Un momentito...</span>
                                        <img src="" alt="Preview Image" id="previewImage" class="drop-zoon__preview-image" draggable="false">
                                        <input type="file" id="ticket" class="drop-zoon__file-input" accept="image/*" name="ticket">
                                    </div>
                                    <small>La imagen no debe exceder los 2MB</small>
                                    <!-- End Drop Zoon -->
                                    <!-- File Details -->
                                    <div id="fileDetails" class="upload-area__file-details file-details">
                                        <h3 class="file-details__title">Ticket Cargado</h3>

                                        <div id="uploadedFile" class="uploaded-file">
                                        <div class="uploaded-file__icon-container">
                                            <img src="/storage/assets/images/icon-upload-ticket.jpg" alt="Document Icon" class="w-8 h-8 text-gray-800">
                                        </div>

                                        <div id="uploadedFileInfo" class="uploaded-file__info">
                                            <span class="uploaded-file__name">Proejct 1</span>
                                            <span class="uploaded-file__counter">0%</span>
                                        </div>
                                        </div>
                                    </div>
                                    <!-- End File Details -->
                                </div>
                            <x-input-label class="my-3" for="ticket" :value="__('Ticket image')" />
                            <x-input-error :messages="$errors->get('ticket')" class="mt-2" />
                        </div>
                        @if (get_app_setting('tickets_quiz_validation') && !is_null($ticket_question))
                        <input type="hidden" name="quid" value="{{$ticket_question->id}}">
                        <div class="mt-3">
                            <x-input-label for="ticket_answer" :value="$ticket_question->title" class="mb-3" />
                            @foreach ($ticket_question->ticket_answers as $answer)
                            <div class="flex items-center mb-4">
                                <x-text-input id="ticket_answer_{{$answer->id}}" class="w-4 h-4 rounded-full border-gray-300 " type="radio" name="ticket_answer"
                                :value="$answer->id" required autofocus />
                                <x-input-label for="ticket_answer_{{$answer->id}}" :value="$answer->title" class="block ms-2  text-sm font-bold dark:text-gray-300" />
                            </div>
                            @endforeach
                        </div>
                        @endif
                        <div class="mt-6">
                            <x-primary-button class="w-full">
                                {{ __('Upload ticket') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @vite(['resources/js/glgc/tcktdte.js'])
</x-app-layout>

