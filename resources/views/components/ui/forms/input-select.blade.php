@props(['label','placeholder','options' => [],'name', 'value', 'cols' => 0])

<div class="col-span-{{ $cols }}">
        <label for="{{ $name }}" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            {{ $label }}
        </label>
        <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
            <select
                id="{{ $name }}"
                name="{{ $name }}"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                :class="isOptionSelected && 'text-gray-800 dark:text-white/90'" @change="isOptionSelected = true">
                <option value="" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                    Select Option
                </option>
                @if ($options)
                @foreach ($options as $option )
                    <option value="{{ $option->id }}"
                        @if (old($name, $value) == $option->id)
                            selected
                        @endif
                         class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                        {{ $option->name }}
                    </option>
                @endforeach
                @endif
            </select>
            <span
                class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-700 dark:text-gray-400">
                <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
        </div>
        @if ($errors->get($name))
            <ul {{ $attributes->merge(['class' => 'font-bold space-y-1 mt-2 text-sm text-red-600 dark:text-red-500']) }} >
                @foreach ((array) $errors->get($name) as $error)
                    <li><p class="text-theme-xs text-error-500">{{ $error }}</p></li>
                @endforeach
            </ul>
        @endif
    </div>