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

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 overflow-hidden">
                <div
                    class="game-card rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-3">

                    <x-campaign-menu :campaign-games="$campaign_games" :campaign-tickets="$campaign_tickets" :campaign-coupons="$campaign_coupons" :campaign-url="route('campaign.show', ['tenant' => tenant('id'), 'slug' => $campaign->slug])" :active="'tickets'" />

                    <h2 class="mb-5 font-bold text-xl">
                        {{get_app_setting('tickets_form_legend')}}
                    </h2>
                    <form id="ticket_create_form" action="{{route('tickets.store', ['tenant' => tenant('id')])}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <x-input-label for="transaction_number" :value="__('Transaction number')" />
                            <x-text-input id="transaction_number" class="block mt-1 w-full text-black" type="text" name="transaction_number"
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
                            <x-text-input id="store" class="block mt-1 w-full text-black" type="text" name="store"
                                :value="old('store')" required autofocus autocomplete="store" />
                            <x-input-error :messages="$errors->get('store')" class="mt-2" />
                        </div>
                        <div class="mt-3">
                            <x-input-label for="amount" :value="__('Amount')" />
                            <x-text-input id="amount" class="block mt-1 w-full text-black" type="text" name="amount"
                                :value="old('amount')" required autofocus autocomplete="amount" />
                            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                        </div>
                        <div class="mt-3">
                            <x-input-label for="ticket" :value="__('Ticket image')" />
                            <x-text-input id="ticket" class="block mt-1 w-full text-black bg-white" type="file" name="ticket"
                                :value="old('ticket')" required />
                            <x-input-error :messages="$errors->get('ticket')" class="mt-2" />
                        </div>
                        @if (get_app_setting('tickets_quiz_validation') && !is_null($ticket_question))
                        <input type="hidden" name="quid" value="{{$ticket_question->id}}">
                        <div class="mt-3">
                            <x-input-label for="ticket_answer" :value="$ticket_question->title" class="mb-3" />
                            @foreach ($ticket_question->ticket_answers as $answer)
                            <div class="flex items-center mb-4">
                                <x-text-input id="ticket_answer_{{$answer->id}}" class="w-4 h-4 border-gray-300 focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-600 dark:focus:bg-blue-600 dark:bg-gray-700 dark:border-gray-600" type="radio" name="ticket_answer"
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
    @section('scripts')
        {{-- <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
        <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
        <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
        <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
 --}}
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                /* FilePond.registerPlugin(FilePondPluginFileValidateType);
                FilePond.registerPlugin(FilePondPluginImagePreview);
                FilePond.registerPlugin(FilePondPluginFileValidateSize);

                FilePond.setOptions({
                    acceptedFileTypes: ['image/png', 'image/jpg', 'image/jpeg', 'image/heic'],
                    maxFiles: 1,
                    eValidateTypeDetectType: (source, type) =>
                        new Promise((resolve, reject) => {
                            resolve(type);
                        }),
                    server: {
                        url: "{{ route('filepond-process', ['tenant' => tenant('id')]) }}",
                        headers: {
                            'X-CSRF-TOKEN': "{{ @csrf_token() }}",
                        }
                    },
                    labelIdle: '<i class="fa fa-upload me-2" aria-hidden="true" style="font-size: 20px !important;"></i>{{  __('Add image or photo') }}',
                    labelInvalidField: '{{ __('Field contains invalid files') }}',
                    labelFileLoading: '{{ __('Loading') }}',
                    labelFileLoadError: '{{ __('Error during load') }}',
                    labelFileProcessingError: '{{ __('Error during upload') }}',
                    labelFileProcessing: '{{ __('Uploading') }}',
                    labelFileProcessingComplete: '{{ __('Upload complete') }}',
                    labelFileTypeNotAllowed: '{{ __('File of invalid type') }}',
                    labelFileWaitingForSize: 'Waiting for size',
                    maxFileSize: '12MB',
                    labelMaxFileSizeExceeded: 'File size too big',
                    labelMaxFileSize: 'Maximum file size is {filesize}'
                });

                FilePond.create(document.querySelector('input[name="ticket"]')); */

                // Datepicker
                const ticketDate = document.getElementById('transaction_date');
                const datepickerInstance = new Datepicker(ticketDate, {
                    format: 'yyyy-mm-dd',
                    minDate: '{{$init_date}}',
                    maxDate: '{{$today}}',
                    autoSelectToday: 1,
                    autohide: true
                });
            });
        </script>
    @endsection
</x-app-layout>

