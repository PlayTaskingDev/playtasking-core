@props(['count','title','icon'])
<div
      class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6"
    >
      <div
        class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800"
      >
      @if ($icon == 'user-group')
        <x-heroicon-o-user-group class="w-5" />
      @elseif ($icon == 'ticket')
        <x-heroicon-o-ticket class="w-5" />
      @endif
      </div>

      <div class="flex items-end justify-between mt-5">
        <div>
          <span class="text-sm text-gray-500 dark:text-gray-400">{{ $title }}</span>
          <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">{{ $count }}</h4>
        </div>

        
      </div>
    </div>