@push('styles')
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

<div class="flex flex-col gap-2">
	<label for="date" class="font-semibold text-gray-700">Kies een datum</label>
	<input
		id="date"
		name="date"
		type="text"
		class="px-4 py-2 transition duration-150 ease-in-out border border-gray-300 rounded-lg shadow-sm datepicker focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
		placeholder="Selecteer een datum"
        autocomplete="off"
        value="{{ $value ?? '' }}"
    >
</div>

@push('scripts')
	<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			flatpickr('.datepicker', {
				dateFormat: "d/m/Y",
				minDate: "today"
			});
		});
	</script>
@endpush
