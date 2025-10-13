@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'font-bold space-y-1 mt-2 text-sm text-red-600 dark:text-red-500']) }} style="{{get_app_setting('primary_button_color')}}">
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
