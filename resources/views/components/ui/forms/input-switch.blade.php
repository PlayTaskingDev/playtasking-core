@props(['label','switcher','name','model' => '', 'cols' => 0 ])

<div x-data="{ {{ $name }}: {{ $switcher }} }" id="data-{{ $name }}" class="col-span-{{ $cols }}">
    <label for="{{ $name }}"
        class="flex cursor-pointer items-center gap-3 text-sm font-medium text-gray-700 select-none dark:text-gray-400">
        <div class="relative">
            @php
                $setModel = '';
                if($model) $setModel = "x-model=$model";
            @endphp
            <input type="checkbox" id="{{ $name }}" :value="{{ $name }} ? 1:0" name="{{ $name }}" {{ $setModel }} class="sr-only" @change="{{ $name }} = !{{ $name }}" />
            <div class="block h-6 w-11 rounded-full"
                :class="{{ $name }} ? 'bg-brand-500 dark:bg-brand-500' : 'bg-gray-200 dark:bg-white/10'">
            </div>
            <div :class="{{ $name }} ? 'translate-x-full' : 'translate-x-0'"
                class="shadow-theme-sm absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white duration-300 ease-linear">
            </div>
        </div>
        {{ $label }}
    </label>
</div>