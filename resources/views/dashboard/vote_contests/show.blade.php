<x-app-layout>
    <x-slot name="title">
        {{ $vote_contest->title }}
    </x-slot>
    <x-slot name="description">
        {{ $vote_contest->description }}
    </x-slot>

     <style>
        /* Upload Area */
        .upload-area {
            width: 100%;
            background-color: #fff;
            border-radius: 18px;
            padding: 2rem 1.875rem 5rem 1.875rem;
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
        color: var(--clr-light-blue);
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
            border: 1px solid var(--clr-light-blue);
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
        color: var(--clr-light-blue);
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
            top: 1.5625rem;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.9375rem;
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
            background-color: var(--color-blue-600) !important;
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
            background-color: var(--color-blue-600) !important;
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

        /* Confirmation Modal */
        .confirmation-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .confirmation-modal.show {
            display: flex;
        }

        .confirmation-modal__content {
            background-color: white;
            border-radius: 8px;
            padding: 2rem;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: modalSlideIn 300ms ease-in-out;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .confirmation-modal__title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #000;
            margin-bottom: 1rem;
        }

        .confirmation-modal__message {
            font-size: 1rem;
            color: #666;
            margin-bottom: 2rem;
        }

        .confirmation-modal__buttons {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .confirmation-modal__btn {
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            border: none;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 300ms ease-in-out;
        }

        .confirmation-modal__btn--cancel {
            background-color: #e5e7eb;
            color: #000;
        }

        .confirmation-modal__btn--cancel:hover {
            background-color: #d1d5db;
        }

        .confirmation-modal__btn--confirm {
            background-color: #3b82f6;
            color: white;
        }

        .confirmation-modal__btn--confirm:hover {
            background-color: #2563eb;
        }
     </style>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 overflow-hidden">
                <div class="game-card rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-3 p-3">

                    <x-campaign-menu :campaign-games="$campaign_games" :campaign-tickets="$campaign_tickets" :campaign-coupons="$campaign_coupons" :campaign-url="route('campaign.show', ['tenant' => tenant('id'), 'slug' => $vote_contest->campaign->slug])" :active="'games'" />

                    @if (!is_null($vote_contest->game_banner_video))
                        <div class="aspect-w-16 aspect-h-9 mb-6">
                            <iframe src="{{$vote_contest->game_banner_video}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    @endif

                    @if(is_null($vote_contest->game_banner_video) && !is_null($vote_contest->game_banner))
                        @if ($vote_contest->game_banner_url)
                            <a href="{{ $vote_contest->game_banner_url }}" target="_blank" rel="noopener noreferrer">
                                <img src="{{$vote_contest->game_banner}}" alt="" class="w-full rounded mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
                            </a>
                        @else
                            <img src="{{$vote_contest->game_banner}}" alt="" class="w-full rounded mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
                        @endif
                    @endif
                        
                    <h2
                        class="font-semibold text-2xl dark:text-gray-200 leading-tight pb-5 uppercase game-heading">
                        {{ $vote_contest->title }}
                    </h2>
                    
                    <p class="font-bold mb-5">
                        {{ $vote_contest->description }}
                    </p>
                    
                    <div class="mt-5">
                        <form id="voteContestForm" action="{{route('vote_contest.store', ['tenant' => tenant('id')])}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="vote_contest" value="{{$vote_contest->id}}" />
                            @if($vote_contest->show_ranking)
                                <div class="mt-3">
                                    <x-input-label for="title" :value="__('Title')" />
                                    <x-text-input id="title" class="vte__input block mt-1 w-full text-black" type="text" name="title"
                                        :value="old('title')" required autofocus autocomplete="title" />
                                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                                </div>
                            @else
                                <div class="mt-3">
                                    <x-ui.forms.input-text-area label="{{ __('Description') }}" class="vte__input" cols="2" name="title" placeholder="" maxlength="600" charcount="true" value="" />
                                </div>
                            @endif
                            <div class="mt-3">
                                    <div id="uploadArea" class="upload-area">
                                        <!-- Header -->
                                        <div class="upload-area__header">
                                            <h1 class="upload-area__title">Sube tu imagen</h1>
                                            <p class="upload-area__paragraph">
                                                Imagen debe ser de tipo jpeg, png, svg o gif.
                                            </p>
                                        </div>
                                        <!-- End Header -->
                                        <!-- Drop Zoon -->
                                        <div id="dropZoon" class="upload-area__drop-zoon drop-zoon">
                                            <span class="drop-zoon__icon">
                                            <i class='bx bxs-file-image'></i>
                                            </span>
                                            <p class="drop-zoon__paragraph">Selecciona o arrastra y suelta aquí tu imagen</p>
                                            <span id="loadingText" class="drop-zoon__loading-text">Please Wait</span>
                                            <img src="" alt="Preview Image" id="previewImage" class="drop-zoon__preview-image" draggable="false">
                                            <input type="file" id="asset" class="drop-zoon__file-input" accept="image/*" name="asset">
                                        </div>
                                        <!-- End Drop Zoon -->
                                        <!-- File Details -->
                                        <div id="fileDetails" class="upload-area__file-details file-details">
                                            <h3 class="file-details__title">Uploaded File</h3>

                                            <div id="uploadedFile" class="uploaded-file">
                                            <div class="uploaded-file__icon-container">
                                                <x-heroicon-o-document class="w-15 text-blue-600"/>
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
                                <x-input-label class="my-3" for="asset" :value="__('strings.contest_asset_limit', ['size' => $vote_contest->mb_size])" />
                                <x-input-error :messages="$errors->get('asset')" class="mt-2" />
                            </div>
                            <div class="flex items-center justify-center mt-4">
                                <x-primary-button class="w-full" id="publishBtn">
                                    {{ __('Publish') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="voteOverlay" class="w-full h-full fixed top-0 left-0 bg-black opacity-75 z-50 hidden">
        <div class="flex justify-center items-center mt-[50vh]">
            <svg aria-hidden="true" class="inline w-24 h-24 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
            </svg>
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="confirmation-modal">
        <div class="confirmation-modal__content">
            <h2 class="confirmation-modal__title">Confirmar publicación</h2>
            <p class="confirmation-modal__message">¿Estás seguro de que deseas publicar tu participación?</p>
            <div class="confirmation-modal__buttons">
                <button type="button" id="cancelBtn" class="confirmation-modal__btn confirmation-modal__btn--cancel">
                    Cancelar
                </button>
                <button type="button" id="confirmBtn" class="confirmation-modal__btn confirmation-modal__btn--confirm">
                    Confirmar
                </button>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>

            document.addEventListener("DOMContentLoaded", () => {
                const publishBtn = document.getElementById('publishBtn');
                const overlay = document.getElementById('voteOverlay');
                const form = document.getElementById('voteContestForm');
                const vtInputs = document.querySelectorAll('.vte__input');
                const confirmationModal = document.getElementById('confirmationModal');
                const confirmBtn = document.getElementById('confirmBtn');
                const cancelBtn = document.getElementById('cancelBtn');

                publishBtn.disabled = true;
                publishBtn.classList.add('cursor-not-allowed', 'opacity-50');
                // Función que valida campos
                const validarFormulario = () => {
                    // Comprueba si todos los campos tienen valor
                    const todosLlenos = Array.from(vtInputs).every(input => input.value.trim() !== "");
                    
                    // Habilita o deshabilita el botón basado en el resultado
                    if(!todosLlenos){
                        publishBtn.disabled = true;
                        publishBtn.classList.add('cursor-not-allowed', 'opacity-50');
                    }else{
                        publishBtn.disabled = false;
                        publishBtn.classList.remove('cursor-not-allowed', 'opacity-50');
                    }
                };

                // Escucha eventos en cada campo
                vtInputs.forEach(input => {
                    input.addEventListener('input', validarFormulario);
                });

                // Mostrar modal de confirmación
                publishBtn.addEventListener('click', function(e){
                    e.preventDefault();
                    confirmationModal.classList.add('show');
                });

                // Cancelar
                cancelBtn.addEventListener('click', function(){
                    confirmationModal.classList.remove('show');
                });

                // Confirmar y enviar
                confirmBtn.addEventListener('click', function(){
                    confirmationModal.classList.remove('show');
                    overlay.classList.remove('hidden');
                    form.submit();
                });

                // Cerrar modal al hacer click fuera
                confirmationModal.addEventListener('click', function(e){
                    if(e.target === confirmationModal){
                        confirmationModal.classList.remove('show');
                    }
                });
            });

            // Design By
            // - https://dribbble.com/shots/13992184-File-Uploader-Drag-Drop

            // Select Upload-Area
            const uploadArea = document.querySelector('#uploadArea')

            // Select Drop-Zoon Area
            const dropZoon = document.querySelector('#dropZoon');

            // Loading Text
            const loadingText = document.querySelector('#loadingText');

            // Slect File Input 
            const fileInput = document.querySelector('#asset');

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
            "svg",
            "gif"
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

            // :)
        </script>
    @endsection

</x-app-layout>
