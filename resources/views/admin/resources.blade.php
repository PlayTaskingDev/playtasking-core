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
    </div>
    <div class="border-t border-gray-100 p-4 sm:p-6 dark:border-gray-800">
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
    </div>
</div>
<script>
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