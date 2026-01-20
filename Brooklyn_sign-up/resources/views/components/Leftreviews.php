    <?php
    // Filter alleen goedgekeurde reviews
    $approvedReviews = $reviews->filter(fn($r) => $r->status === 'approved');
    ?>

    <?php if ($approvedReviews->count() > 0): ?>
        <div class="max-w-7xl mx-auto px-6 py-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php foreach ($approvedReviews as $review): ?>
                    <div class="bg-eisgeel p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-shadow duration-300 flex flex-col justify-between border-2 border-eisblue">
                        <!-- Naam van de reviewer -->
                        <p class="text-gray-900 font-semibold mb-4"><?= htmlspecialchars($review->reviewer_name) ?></p>
                        <!-- Sterren -->
                        <div class="flex items-center mb-4">
                            <div class="flex space-x-1">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <svg class="w-5 h-5 <?= $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' ?>" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.163c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.956c.3.922-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.175 0l-3.37 2.448c-.784.57-1.838-.196-1.539-1.118l1.287-3.956a1 1 0 00-.364-1.118L2.07 9.384c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.286-3.957z"/>
                                    </svg>
                                <?php endfor; ?>
                            </div>
                            <span class="ml-2 text-sm text-gray-500"><?= $review->rating ?>/5</span>
                        </div>
                        <!-- Review -->
                        <p class="text-gray-700 flex-1"><?= htmlspecialchars($review->comment) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php else: ?>
        <p class="text-center text-gray-500 mt-8">Er zijn nog geen reviews geschreven.</p>
    <?php endif; ?>