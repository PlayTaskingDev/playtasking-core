@props(['pageTitle' => 'Page', 'desc' => '', 'modalId' =>'', 'isBtn' => '0', 'titleBtn' => '','routeDataBtn' => '', 'routeBtn' => ''])

<div class="flex flex-wrap items-center justify-between gap-3 mb-2">
    <div class="py-3">
        <h3 class="text-base font-bold text-gray-800 dark:text-white/90">
             {{ $pageTitle }}
        </h3>
        @if($desc)
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ $desc }}
            </p>
        @endif
    </div>
    @if ($isBtn)
        <a
                data-action="create"
                data-modal-target="{{ $modalId }}"
                data-modal-toggle="{{ $modalId }}"
                data-save-route="{{ $routeDataBtn }}"
                href="{{ $routeBtn ? $routeBtn:'#' }}"
                class="btn bg-brand-500 hover:bg-brand-600 flex w-full justify-center rounded-lg px-4 py-2.5 text-sm font-medium text-white sm:w-auto cursor-pointer" aria-label="{{ $titleBtn }}">
            {{ $titleBtn }}
        </a>
    @endif
    <!--Add Button-->
</div>
