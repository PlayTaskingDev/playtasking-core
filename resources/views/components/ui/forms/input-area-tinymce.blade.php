@props(['label','value','name', 'cols' => 0])
<div class="col-span-{{ $cols }}">
    <label class="mb-1.5 block text-normal font-medium text-gray-700 dark:text-gray-400" for="{{ $name }}">
            {{ $label }}
        </label>
    <textarea id="{{ $name }}" name="{{ $name }}" rows="10"
    {{ 
        $attributes->merge([
        'class' => 'tinymce-component block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500'
        ]) 
    }}>{{ old($name, $value)  }}</textarea>
    @if ($errors->get($name))
        <ul {{ $attributes->merge(['class' => 'font-bold space-y-1 mt-2 text-sm text-red-600 dark:text-red-500']) }} >
            @foreach ((array) $errors->get($name) as $error)
                <li><p class="text-theme-xs text-error-500">{{ $error }}</p></li>
            @endforeach
        </ul>
    @endif
</div>