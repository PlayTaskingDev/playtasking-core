<x-panel-layout>
    <x-slot name="title">
        {{ $title }}
    </x-slot>
    <x-slot name="description">
        {{ $description }}
    </x-slot>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Statistics') }}
        </h1>
    </x-slot>

    <div class="py-6 mx-3">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-6 bg-white p-3 rounded shadow">
            @if (session('status'))
                <x-alert :status="session('status')" class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-4 mb-4 text-sm rounded-lg"
                    role="alert" />
            @endif
            <div class="grid sm:grid-cols-2 gap-3 mb-3">
                <div class="w-full max-w-sm p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
                    <h5 class="mb-4 text-xl font-medium text-gray-500 dark:text-gray-400 capitalize">{{ __('registered users') }}</h5>
                    <div class="flex items-baseline text-gray-900 dark:text-white">
                        <span class="text-4xl font-extrabold tracking-tight">{{number_format($users)}}</span>
                    </div>
                </div>
                <div class="w-full max-w-sm p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
                    <h5 class="mb-4 text-xl font-medium text-gray-500 dark:text-gray-400 capitalize">{{ __('total coupons') }}</h5>
                    <div class="flex items-baseline text-gray-900 dark:text-white">
                        <span class="text-4xl font-extrabold tracking-tight">{{number_format($coupons)}}</span>
                    </div>
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-3 mb-3">
                <div class="w-full max-w-sm p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
                    <h5 class="mb-4 text-xl font-medium text-gray-500 dark:text-gray-400 capitalize">{{ __('coupons delivered') }}</h5>
                    <div class="flex items-baseline text-gray-900 dark:text-white">
                        <span class="text-4xl font-extrabold tracking-tight">{{number_format($coupons_delivered)}}</span>
                    </div>
                </div>
                <div class="w-full max-w-sm p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
                    <h5 class="mb-4 text-xl font-medium text-gray-500 dark:text-gray-400 capitalize">{{ __('coupons remaining') }}</h5>
                    <div class="flex items-baseline text-gray-900 dark:text-white">
                        <span class="text-4xl font-extrabold tracking-tight">{{number_format($coupons_remaining)}}</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 mb-3">
                <div class="w-full p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
                    <h5 class="mb-4 text-xl font-medium text-gray-500 dark:text-gray-400 capitalize">{{ __('coupons by dynamic') }}</h5>
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        {{__('Concourse')}}
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        {{__('Codes Delivered')}}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($coupons_dynamic as $dynamic)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4">
                                    {{$dynamic->awardable && $dynamic->awardable->title ? $dynamic->awardable->title : 'OCR Tickets'}}
                                </th>
                                <td class="px-6 py-4">
                                    {{number_format($dynamic->codes_delivered_count)}}
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1">
                <div class="w-full p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
                    <h5 class="mb-4 text-xl font-medium text-gray-500 dark:text-gray-400 capitalize">{{ __('Top 10 ranking') }}</h5>
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        {{__('User')}}
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        {{__('Ranking')}}
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        {{__('Points')}}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($top_ten_users as $top_ten_user)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4">
                                    {{$top_ten_user->name}} <br>
                                    {{$top_ten_user->email}}
                                </th>
                                <td class="px-6 py-4">
                                    {{number_format($top_ten_user->ranking)}}
                                </td>
                                <td class="px-6 py-4">
                                    {{number_format($top_ten_user->points)}}
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-panel-layout>
