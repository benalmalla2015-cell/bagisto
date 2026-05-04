@props(['text' => ''])

<div class="relative inline-block" x-data="{ open: false }">
    <button
        type="button"
        class="flex h-5 w-5 items-center justify-center rounded-full border border-gray-400 text-xs font-bold text-gray-500 hover:border-blue-500 hover:text-blue-500 focus:outline-none dark:border-gray-600 dark:text-gray-400"
        @click="open = !open"
        @click.away="open = false"
        aria-label="مساعدة"
    >?</button>

    <div
        x-show="open"
        x-transition
        class="absolute bottom-full right-0 z-50 mb-2 w-64 rounded-lg border bg-white p-3 text-sm text-gray-700 shadow-lg dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
    >
        {{ $text }}
    </div>
</div>
