<x-mail::layout>
{{-- Header --}}
<x-slot:header>
<x-mail::header :url="route('welcome', ['tenant' => tenant('id')])">
<img width="200" src="{{ get_app_setting('app_logo') }}" alt="{{ get_app_setting('app_name') }}">
</x-mail::header>
</x-slot:header>

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
<x-mail::subcopy>
{{ $subcopy }}
</x-mail::subcopy>
</x-slot:subcopy>
@endisset

{{-- Footer --}}
<x-slot:footer>
<x-mail::footer>
© {{ date('Y') }} {{ get_app_setting('app_name') }}. @lang('All rights reserved.')
</x-mail::footer>
</x-slot:footer>
</x-mail::layout>
