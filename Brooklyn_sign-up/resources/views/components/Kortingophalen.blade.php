<div x-data="{
    kortingen: [],
    fetchKortingen() {
        fetch('/korting/{{ $selectedUserId }}')
            .then(res => res.json())
            .then(data => this.kortingen = data);
    },

    verwijderKorting(id) {
        fetch(`/korting/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
        })
        .then(res => {
            if (res.ok) {
                this.kortingen = this.kortingen.filter(k => k.id !== id);
            } else {
                alert('Kon de korting niet verwijderen.');
            }
        });
    }

}" x-init="fetchKortingen()">
    <h3 class="font-bold mb-2">Kortingen</h3>

    <template x-if="kortingen.length === 0">
        <p class="text-gray-500">Geen kortingen gevonden voor deze gebruiker.</p>
    </template>

    <ul class="space-y-2" x-show="kortingen.length > 0">
        <template x-for="korting in kortingen" :key="korting.id">
            <li class="border-b border-gray-200 pb-1 flex justify-between items-center">
                <div>
                    <span class="font-semibold" x-text="korting.percentage + '%'"></span>
                    - <span class="text-gray-700" x-text="korting.reason"></span>
                </div>
                <button 
                    @click="verwijderKorting(korting.id)" 
                    class="bg-eisgroen text-white px-2 py-1 rounded text-sm hover:bg-eisgroen/80">
                    Toegepast
                </button>
            </li>
        </template>
    </ul>
</div>
