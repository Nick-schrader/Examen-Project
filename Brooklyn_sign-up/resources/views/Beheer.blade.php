<x-app-layout>
    <x-beheerreviews :reviews="$approvedReviews" type="approved" />
    <x-beheerreviews :reviews="$flaggedReviews" type="flagged" />
</x-app-layout>