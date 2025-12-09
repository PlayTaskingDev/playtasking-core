<x-panel-layout>
    <x-slot name="title">
        {{ $smash_object->id == null ? trans('Create') . ' ' . trans('Object') : $smash_object->name }}
    </x-slot>
    <x-slot name="description">
        {{ $smash_object->id == null ? '' : $smash_object->name }}
    </x-slot>

    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $smash_object->id == null ? trans('Create') : trans('Edit') }} {{ __('Object') }}
        </h1>
    </x-slot>

    @if (session('status'))
        <div class="mx-5">
            <x-alert :status="session('status')" class="max-w-2xl mx-auto sm:px-6 lg:px-8 p-4 mt-4 text-sm rounded-lg" role="alert" />
        </div>
    @endif

    <div class="py-6 mx-5">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 bg-white p-3 rounded shadow">
            <form method="POST" enctype="multipart/form-data"
                action="{{ $smash_object->id == null ? route('smash_objects.store', ['tenant' => tenant('id')]) : route('smash_objects.update', ['tenant' => tenant('id'), 'smash_object' => $smash_object]) }}">
                @csrf
                @isset($smash_object->id)
                    @method('PATCH')
                    <input type="hidden" name="id" value="{{ $smash_object->id }}">
                @endisset

                <input type="hidden" name="smash_game_id" value="{{ !is_null($smash_object->id) ? $smash_object->smash_game_id : $smash_game_id }}">

                <div class="my-5">
                    <x-input-label for="object_image" :value="__('Object Image')" />
                    @if (!is_null($smash_object->id) && $smash_object->object_image)
                        <img src="{{$smash_object->object_image}}" alt="{{__('Object Image')}}" title="{{__('Object Image')}}" class="my-5">
                    @endif
                    <input
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                        aria-describedby="object_image_help" id="object_image" name="object_image" type="file">
                    <x-input-error class="mt-2" :messages="$errors->get('object_image')" />
                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="object_image_help">
                        {{__('Image must be less than 2MB and JPG or PNG format.')}} <br>
                    </div>
                </div>

                <div class="flex my-5">
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">{{ __('Save') }}</button>
                    @if (!is_null($smash_object->id))
                        <a href="{{ route('smash_objects.edit', ['tenant' => tenant('id'), 'smash_object' => $smash_object]) }}"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">{{ __('Back') }}</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</x-panel-layout>
