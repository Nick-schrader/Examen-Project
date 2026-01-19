@php
    $isButton = isset($type) && $type !== null;
    $tag = $isButton ? 'button' : 'a';

    $componentAttributes = $attributes->merge([
        'class' => 'px-6 py-2 font-bold text-white transition-all duration-200 rounded-full shadow-xl bg-eisblue hover:bg-eisgroen focus:outline-none focus:ring-2 focus:ring-eisblue focus:ring-opacity-50',
    ]);

    if ($isButton) {
        $componentAttributes = $componentAttributes->merge([
            'type' => isset($type) ? $type : '',
        ]);
    } else {
        $componentAttributes = $componentAttributes->merge([
            'href' => isset($href) ? $href : '',
        ]);
    }
@endphp

<{{ $tag }} {{ $componentAttributes }}
    style="font-family: 'Inter', 'Segoe UI', Arial, sans-serif;">
    <span class="tracking-wide">
        {{ $slot }}
    </span>
</{{ $tag }}>
