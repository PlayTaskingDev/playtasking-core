<x-panel-layout>
    <x-slot name="title">
        {{ trans('Import Ticket Questions') }}
    </x-slot>
    <x-slot name="description">
        {{ trans('Import Ticket Questions') }}
    </x-slot>

    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ trans('Import Ticket Questions') }}
        </h1>
    </x-slot>

    <div class="py-6 mx-5">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 bg-white p-3 rounded shadow">
            <p class="text-right">
                <a class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                    href="{{ route('tickets.questions.sample', ['tenant' => tenant('id')]) }}">{{ __('Download Sample') }}</a>
            </p>
            <form method="POST" enctype="multipart/form-data" action="{{ route('tickets.questions.import', ['tenant' => tenant('id')]) }}">
                @csrf

                <div class="my-5">
                    <x-input-label for="file" :value="__('File')" />
                    <input
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                        aria-describedby="file_help" id="file" name="file" type="file">
                    <x-input-error class="mt-2" :messages="$errors->get('file')" />
                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_help">
                        {{ __('File must be XLS, XLSX or CSV.') }}
                    </div>
                </div>

                <div class="my-5">
                    <button id="submit-codes-btn" type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>

    @section('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                var button = document.getElementById('submit-codes-btn');
                button.addEventListener('click', function handleClick() {
                    button.classList.remove('bg-blue-700');
                    button.className = 'cursor-not-allowed bg-blue-400';
                    button.textContent = "{{ __('Loading file...') }}";
                });
            });
        </script>
    @endsection

</x-panel-layout>
