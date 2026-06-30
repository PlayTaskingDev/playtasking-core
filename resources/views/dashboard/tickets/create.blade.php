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
            background-color: #fff;
            border-radius: 18px;
            padding: 2rem 1.875rem 3rem 1.875rem;
            text-align: center;
            margin-top: 10px;
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
            font-size: 1.8rem;
            font-weight: 500;
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
            height: 11.25rem; /* 180px */
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            border: 2px dashed #cfcfcf;
            border-radius: 15px;
            margin-top: 2.1875rem;
            cursor: pointer;
            transition: border-color 300ms ease-in-out;
        }

        .upload-area__drop-zoon:hover {
        border-color: #929292 !important;
        }

        .drop-zoon__icon {
        display: flex;
        font-size: 3.75rem;
        color: #929292 !important;
        transition: opacity 300ms ease-in-out;
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
                                <small>{{__('Enter the total amount without commas or points.')}}</small>
                            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                        </div>
                        <div class="mt-3">
                                <div id="uploadArea" class="upload-area">
                                    <!-- Header -->
                                    <div class="upload-area__header">
                                        <h1 class="upload-area__title">Sube tu Ticket</h1>
                                        <p class="upload-area__paragraph">
                                            Imagen debe ser de tipo jpeg, png.
                                        </p>
                                    </div>
                                    <!-- End Header -->
                                    <!-- Drop Zoon -->
                                    <div id="dropZoon" class="upload-area__drop-zoon drop-zoon">
                                        <span class="drop-zoon__icon">
                                        <i class='bx bxs-file-image'></i>
                                        </span>
                                        <p class="drop-zoon__paragraph">Selecciona o arrastra y suelta aquí tu imagen</p>
                                        <span id="loadingText" class="drop-zoon__loading-text">Un momentito...</span>
                                        <img src="" alt="Preview Image" id="previewImage" class="drop-zoon__preview-image" draggable="false">
                                        <input type="file" id="ticket" class="drop-zoon__file-input" accept="image/*" name="ticket">
                                    </div>
                                    <!-- End Drop Zoon -->
                                    <!-- File Details -->
                                    <div id="fileDetails" class="upload-area__file-details file-details">
                                        <h3 class="file-details__title">Ticket Cargado</h3>

                                        <div id="uploadedFile" class="uploaded-file">
                                        <div class="uploaded-file__icon-container">
                                            <x-heroicon-o-document class="w-10 text-gray-800"/>
                                            <span class="uploaded-file__icon-text"></span> <!-- Data Will be Comes From Js -->
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
                // const ticketDate = document.getElementById('transaction_date');
                // const datepickerInstance = new Datepicker(ticketDate, {
                //     format: 'yyyy-mm-dd',
                //     minDate: '{{$init_date}}',
                //     maxDate: '{{$today}}',
                //     autoSelectToday: 1,
                //     autohide: true
                // });
            });
            // Select Upload-Area
            const uploadArea = document.querySelector('#uploadArea')

            // Select Drop-Zoon Area
            const dropZoon = document.querySelector('#dropZoon');

            // Loading Text
            const loadingText = document.querySelector('#loadingText');

            // Slect File Input 
            const fileInput = document.querySelector('#ticket');

            // Select Preview Image
            const previewImage = document.querySelector('#previewImage');

            // File-Details Area
            const fileDetails = document.querySelector('#fileDetails');

            // Uploaded File
            const uploadedFile = document.querySelector('#uploadedFile');

            // Uploaded File Info
            const uploadedFileInfo = document.querySelector('#uploadedFileInfo');

            // Uploaded File  Name
            const uploadedFileName = document.querySelector('.uploaded-file__name');

            // Uploaded File Icon
            const uploadedFileIconText = document.querySelector('.uploaded-file__icon-text');

            // Uploaded File Counter
            const uploadedFileCounter = document.querySelector('.uploaded-file__counter');


            // Images Types
            const imagesTypes = [
                "jpeg",
                "png",
            ];
            // When (drop-zoon) has (dragover) Event 
            dropZoon.addEventListener('dragover', function (event) {
            // Prevent Default Behavior 
            event.preventDefault();

            // Add Class (drop-zoon--over) On (drop-zoon)
            dropZoon.classList.add('drop-zoon--over');
            });

            // When (drop-zoon) has (dragleave) Event 
            dropZoon.addEventListener('dragleave', function (event) {
            // Remove Class (drop-zoon--over) from (drop-zoon)
            dropZoon.classList.remove('drop-zoon--over');
            });

            // When (drop-zoon) has (drop) Event 
            dropZoon.addEventListener('drop', function (event) {
            // Prevent Default Behavior 
            event.preventDefault();

            // Remove Class (drop-zoon--over) from (drop-zoon)
            dropZoon.classList.remove('drop-zoon--over');

            // Select The Dropped File
            const file = event.dataTransfer.files[0];

            // Call Function uploadFile(), And Send To Her The Dropped File :)
            uploadFile(file);
            });

            // When (drop-zoon) has (click) Event 
            dropZoon.addEventListener('click', function (event) {
            // Click The (fileInput)
            fileInput.click();
            });

            // When (fileInput) has (change) Event 
            fileInput.addEventListener('change', function (event) {
            // Select The Chosen File
            const file = event.target.files[0];

            // Call Function uploadFile(), And Send To Her The Chosen File :)
            uploadFile(file);
            });

            // Upload File Function
            function uploadFile(file) {
                // FileReader()
                const fileReader = new FileReader();
                // File Type 
                const fileType = file.type;
                // File Size 
                const fileSize = file.size;

                // If File Is Passed from the (File Validation) Function
                if (fileValidate(fileType, fileSize)) {
                    // Asignar el archivo al input file para que se envíe al servidor
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    fileInput.files = dataTransfer.files;

                    // Add Class (drop-zoon--Uploaded) on (drop-zoon)
                    dropZoon.classList.add('drop-zoon--Uploaded');

                    // Show Loading-text
                    loadingText.style.display = "block";
                    // Hide Preview Image
                    previewImage.style.display = 'none';

                    // Remove Class (uploaded-file--open) From (uploadedFile)
                    uploadedFile.classList.remove('uploaded-file--open');
                    // Remove Class (uploaded-file__info--active) from (uploadedFileInfo)
                    uploadedFileInfo.classList.remove('uploaded-file__info--active');

                    // After File Reader Loaded 
                    fileReader.addEventListener('load', function () {
                    // After Half Second 
                    setTimeout(function () {
                        // Add Class (upload-area--open) On (uploadArea)
                        uploadArea.classList.add('upload-area--open');

                        // Hide Loading-text (please-wait) Element
                        loadingText.style.display = "none";
                        // Show Preview Image
                        previewImage.style.display = 'block';

                        // Add Class (file-details--open) On (fileDetails)
                        fileDetails.classList.add('file-details--open');
                        // Add Class (uploaded-file--open) On (uploadedFile)
                        uploadedFile.classList.add('uploaded-file--open');
                        // Add Class (uploaded-file__info--active) On (uploadedFileInfo)
                        uploadedFileInfo.classList.add('uploaded-file__info--active');
                    }, 500); // 0.5s

                    // Add The (fileReader) Result Inside (previewImage) Source
                    previewImage.setAttribute('src', fileReader.result);

                    // Add File Name Inside Uploaded File Name
                    uploadedFileName.innerHTML = file.name;

                    // Call Function progressMove();
                    progressMove();
                    });

                    // Read (file) As Data Url 
                    fileReader.readAsDataURL(file);
                } else { // Else

                    this; // (this) Represent The fileValidate(fileType, fileSize) Function

                };
            };

            // Progress Counter Increase Function
            function progressMove() {
                // Counter Start
                let counter = 0;

                // After 600ms 
                setTimeout(() => {
                    // Every 100ms
                    let counterIncrease = setInterval(() => {
                    // If (counter) is equle 100 
                    if (counter === 100) {
                        // Stop (Counter Increase)
                        clearInterval(counterIncrease);
                    } else { // Else
                        // plus 10 on counter
                        counter = counter + 10;
                        // add (counter) vlaue inisde (uploadedFileCounter)
                        uploadedFileCounter.innerHTML = `${counter}%`
                    }
                    }, 100);
                }, 600);
            };


            // Simple File Validate Function
            function fileValidate(fileType, fileSize) {
                // File Type Validation
                let isImage = imagesTypes.filter((type) => fileType.indexOf(`image/${type}`) !== -1);

                // If The Uploaded File Type Is 'jpeg'
                if (isImage[0] === 'jpeg') {
                    // Add Inisde (uploadedFileIconText) The (jpg) Value
                    uploadedFileIconText.innerHTML = 'jpg';
                } else { // else
                    // Add Inisde (uploadedFileIconText) The Uploaded File Type 
                    uploadedFileIconText.innerHTML = isImage[0];
                };

                // If The Uploaded File Is An Image
                if (isImage.length !== 0) {
                    // Check, If File Size Is 2MB or Less
                    if (fileSize <= 2000000) { // 2MB :)
                    return true;
                    } else { // Else File Size
                    return alert('Please Your File Should be 2 Megabytes or Less');
                    };
                } else { // Else File Type 
                    return alert('Please make sure to upload An Image File Type');
                };
            };

        </script>
    @endsection
</x-app-layout>

