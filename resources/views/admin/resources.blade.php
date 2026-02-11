
@extends('layouts.v2.app')
<x-slot name="title">
    {{ $title }}
</x-slot>
<x-slot name="description">
    {{ $description }}
</x-slot>
<x-slot name="header">
    <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
    {{ __('Media elements') }}
    </h1>
</x-slot>

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="px-6 py-5">
            <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
            {{ $title }}
            </h3>
            <div class="w-full flex justify-end">
                <button
                data-action="create"
                data-modal-target="resource-modal"
                data-modal-toggle="resource-modal"
                data-modal-type="upload"
                data-save-route="{{ route('resources.store', tenant('id')) }}"
                class="btn bg-brand-500 hover:bg-brand-600 flex w-full justify-center rounded-lg px-4 py-2.5 text-sm font-medium text-white sm:w-auto" aria-label="{{ __('Add new Resource') }}">
                {{ __('Add Resource +') }}
                </button>
            </div>
        </div>
        <div class="border-t border-gray-100 p-4 sm:p-6 dark:border-gray-800">
            @if (!$media_elements->isEmpty())
                <div class="grid grid-cols-2 gap-5 sm:grid-cols-4 xl:grid-cols-6">
                    @foreach ($media_elements as $media_element)
                        <!-- Card Item -->
                        <div>
                            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
                                <div class="mb-5 overflow-hidden rounded-lg">
                                    <img src="{{ $media_element->asset }}" alt="{{ $media_element->description }}" title="{{ $media_element->description }}" class="h-32 w-fit object-scale-down mb-3">
                                </div>
                                <div>
                                    <h4 class="mb-1 text-theme-xl font-medium text-gray-800 dark:text-white/90">
                                    {{ $media_element->description }}
                                    </h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 break-words">
                                    {{ $media_element->asset }}
                                    </p>
                                    <button onclick="copyStringToClipboard(event,'{{ $media_element->asset }}')" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                                    <x-heroicon-o-document-duplicate class="h-4" />
                                    <span>Copy Media URL</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @else
                <div class="bg-white p-5">
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
                    {{__('There are no items to display')}}
                    </h2>
                </div>
            @endif
        </div>
    </div>
    <div id="resource-modal" tabindex="-1" class="hidden fixed inset-0 z-9999  items-center justify-center bg-black/50">
        <div class="relative w-full max-w-3xl rounded-3xl bg-white p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 id="modal-title"
                class="text-2xl font-semibold text-gray-800">
                {{ __('Add Resource') }}
                </h3>
                <button data-modal-hide="resource-modal" class="text-gray-400 hover:text-gray-600">
                ✕
                </button>
            </div>
            <form id="form-resource" method="POST" enctype="multipart/form-data">
                        <div class="px-2 overflow-y-auto custom-scrollbar h-[510px]">
                            <div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2">
                                @csrf
                                <input type="hidden" name="_method" value="POST" id="method-field">
                                <div class="flex flex-col justify-center col-span-2">
                                    <!-- Dropzone -->
                                    <label for="asset" class="font-bold"> Cargar Recurso </label>
                                    <div
                                    x-data="{
                                    isDragging: false,
                                    files: [],
                                    handleDrop(e) {
                                    this.isDragging = false;
                                    const droppedFiles = Array.from(e.dataTransfer.files);
                                    this.handleFiles(droppedFiles);
                                    },
                                    handleFiles(selectedFiles) {
                                    const validTypes = ['image/png', 'image/jpeg', 'image/webp', 'image/svg+xml'];
                                    const validFiles = selectedFiles.filter(file => validTypes.includes(file.type));

                                    if (validFiles.length > 0) {
                                    this.files = [...this.files, ...validFiles];
                                    console.log('Files uploaded:', validFiles);

                                    // Here you can add logic to upload files to server
                                    this.uploadFiles(validFiles);
                                    }
                                    },
                                    uploadFiles(files) {
                                    // Access the element using $refs.fileInput
                                    const inputElement = this.$refs.fileInput;
                                    const form = inputElement.form;
                                    const formData = new FormData(form);
                                    files.forEach(file => {
                                    formData.append('asset[]', file);
                                    });
                                    sendFormData(formData);
                                    },
                                    removeFile(index) {
                                    this.files.splice(index, 1);
                                    }
                                    }"
                                    class="transition border border-gray-300 border-dashed cursor-pointer dark:hover:border-brand-500 dark:border-gray-700 rounded-xl hover:border-brand-500 w-full"
                                    >
                                    <div
                                    @drop.prevent="handleDrop($event)"
                                    @dragover.prevent="isDragging = true"
                                    @dragleave.prevent="isDragging = false"
                                    @click="$refs.fileInput.click()"
                                    :class="isDragging
                                    ? 'border-brand-500 bg-gray-100 dark:bg-gray-800'
                                    : 'border-gray-300 bg-gray-50 dark:border-gray-700 dark:bg-gray-900'"
                                    class="dropzone rounded-xl border-dashed border-gray-300 p-7 lg:p-10 transition-colors cursor-pointer"
                                    id="asset"
                                    >
                                    <!-- Hidden File Input -->
                                    <input
                                    x-ref="fileInput"
                                    type="file"
                                    id="asset"
                                    name="asset[]"
                                    @change="handleFiles(Array.from($event.target.files)); $event.target.value = ''"
                                    accept="image/png,image/jpeg,image/webp,image/svg+xml"

                                    class="hidden"
                                    multiple
                                    @click.stop
                                    />

                                    <div class="flex flex-col items-center m-0">

                                        <!-- Text Content -->
                                        <h4 class="mb-3 font-semibold text-gray-800 text-theme-xl dark:text-white/90 text-center">
                                        <span x-show="!isDragging">Drag & Drop Here</span>
                                        <span x-show="isDragging" x-cloak>Drop Here</span>
                                        </h4>

                                        <span class="text-center mb-5 block w-full max-w-[290px] text-sm text-gray-700 dark:text-gray-400">
                                        Drag and drop your PNG, JPG, WebP, SVG images here or browse
                                        </span>

                                        <span class="font-medium underline text-theme-sm text-brand-500">
                                        Browse File
                                        </span>
                                    </div>
                                </div>

                                <!-- File Preview List (Optional) -->
                                <div x-show="files.length > 0" class="mt-4 p-4 border-t border-gray-200 dark:border-gray-700" x-cloak>
                                    <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Uploaded Files:</h5>
                                    <ul class="space-y-2">
                                        <template x-for="(file, index) in files" :key="index">
                                        <li class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <span class="text-sm text-gray-700 dark:text-gray-300" x-text="file.name"></span>
                                        </div>
                                        <button
                                        @click.stop="removeFile(index)"
                                        type="button"
                                        class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300"
                                        >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        </button>
                                        </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="flex items-center gap-3 mt-6 lg:justify-end">
                    <button type="button" aria-label="{{ __('Close modal') }}"
                    class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">
                    {{ __('Close') }}
                    </button>
                    <button id="upload-files-btn" type="button" aria-label="{{ __('Upload Files') }}"
                    class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 disabled:opacity-50 disabled:cursor-not-allowed sm:w-auto transition-opacity">
                    <span >{{ __('Upload Files') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

@vite(['resources/js/cruds/resource.js'])

<script>
function sendFormData(formData) {
let btnUpload = document.getElementById('upload-files-btn');
btnUpload.addEventListener("click", function(){
btnUpload.disabled = true;
fetch("{{ route('resources.store', tenant('id')) }}", {
method: "POST",
headers: {
'X-CSRF-TOKEN': '{{ csrf_token() }}'
},
body: formData
})
.then(response => {
if (!response.ok) {
throw new Error('Network response was not ok');
}
return response.json();
})
.then(data => {
console.log('Success:', data);
document.getElementById('form-resource').reset();
location.reload();
})
.catch(error => {
console.error('Error:', error);
});
});
}
function copyStringToClipboard(e,text) {
const btn = e.currentTarget;
navigator.clipboard.writeText(text)
.then(() => {
console.log(btn.children)
btn.children[1].innerHTML = "Copied!"
setTimeout(() => {
btn.children[1].innerHTML = "Copy Media URL"
}, 2100);
})
.catch(err => {
console.error('Could not copy text: ', err);
});
}
</script>
@endsection

