<x-app-layout>
<div class="min-h-screen bg-white flex flex-col items-center py-10">

    <!-- Instructor Selector -->
    <x-user-selector type="2" :selected-user-id="request('instructor')" />
    
    <!-- Agenda Component -->
    <x-agenda />

</div>
</x-app-layout>