@extends('layouts.v2.app')
<x-slot name="title">
    {{ $page->title }}
</x-slot>
<x-slot name="description">
    {{ $page->description }}
</x-slot>
<x-slot name="header">
    <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
    {{ $page->title }}
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
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">
            {{ $page->id == null ? trans('Create') : trans('Edit') }} {{ __('Page') }}
            </h2>
            <nav>
            <ol class="flex items-center gap-1.5">
                <li>
                <a class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400" href="{{ route('welcome', ['tenant' => tenant('id')]) }}">
                Home
                <svg class="stroke-current" width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366" stroke="" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                </a>
                </li>
                <li class="text-sm text-gray-800 dark:text-white/90">
                {{ !is_null($page->title) ? $page->title : trans('Create') . ' ' . trans('Page') }}
                </li>
            </ol>
            </nav>
        </div>
        <div class="space-y-6">
            @if (session('status'))
                <x-v2.ui.alert
                variant="success"
                title="{{ session('status') }}"
                :showLink="false"
                />
            @endif
            <form id="form-campaign" method="POST" enctype="multipart/form-data"
            action="{{ $page->id == null ? route('pages.store', ['tenant' => tenant('id')]) : route('pages.update', ['tenant' => tenant('id'), 'page' => $page]) }}">
            <div class="mb-6 flex flex-col justify-between gap-6 rounded-2xl border border-gray-200 bg-white px-6 py-5 sm:flex-row sm:items-center dark:border-gray-800 dark:bg-white/3">
                <div class="flex flex-col gap-2.5 divide-gray-300 sm:flex-row sm:divide-x dark:divide-gray-700">
                    <div class="flex items-center gap-2 sm:pr-3">
                        @if ($page->active)
                            <span class="bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500 inline-flex items-center justify-center gap-1 rounded-full px-2.5 py-0.5 text-sm font-medium">Active</span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-3 mt-6 lg:justify-end">
                    <button type="button" aria-label="{{ __('Close modal') }}"
                    class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">
                    {{ __('Close') }}
                    </button>
                    <button type="submit" aria-label="{{ __('Save changes') }}"
                    class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 disabled:opacity-50 disabled:cursor-not-allowed sm:w-auto transition-opacity">
                    <span >{{ __('Save Changes') }}</span>
                    </button>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
                <div class="lg:col-span-8 2xl:col-span-9">
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/3">
                        <div class="px-2 overflow-y-auto ">
                            <div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2">
                                @csrf
                                @isset($page->id)
                                @method('PATCH')
                                <input type="hidden" name="id" value="{{ $page->id }}">
                                @endisset

                                <h2 class="mt-6 text-lg col-span-2 font-semibold text-gray-800 dark:text-white/90">Page Details</h2>
                                <x-ui.forms.input-text label="{{ __('Description') }}" cols="2" name="description" placeholder="" :value="$page->description" data-field="page.description" />
                                <x-ui.forms.input-text label="{{ __('Slug') }}" cols="2" name="slug" placeholder="" :value="$page->slug" data-field="page.slug" />
                                <x-ui.forms.input-area-tinymce label="Texto de {{ $page->title }}" cols="2" name="content" value="{!! $page->content !!}" />
                            </div>
                        </div>

                    </div>
                </div>
                <div class="space-y-6 lg:col-span-4 2xl:col-span-3">
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/3">
                        <h2 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90">Page Configuration</h2>
                        <x-ui.forms.input-switch label="{{ __('Active') }}" name="active" placeholder="" :value="$page->active" data-field="page.active" />
                    </div>
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/3 space-y-3">
                        <h2 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90">Page Settings</h2>
                        <x-ui.forms.input-file label="{{ __('Icon Page') }}" dummy_img="/storage/dummy_assets/600x200.png" name="icon" placeholder="" :value="$page->icon" data-field="campaign.icon" />

                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<x-footer.tinymce-config/>


@endsection

