@push('styles')
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

<div class="flex flex-col gap-2">
	<label for="time" class="font-semibold text-gray-700">Kies een tijd</label>
	<input
		id="time"
		name="time"
		type="text"
		class="px-4 py-2 transition duration-150 ease-in-out border border-gray-300 rounded-lg shadow-sm timepicker focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
		placeholder="Selecteer een tijd"
		autocomplete="off"
	    value="{{ $value ?? '' }}"
    >
</div>

@push('scripts')
	<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			   flatpickr('.timepicker', {
				   enableTime: true,
				   noCalendar: true,
				   dateFormat: "H:i:ss",
				   time_24hr: true,
				   minuteIncrement: 60,
				   onValueUpdate: function(selectedDates, dateStr, instance) {
					   // If user types a value, force minutes to 00
					   if (dateStr && dateStr.match(/^\d{1,2}:(\d{2})$/)) {
						   let [h, m] = dateStr.split(":");
						   if (m !== "00") {
							   instance.setDate(h.padStart(2, '0') + ":00", true);
						   }
					   }
				   }
			   });
		});
	</script>
@endpush
