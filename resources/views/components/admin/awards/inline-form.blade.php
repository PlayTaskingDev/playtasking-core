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
    <div class="mt-6 border-t border-gray-200 pt-6 dark:border-gray-700">

    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
        {{ __('Award Codes') }}
    </h3>

    @if ($award)

        <div class="mt-3 flex flex-wrap gap-3 text-sm">

            <span class="rounded-lg bg-gray-100 px-3 py-2 dark:bg-gray-800">
                {{ __('Available') }}:
                <strong>
                    {{ $award->codes_available_count ?? 0 }}
                </strong>
            </span>

            <span class="rounded-lg bg-gray-100 px-3 py-2 dark:bg-gray-800">
                {{ __('Delivered') }}:
                <strong>
                    {{ $award->codes_delivered_count ?? 0 }}
                </strong>
            </span>

        </div>

    @endif

    <div class="mt-5">

        <label class="flex items-center gap-3">

            <input
                type="checkbox"
                name="generate_award_codes"
                value="1"
                @checked(old('generate_award_codes'))
                class="rounded border-gray-300"
            >

            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ __('Generate random codes when saving') }}
            </span>

        </label>

    </div>

    <div class="mt-4">

        <x-input-label
            for="award_codes_quantity"
            :value="__('Quantity of codes')"
        />

        <input
            type="number"
            id="award_codes_quantity"
            name="award_codes_quantity"
            min="1"
            max="20000"
            value="{{ old('award_codes_quantity', 2000) }}"
            class="mt-1 block w-full rounded-lg border border-gray-300
                   bg-gray-50 p-2.5 text-sm text-gray-900
                   dark:border-gray-600 dark:bg-gray-700 dark:text-white"
        >

        <x-input-error
            :messages="$errors->get('award_codes_quantity')"
            class="mt-2"
        />

    </div>

    @if ($award)

        <div class="mt-5">

            <a
                href="{{ route('awards.codes.show', [
                    'tenant' => tenant('id'),
                    'award' => $award
                ]) }}"
                class="text-sm font-medium text-blue-600 hover:underline"
            >
                {{ __('Import or manage existing codes') }}
            </a>

        </div>

    @endif

</div>
</div>