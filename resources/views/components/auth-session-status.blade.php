@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400']) }}>
        {{ $status }}
    </div>
@endif
