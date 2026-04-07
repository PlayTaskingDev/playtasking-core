@props(['label','placeholder','name', 'value', 'cols' => 0 , 'charcount' => false])
<div class="my-5 col-span-{{ $cols }}">
    <label for="{{ $name }}" >{{ $label }}</label>
    <textarea id="{{ $name }}" name="{{ $name }}" rows="10"
        placeholder="{{ $placeholder }}"
        {{ 
            $attributes->merge([
            'class' => 'block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500'
            ]) 
        }}>{{ $value }}</textarea>
        @if($charcount) <small><span id="contador">0</span> / 600 caracteres</small>@endif
    @if ($errors->get($name))
        <ul {{ $attributes->merge(['class' => 'font-bold space-y-1 mt-2 text-sm text-red-600 dark:text-red-500']) }} >
            @foreach ((array) $errors->get($name) as $error)
                <li><p class="text-theme-xs text-error-500">{{ $error }}</p></li>
            @endforeach
        </ul>
    @endif
</div>
@if($charcount) 
    <script>
        const textarea = document.getElementById('{{ $name }}');
        const contador = document.getElementById('contador');
        const maxCaracteres = 600;

        textarea.addEventListener('input', function() {
            const longitudActual = textarea.value.length;
            contador.innerText = longitudActual;
        });
    </script>
@endif