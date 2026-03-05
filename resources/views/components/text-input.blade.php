@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-xs']) }}>
<input {{ $attributes->merge([
'class' => 'bg-gray-200 text-black border-gray-400 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500'
]) }}>