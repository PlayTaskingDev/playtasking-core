@props(['label','name', 'cols' => 0, 'switcher'=> 0 ])

<div class="flex items-center col-span-{{ $cols }}">
    <input id="{{ $name }}" name="{{ $name }}" type="checkbox"  @checked(old('is_active', $switcher ?? false)) value="1" 
    {{ 
        $attributes->merge([
        'class' => 'w-4 h-4 border border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft'
        ]) 
    }}
    >
    <label for="{{ $name }}" class="select-none ms-2 text-sm font-medium text-heading">{{ $label }}</label>
    @if ($errors->get($name))
        <ul {{ $attributes->merge(['class' => 'font-bold space-y-1 mt-2 text-sm text-red-600 dark:text-red-500']) }} >
            @foreach ((array) $errors->get($name) as $error)
                <li><p class="text-theme-xs text-error-500">{{ $error }}</p></li>
            @endforeach
        </ul>
    @endif
</div>