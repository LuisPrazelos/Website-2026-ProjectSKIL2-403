<div class="space-y-4">
    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Recent Reviews</h2>

    @if($reviews->isEmpty())
        <p class="text-gray-500 dark:text-gray-400">No reviews yet.</p>
    @else
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach($reviews as $review)
                <div class="rounded-xl border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-sm font-bold text-gray-600 dark:text-gray-300">
                                {{ substr($review->user->name ?? 'U', 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $review->user->name ?? 'Unknown User' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($review->date)->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 text-yellow-500">
                            <span class="font-bold">{{ $review->score }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>

                    <p class="text-sm text-gray-700 dark:text-gray-300 line-clamp-3 mb-3">
                        {{ $review->content }}
                    </p>

                    @if($review->dessert)
                        <div class="text-xs text-gray-500 dark:text-gray-400 border-t border-gray-100 dark:border-gray-700 pt-2 mt-2">
                            Review for: <span class="font-medium text-indigo-600 dark:text-indigo-400">{{ $review->dessert->name }}</span>
                        </div>
                    @elseif($review->workshop)
                        <div class="text-xs text-gray-500 dark:text-gray-400 border-t border-gray-100 dark:border-gray-700 pt-2 mt-2">
                            Review for: <span class="font-medium text-indigo-600 dark:text-indigo-400">{{ $review->workshop->name }}</span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
