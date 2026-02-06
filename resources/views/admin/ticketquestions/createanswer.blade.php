@extends('layouts.v2.app')

<x-slot name="title">
    {{ $catch_object->id == null ? trans('Create') . ' ' . trans('Answer') : $catch_object->name }}
</x-slot>
<x-slot name="description">
    {{ $catch_object->id == null ? '' : $catch_object->name }}
</x-slot>

<x-slot name="header">
    <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
    {{ $catch_object->id == null ? trans('Create') : trans('Edit') }} {{ __('Answer') }}
    </h1>
</x-slot>

@if (session('status'))
    <div class="mx-5">
        <x-alert :status="session('status')" class="max-w-2xl mx-auto sm:px-6 lg:px-8 p-4 mt-4 text-sm rounded-lg" role="alert" />
    </div>
@endif

@section('content')

@endsection

