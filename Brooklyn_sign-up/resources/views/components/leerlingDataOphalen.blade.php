{{-- resources/views/components/leerlingDataOphalen.blade.php --}}
@props(['les' => null])

<div class="py-2 border-t flex flex-col gap-2">

    @if($les)
        <div><h1><strong>Leerling ID:</strong> {{ $les->leerling_id }}</h1></div>
        <div><h1><strong>Datum en Tijd:</strong> {{ $les->datum_en_tijd }}</h1></div>
        <div><h1><strong>Auto:</strong> {{ $les->auto }}</h1></div>
    @else
        <div><h1>Geen les ingepland op dit tijdstip.</h1></div>
    @endif

</div>
