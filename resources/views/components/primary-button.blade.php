<button {{ $attributes->merge(['type' => 'submit', 'class' => 'p-2 border border-transparent rounded-lg font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150']) }} style="background-color:{{get_app_setting('primary_button_background')}} ; color:{{get_app_setting('primary_button_color')}} ;">
    {{ $slot }}
</button>
