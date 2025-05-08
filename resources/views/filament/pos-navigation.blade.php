<a
    href="{{ $url }}"
    @class([
        'flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition',
        'hover:bg-gray-500/5 focus:bg-gray-500/5' => ! $active,
        'dark:text-primary-500 bg-primary-500/10 text-primary-600' => $active,
    ])
>
    <x-heroicon-o-shopping-cart class="h-5 w-5 text-gray-400 shrink-0" />

    <span class="flex-1">
        POS Terminal
    </span>
</a>