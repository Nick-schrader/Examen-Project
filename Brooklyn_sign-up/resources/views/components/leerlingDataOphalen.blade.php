@props(['les' => null])

<div class="py-2 border-t flex flex-col gap-2">

    @if($les)
        <div><strong>Leerling:</strong> {{ $les->leerling_naam }}</div>
        <div><strong>Datum en Tijd:</strong> {{ $les->datum_en_tijd }}</div>
        <div><strong>Auto:</strong> {{ $les->autos_merk }}</div>
        <div><strong>Kenteken:</strong> {{ $les->kenteken }}</div>
    @else
        <div>Geen les ingepland op dit tijdstip.</div>
    @endif

</div>
