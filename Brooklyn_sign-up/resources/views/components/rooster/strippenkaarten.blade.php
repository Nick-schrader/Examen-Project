@php
	$leerling = auth()->user();
	$strippenkaarten = \App\Models\Strippenkaart::where('leerling_id', $leerling->id)->get();
@endphp

@if($strippenkaarten->count())
	<div class="mb-4">
		<h3 class="mb-2 text-lg font-semibold">Strippenkaarten</h3>
		<ul class="space-y-1">
			@foreach($strippenkaarten as $kaart)
				<li class="flex items-center justify-between p-2 bg-gray-100 rounded">
					<span>Strippenkaart tot {{ $kaart->verval_datum }}</span>
					<span class="font-bold text-eisblue">{{ $kaart->tegoed }} les(sen) over</span>
				</li>
			@endforeach
		</ul>
	</div>
@else
	<div class="mb-4 text-red-500">Je hebt geen actieve strippenkaarten.</div>
@endif
