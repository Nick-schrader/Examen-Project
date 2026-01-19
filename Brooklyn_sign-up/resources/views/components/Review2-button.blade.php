<div x-data="{
        showMenu: false,
        reviews: @json($reviews)
    }" class="p-6 text-gray-900">

    <!-- Open button -->
    <button
        @click.prevent="showMenu = true"
        type="button"
        class="bg-eisblue hover:bg-eisblue/80 text-white font-bold py-2 px-4 rounded"
    >
        Review Menu
    </button>

    <!-- Menu -->
    <div
        x-show="showMenu"
        @click.self="showMenu = false"
        class="fixed inset-0 z-[80] flex items-center justify-center bg-black bg-opacity-50"
    >
        <div class="bg-eisgeel p-6 rounded shadow-lg max-w-lg w-full mx-4 border-2 border-eisblue shadow-2xl rounded-3xl">
            <h3 class="text-lg font-semibold mb-4">Review Keuren</h3>

            <!-- Scrollable list of reviews -->
            <div class="max-h-96 overflow-y-auto space-y-4">
                <template x-for="review in reviews" :key="review.id">
                    <div class="p-3 border rounded bg-white">
                        <p><strong x-text="review.user"></strong>: <span x-text="review.comment"></span></p>
                        <div class="mt-2 space-x-2">
                            <button 
                                @click="review.approved = true" 
                                :class="review.approved === true ? 'bg-eisgroen' : 'bg-eisgroen hover:bg-eisgroen/80'"
                                class="text-white font-bold py-1 px-3 rounded">
                                Goedkeuren
                            </button>
                            <button 
                                @click="review.approved = false" 
                                :class="review.approved === false ? 'bg-red-500' : 'bg-red-500 hover:bg-red-500/80'"
                                class="text-white font-bold py-1 px-3 rounded">
                                Afkeuren
                            </button>
                            <span x-show="review.approved !== null" class="ml-2 font-semibold" 
                                  x-text="review.approved ? 'Goedgekeurd' : 'Afgekeurd'"></span>
                        </div>
                    </div>
                </template>
            </div>

            <div class="text-right mt-4">
                <button type="button" @click="showMenu = false" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">Sluit</button>
            </div>
        </div>
    </div>
</div>
