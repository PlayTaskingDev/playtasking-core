@extends('layouts.v2.app')

@section('content')
    <x-v2.common.page-breadcrumb pageTitle="From Elements" />
    <div class="space-y-6">
        <x-v2.common.component-card title="Basic Table 1">
            <x-v2.tables.basic-tables.basic-tables-one />
        </x-v2.common.component-card>
        <x-v2.common.component-card title="Basic Table 2">
            <x-v2.tables.basic-tables.basic-tables-two />
        </x-v2.common.component-card>
        <x-v2.common.component-card title="Basic Table 3">
            <x-v2.tables.basic-tables.basic-tables-three />
        </x-v2.common.component-card>
        <x-v2.common.component-card title="Basic Table 4">
            <x-v2.tables.basic-tables.basic-tables-four />
        </x-v2.common.component-card>
        <x-v2.common.component-card title="Basic Table 5">
            <x-v2.tables.basic-tables.basic-tables-five />
        </x-v2.common.component-card>
    </div>
@endsection
