@props(['title', 'value', 'delta' => null, 'deltaLabel' => null, 'icon' => null, 'color' => 'blue'])

<div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow-sm hover:shadow-md transition-shadow">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">{{ $title }}</p>
            <p class="text-2xl font-semibold text-slate-900 dark:text-white">{{ $value }}</p>
            @if ($delta !== null)
                <p class="mt-1 text-sm font-medium
                    {{ str_starts_with($delta, '+') || str_starts_with($delta, '▲') ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}
                ">
                    @if ($icon === 'trending-up' || $icon === 'trending-down')
                        <svg class="inline h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon === 'trending-up' ? 'M7 7l5 5m0 0l5-5m-5 5v2m0-2V7m0 0l5 5m-5-5h-2m0-2H3' : 'M13 17l-5-5m0 0l5-5m-5 5v2m0-2V7m0 0l5 5m-5-5h-2m0-2H3' }}"></path>
                        </svg>
                    @endif
                    {{ $delta }}
                    @if ($deltaLabel)
                        <span class="text-slate-500 dark:text-slate-400 font-normal ml-1">{{ $deltaLabel }}</span>
                    @endif
                </p>
            @endif
        </div>
        <div class="flex-shrink-0">
            <div class="p-3 rounded-xl
                {{ $color === 'blue' ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }}
                {{ $color === 'emerald' ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : '' }}
                {{ $color === 'amber' ? 'bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400' : '' }}
                {{ $color === 'rose' ? 'bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400' : '' }}
                {{ $color === 'indigo' ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400' : '' }}
            ">
                {!! $icon ?? '' !!}
            </div>
        </div>
    </div>
</div>
