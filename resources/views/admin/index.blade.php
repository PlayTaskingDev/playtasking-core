@extends('layouts.v2.app')

@section('content')
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <div class="col-span-12 space-y-6 xl:col-span-7">
            <x-v2.ecommerce.ecommerce-metrics />
            <x-v2.ecommerce.monthly-sale />
        </div>
        <div class="col-span-12 xl:col-span-5">
            <x-v2.ecommerce.monthly-target />
        </div>

        <div class="col-span-12">
            <x-v2.ecommerce.statistics-chart />
        </div>

        <div class="col-span-12 xl:col-span-5">
            <x-v2.ecommerce.customer-demographic />
        </div>

        <div class="col-span-12 xl:col-span-7">
            <x-v2.ecommerce.recent-orders />
        </div>
    </div>
@endsection

