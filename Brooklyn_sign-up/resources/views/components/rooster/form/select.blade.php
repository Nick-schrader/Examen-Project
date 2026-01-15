@php
    $id = isset($id) ? $id : $name;
    $wat = isset($wat) ? $wat : $name;
@endphp

<div class="flex flex-col gap-2">
    <label for="{{ $id }}" class="font-semibold text-gray-700">Kies een {{ $wat }}</label>
    <select id="{{ $id }}" name="{{ $name }}" class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
        <option value="">Selecteer een {{ $wat }}</option>
        {{ $slot }}
    </select>
</div>
