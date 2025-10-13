@props(['status'])

@switch($status)
    @case('error')
        @php
            $classes = 'text-red-800 bg-red-50 dark:bg-gray-800 dark:text-red-400';
            $message = trans('Error on responses submission. Try again.');
        @endphp
    @break

    @case('success')
        @php
            $classes = 'text-green-800 bg-green-50 dark:bg-gray-800 dark:text-green-400';
            $message = trans('Responses have been submitted. Good luck!');
        @endphp
    @break

    @case('not_active')
        @php
            $classes = 'text-red-800 bg-red-50 dark:bg-gray-800 dark:text-red-400';
            $message = trans('Sorry, the quiz is not active now.');
        @endphp
    @break

    @case('has_quizzed')
        @php
            $classes = 'text-red-800 bg-red-50 dark:bg-gray-800 dark:text-red-400';
            $message = trans('You have been participated in this quiz.');
        @endphp
    @break

    @default
        @php
            $classes = 'text-green-800 bg-green-50 dark:bg-gray-800 dark:text-green-400';
            $message = $status;
        @endphp
@endswitch

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $message }}
</div>
