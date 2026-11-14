@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-white text-slate-900 placeholder-slate-400 border-gray-300 dark:bg-slate-900 dark:text-slate-100 dark:placeholder-slate-500 dark:border-slate-600 focus:border-indigo-500 dark:focus:border-blue-400 focus:ring-indigo-500 dark:focus:ring-blue-400 rounded-md shadow-sm']) }}>
