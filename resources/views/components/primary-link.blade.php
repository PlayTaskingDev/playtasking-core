<a {{ $attributes->merge(['class' => 'uppercase p-4 border border-white rounded-sm font-bold focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150']) }} style="background-color:{{get_app_setting('primary_button_background')}} ; color:{{get_app_setting('primary_button_color')}} ;">
    {{ $slot }}
</a>
