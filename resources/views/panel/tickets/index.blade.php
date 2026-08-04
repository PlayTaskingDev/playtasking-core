<x-panel-layout>
    <x-slot name="title">
        {{ $title }}
    </x-slot>
    <x-slot name="description">
        {{ $description }}
    </x-slot>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tickets') }}
        </h1>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="relative overflow-x-auto shadow-md rounded-lg mx-5">
                @if (session('status'))
                    <x-alert :status="session('status')" class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-4 my-4 text-sm rounded-lg" role="alert" />
                @endif
               
                @if (!$tickets->isEmpty())
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 bg-white">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                {{ __('Thumbnail') }}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{ __('User') }}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{ __('Transaction number') }}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{ __('Type') }}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{ __('Created at') }}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{ __('Actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tickets as $ticket)
                            <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4">
                                    <a href="{{ $ticket->img_url }}" target="_blank">
                                        <img src="{{ $ticket->img_url }}" alt="Thumbnail" class="w-16 h-16 object-cover rounded-lg">
                                    </a>
                                </th>
                                <th scope="row" class="px-6 py-4">
                                    {{ $ticket->user->name }} <br> <small>{{ $ticket->user->email }}</small>
                                </th>
                                <th scope="row" class="px-6 py-4">
                                    {{ $ticket->transaction_number }}
                                </th>
                                <th scope="row" class="px-6 py-4">
                                    {{ $ticket->model_name }}
                                </th>
                                <th scope="row" class="px-6 py-4">
                                    {{ $ticket->created_at->format('Y-m-d H:i') }}
                                </th>
                                <td class="px-6 py-4 grid grid-cols-2 gap-2 justify-items-center">
                                    <a href="{{ route('panel.tickets.show', ['tenant' => tenant('id'), 'model' => $ticket->model_name, 'id' => $ticket->id]) }}"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ __('View') }}</a>
                                    <form method="post" action="{{ route('panel.tickets.destroy', ['tenant' => tenant('id'), 'model' => $ticket->model_name, 'id' => $ticket->id]) }}">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline" onclick="return confirm('{{ __('Are you sure to delete this?') }}')">
                                            {{ __('Delete') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="bg-white p-5">{{ $tickets->links() }}</div>

                @else
                <div class="bg-white p-5">
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
                        {{__('There are no items to display')}}
                    </h2>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-panel-layout>
