@props(['label','name', 'cols' => 0 ])

<div class="flex items-center col-span-{{ $cols }}">
    <input id="{{ $name }}" name="{{ $name }}" type="checkbox" value="" 
    {{ 
        $attributes->merge([
        'class' => 'w-4 h-4 border border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft'
        ]) 
    }}
    >
    <label for="{{ $name }}" name="{{ $name }}" class="select-none ms-2 text-sm font-medium text-heading">{{ $label }}</label>
</div>