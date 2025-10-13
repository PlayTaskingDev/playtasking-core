<x-panel-layout>
    <x-slot name="title">
        {{ trans('Import Codes to') . ' ' . $award->awardable->title }}
    </x-slot>
    <x-slot name="description">
        {{ trans('Import Codes to') . ' ' . $award->awardable->description }}
    </x-slot>

    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ trans('Import Codes to') . ' ' . $award->awardable->title }}
        </h1>
    </x-slot>

    @if ($errors->any())
    <div class="alert alert-danger">
        <h4>Validation Errors:</h4>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <div class="py-6 mx-5">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 bg-white p-3 rounded shadow">
            <form method="POST" enctype="multipart/form-data" action="{{ $award->model_type != 'code' || ($award->model_type == 'code' && $award->awardable->type == 'unique_external') ? route('awards.codes.import', ['tenant' => tenant('id')]) : route('awards.create_award_codes', ['tenant' => tenant('id')]) }}">
                @csrf

                <input type="hidden" name="award_id" id="award_id" value="{{ $award->id }}" />

                @if ($award->model_type != 'code' || ($award->model_type == 'code' && $award->awardable->type == 'unique_external'))
                <div class="my-5">
                    <p class="text-right">
                        <a class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                            href="{{ route('awards.codes.sample', ['tenant' => tenant('id')]) }}">{{ __('Download Sample') }}</a>
                    </p>
                    <x-input-label for="file" :value="__('File')" />
                    <input
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                        aria-describedby="file_help" id="file" name="file" type="file">
                    <x-input-error class="mt-2" :messages="$errors->get('file')" />
                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_help">
                        {{ __('File must be XLS, XLSX or CSV.') }}
                    </div>
                </div>
                @else
                <input type="hidden" name="coupon_type" value="{{$award->awardable->type}}">
                <div class="my-5">
                    @if ($award->awardable->type == 'multiple')
                    <x-input-label for="code" :value="__('Code string')" />
                    <x-text-input id="code" class="block mt-1 w-full" type="text" name="code"
                        :value="old('code', $award->awardable->code)" required autofocus autocomplete="code" />
                    <x-input-error :messages="$errors->get('code')" class="mt-2" />
                    @else
                    <x-input-label for="quantity" :value="__('Quantity')" />
                    <x-text-input id="quantity" class="block mt-1 w-full" type="number" name="quantity"
                        :value="old('quantity')" required autofocus autocomplete="quantity" />
                    <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                    @endif
                </div>
                <div class="my-5">
                    <x-input-label for="product" :value="__('Product')" />
                    <x-text-input id="product" class="block mt-1 w-full" type="text" name="product"
                        :value="old('product', $award->awardable->product)" required autofocus autocomplete="product" />
                    <x-input-error :messages="$errors->get('product')" class="mt-2" />
                </div>
                <div class="my-5">
                    <x-input-label for="validity" :value="__('Validity')" />
                    <x-text-input id="validity" class="block mt-1 w-full" type="text" name="validity"
                        :value="old('validity', $award->awardable->validity)" required autofocus autocomplete="validity" />
                    <x-input-error :messages="$errors->get('validity')" class="mt-2" />

                    <p class="text-right my-3">
                        <a class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                            href="{{ route('codes.download', ['tenant' => tenant('id')]) }}">{{ __('Download Codes') }}</a>
                    </p>
                </div>
                @endif

                <div class="my-5">
                    <button id="submit-codes-btn" type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">{{ __('Create codes') }}</button>
                </div>
            </form>
        </div>
    </div>

    @section('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                var button = document.getElementById('submit-codes-btn');
                if (button.length > 0) {
                    button.addEventListener('click', function handleClick() {
                        button.classList.remove('bg-blue-700');
                        button.className = 'cursor-not-allowed bg-blue-400';
                        button.textContent = "{{ __('Loading codes...') }}";
                    });
                }
            });
        </script>
    @endsection

</x-panel-layout>
