@props([
    'reviews',        // collectie reviews die getoond moet worden
    'type' => 'approved' // 'approved' of 'flagged'
])

<div class="max-w-7xl mx-auto px-6 py-8">
    @if($type === 'approved')
        <h2 class="text-2xl font-bold mb-4">Goedgekeurde Reviews</h2>

        <!-- Filter op rating -->
            <div x-data="{ ratingFilter: '' }" class="mb-6">
                <label for="filterRating" class="mr-2 font-semibold">Filter op rating:</label>
                <select id="filterRating" x-model="ratingFilter"
                        class="border border-gray-300 rounded px-3 py-2 w-40 min-w-[120px]">
                    <option value="">Alle</option>
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}">{{ $i }} sterren</option>
                    @endfor
                </select>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-4">
                @foreach($reviews as $review)
                    <div class="bg-eisgeel p-6 rounded-2xl shadow-lg flex flex-col justify-between border-2 border-eisblue"
                         x-show="ratingFilter === '' || ratingFilter == {{ $review->rating }}">
                        <p class="text-gray-900 font-semibold mb-4">{{ $review->reviewer_name }}</p>

                        <!-- Rating -->
                        <div class="flex items-center mb-4">
                            <div class="flex space-x-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.163c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.956c.3.922-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.175 0l-3.37 2.448c-.784.57-1.838-.196-1.539-1.118l1.287-3.956a1 1 0 00-.364-1.118L2.07 9.384c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.286-3.957z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="ml-2 text-sm text-gray-500">{{ $review->rating }}/5</span>
                        </div>

                        <p class="text-gray-700 flex-1">{{ $review->comment }}</p>

                        <!-- Verwijderen knop met margin -->
                        <div class="flex space-x-2 mt-4">
                            <form method="POST" action="{{ route('reviews.reject') }}">
                                @csrf
                                <input type="hidden" name="review_id" value="{{ $review->id }}">
                                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-500/80">Verwijderen</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    @elseif($type === 'flagged')
        <h2 class="text-2xl font-bold mb-4">Geflagde Reviews</h2>

        @if($reviews->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($reviews as $review)
                    <div class="bg-eisgeel p-6 rounded-2xl shadow-lg flex flex-col justify-between border-2 border-eisblue">
                        <p class="text-gray-900 font-semibold mb-4">{{ $review->reviewer_name }}</p>

                        <!-- Rating -->
                        <div class="flex items-center mb-4">
                            <div class="flex space-x-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.163c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.956c.3.922-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.175 0l-3.37 2.448c-.784.57-1.838-.196-1.539-1.118l1.287-3.956a1 1 0 00-.364-1.118L2.07 9.384c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.286-3.957z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="ml-2 text-sm text-gray-500">{{ $review->rating }}/5</span>
                        </div>

                        <p class="text-gray-700 flex-1 mb-4">{{ $review->comment }}</p>

                            @if(isset($review->reason))
                                <p class="text-red-600 text-sm mt-2">Reden: {{ $review->reason }}</p>
                            @endif

                        <!-- Goedkeuren / Afkeuren knoppen -->
                        <div class="flex space-x-2 mt-4">
                            <form method="POST" action="{{ route('reviews.approve') }}">
                                @csrf
                                <input type="hidden" name="review_id" value="{{ $review->id }}">
                                <button type="submit" class="px-4 py-2 bg-eisgroen text-white rounded hover:bg-eisgroen/80">Goedkeuren</button>
                            </form>
                            <form method="POST" action="{{ route('reviews.reject') }}">
                                @csrf
                                <input type="hidden" name="review_id" value="{{ $review->id }}">
                                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-500/80">Afkeuren</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 mt-4">Er zijn geen geflagde reviews.</p>
        @endif
    @endif
</div>
