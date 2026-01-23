@props(['label','placeholder','name', 'value' => null, 'cols' => 0 ])

<div class="formfield">
    <label for="{{ $name }}">{{ $label }}</label>
    <input type="datetime-local" name="{{ $name }}" id="{{ $name }}" value="{{ $value ?? now()->format('Y-m-d\TH:i') }}"
        {{ 
            $attributes->merge([
            'class' => 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30'
            ]) 
        }}
    />
  </div>