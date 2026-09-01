@props([
    'award' => null,
])

<div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/3 mt-6">

    <div class="mb-5">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">
            {{ __('Award') }}
        </h2>

        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            {{ __('Configure the prize associated with this game.') }}
        </p>
    </div>

    <div class="space-y-5">

        {{-- Título --}}
        <div>
            <x-input-label
                for="award_title"
                :value="__('Award Title')"
            />

            <textarea
                id="award_title"
                name="award_title"
                rows="4"
                class="tinymce-component block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300
                       focus:ring-blue-500 focus:border-blue-500
                       dark:bg-gray-700 dark:border-gray-600
                       dark:placeholder-gray-400 dark:text-white"
            >{{ old('award_title', $award?->title) }}</textarea>

            <x-input-error
                :messages="$errors->get('award_title')"
                class="mt-2"
            />
        </div>

        {{-- Contenido --}}
        <div>
            <x-input-label
                for="award_content"
                :value="__('Award Content')"
            />

            <textarea
                id="award_content"
                name="award_content"
                rows="10"
                class="tinymce-component block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300
                       focus:ring-blue-500 focus:border-blue-500
                       dark:bg-gray-700 dark:border-gray-600
                       dark:placeholder-gray-400 dark:text-white"
            >{{ old('award_content', $award?->content) }}</textarea>

            <x-input-error
                :messages="$errors->get('award_content')"
                class="mt-2"
            />
        </div>

    </div>

</div>