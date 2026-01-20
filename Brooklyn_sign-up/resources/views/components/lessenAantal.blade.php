@php
    $selectedUserId = request('user') ?? auth()->id();
    $selectedUser = \App\Models\User::with('strippenkaart')->find($selectedUserId);
@endphp

{{-- Aantal resterende lessen van de geselecteerde gebruiker --}}
<div class="w-[240px] h-10 bg-eisgeel rounded-md text-lg font-bold text-center flex items-center justify-center">
    <h1>Lessen: {{ $selectedUser->strippenkaart->tegoed ?? '0' }}</h1>
</div>