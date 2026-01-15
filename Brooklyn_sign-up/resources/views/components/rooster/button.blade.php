@php
    $isButton = isset($type) && $type !== null;
    $tag = $isButton ? 'button' : 'a';
    $extraAttributes = '';
    if ($isButton) {
        $extraAttributes = 'type="' . e(isset($type) ? $type : '') . '"';
    } else {
        $extraAttributes = 'href="' . e(isset($href) ? $href : '') . '"';
    }
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => 'px-6 py-2 font-bold text-white transition-all duration-200 rounded-full shadow-xl bg-eisblue hover:bg-eisgroen focus:outline-none focus:ring-2 focus:ring-eisblue focus:ring-opacity-50']) }} {!! $extraAttributes !!}
    style="font-family: 'Inter', 'Segoe UI', Arial, sans-serif;">
    <span class="tracking-wide">
        {{ $slot }}
    </span>
</{{ $tag }}>
