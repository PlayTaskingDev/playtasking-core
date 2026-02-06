@extends('layouts.v2.app')

@section('content')
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <div class="col-span-12 space-y-6 xl:col-span-7">
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-2 md:gap-6">
                <x-ui.statistics.card-count icon="user-group" title="Usuarios Registrados" count="{{ $users }}" />
                <x-ui.statistics.card-count icon="ticket" title="Total de Cupones " count="{{ $coupons }}" />
            </div>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-2 md:gap-6">
                <x-ui.statistics.card-count icon="ticket" title="Cupones entregados " count="{{ $coupons_delivered }}" />
                <x-ui.statistics.card-count icon="ticket" title="Cupones Restantes " count="{{ $coupons_remaining }}" />
            </div>
            {{-- <x-v2.ecommerce.monthly-sale /> --}}
        </div>
        <div class="col-span-12 xl:col-span-5">

            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-4 pb-3 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6">
                <div class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Cupones por Dinamica</h3>
                    </div>

                    <div class="flex items-center gap-3">

                    </div>
                </div>

                <div class="max-w-full overflow-x-auto custom-scrollbar">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-t border-gray-100 dark:border-gray-800">
                            <th class="py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Concurso</p>
                            </th>
                            <th class="py-3 text-right">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Cupones Entregados</p>
                            </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($coupons_dynamic as $dynamic)
                                <tr class="border-t border-gray-100 dark:border-gray-800">
                                <td class="py-3 whitespace-nowrap">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400"> {{$dynamic->awardable && $dynamic->awardable->title ? $dynamic->awardable->title : 'OCR Tickets'}}</p>
                                </td>
                                <td class="py-3 whitespace-nowrap text-right">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{number_format($dynamic->codes_delivered_count)}}</p>
                                </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- <div class="col-span-12">
            <x-v2.ecommerce.statistics-chart />
        </div> --}}

        {{-- <div class="col-span-12 xl:col-span-5">
            <x-v2.ecommerce.customer-demographic />
        </div> --}}

        <div class="col-span-12 xl:col-span-7">
        </div>
    </div>
@endsection

