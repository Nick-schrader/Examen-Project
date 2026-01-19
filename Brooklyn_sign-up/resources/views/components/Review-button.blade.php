<!-- review popup code -->
<div x-data="{ showPopUp: false, rating: 0 }" class="p-6 text-gray-900">
    <!-- Open button -->
    <button
        @click.prevent="showPopUp = true"
        type="button"
        class="bg-eisblue hover:bg-eisblue/80 text-white font-bold py-2 px-4 rounded"
    >
        Review
    </button>

    <!-- Popup -->
    <div
        x-show="showPopUp"
        @click.self="showPopUp = false"
        class="fixed inset-0 z-[80] flex items-center justify-center bg-black bg-opacity-50"
    >
        <div class="bg-eisgeel p-6 rounded shadow-lg max-w-md w-full mx-4 border-2 border-eisblue shadow-2xl rounded-3xl">
            <h3 class="text-lg font-semibold mb-2">Laat je review van de les achter</h3>

            <!-- Meldingen -->
            @if(session('error'))
                <div class="mb-4 p-2 bg-red-200 text-red-800 rounded">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="mb-4 p-2 bg-green-200 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('review.store') }}">
                @csrf

                <input type="hidden" name="rooster_item_id" value="{{ $lesson->id ?? 0 }}">

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Beoordeling:</label>
                    <div class="flex items-center space-x-2 text-2xl">
                        <button type="button" class="star text-gray-300 hover:text-yellow-400" :class="{'text-yellow-400': rating >= 1}" @click="rating = 1">★</button>
                        <button type="button" class="star text-gray-300 hover:text-yellow-400" :class="{'text-yellow-400': rating >= 2}" @click="rating = 2">★</button>
                        <button type="button" class="star text-gray-300 hover:text-yellow-400" :class="{'text-yellow-400': rating >= 3}" @click="rating = 3">★</button>
                        <button type="button" class="star text-gray-300 hover:text-yellow-400" :class="{'text-yellow-400': rating >= 4}" @click="rating = 4">★</button>
                        <button type="button" class="star text-gray-300 hover:text-yellow-400" :class="{'text-yellow-400': rating >= 5}" @click="rating = 5">★</button>
                    </div>
                    <input type="hidden" name="rating" :value="rating">
                </div>

                <div class="mb-4">
                    <label for="review" class="block text-gray-700 font-bold mb-2">Je review:</label>
                    <textarea id="review" name="comment" rows="4" class="w-full px-3 py-2 border rounded" required></textarea>
                </div>

                <div class="text-right space-x-2">
                    <button type="submit" class="bg-eisgroen hover:bg-eisgroen/80 text-white font-bold py-2 px-4 rounded">Verzenden</button>
                    <button type="button" @click.prevent="showPopUp = false" class="bg-red-500 hover:bg-red-500/80 text-white font-bold py-2 px-4 rounded">Sluit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Eind review popup code -->
