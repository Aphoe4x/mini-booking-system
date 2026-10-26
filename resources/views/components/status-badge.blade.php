@props(['status'])

@php
    $config = [
        'pending'    => ['bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300 border-yellow-200 dark:border-yellow-800', 'Pending'],
        'confirmed'  => ['bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800', 'Confirmed'],
        'cancelled'  => ['bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-slate-700', 'Cancelled'],
        'rejected'   => ['bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800', 'Rejected'],
        'completed'  => ['bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800', 'Completed'],
    ];
    [$classes, $label] = $config[$status] ?? ['bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-slate-700', ucfirst($status)];
@endphp

<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full border text-xs font-medium leading-tight uppercase tracking-wider {{ $classes }}">
    {{ $label }}
</span>
