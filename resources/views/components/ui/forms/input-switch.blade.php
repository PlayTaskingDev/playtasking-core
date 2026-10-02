@props([
    'label',
    'name',
    'value' => null,
    'cols' => 0,
    'switcher' => null,
])

@php
    /*
     * Soportamos ambas formas que ya existen
     * actualmente en el proyecto:
     *
     * :value="$model->active"
     *
     * o
     *
     * value="1"
     * :switcher="$model->active"
     */
    $isChecked = old(
        $name,
        $switcher ?? $value ?? false
    );
@endphp

<div class="flex items-center col-span-{{ $cols }}">

    {{-- Si está apagado enviamos 0 --}}
    <input
        type="hidden"
        name="{{ $name }}"
        value="0"
    >

    {{-- Si está encendido enviamos 1 --}}
    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="checkbox"
        value="1"

        @checked(
            filter_var(
                $isChecked,
                FILTER_VALIDATE_BOOLEAN
            )
        )

        {{
            $attributes->merge([
                'class' =>
                    'w-4 h-4 border border-default-medium ' .
                    'rounded-xs bg-neutral-secondary-medium ' .
                    'focus:ring-2 focus:ring-brand-soft'
            ])
        }}
    >

    <label
        for="{{ $name }}"
        class="select-none ms-2 text-sm font-medium text-heading"
    >
        {{ $label }}
    </label>

    @if ($errors->get($name))

        <ul
            class="font-bold space-y-1 mt-2 text-sm
                   text-red-600 dark:text-red-500"
        >
            @foreach ((array) $errors->get($name) as $error)

                <li>
                    <p class="text-theme-xs text-error-500">
                        {{ $error }}
                    </p>
                </li>

            @endforeach
        </ul>

    @endif

</div>