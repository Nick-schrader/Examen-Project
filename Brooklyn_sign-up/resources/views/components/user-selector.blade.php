@props(['selectedUserId' => null, 'modal' => null])

@php
    $label = auth()->user()->type === 3
        ? 'een instructeur'
        : 'een leerling';

    // Geen fallback naar auth()->id()
    $currentUser = request('user');
@endphp

<div class="mb-6 w-full flex flex-col items-center">

    <form method="GET" action="" class="flex flex-col items-center w-full">

        {{-- Modal parameter meesturen zodat modal open blijft --}}
        @if($modal)
            <input type="hidden" name="modal" value="{{ $modal }}">
        @endif

        <label for="user" class="mb-2 font-semibold text-eisblue">
            Kies {{ $label }}:
        </label>

        <select name="user" id="user"
                class="px-4 py-2 rounded shadow w-72 text-eisblue"
                onchange="this.form.submit()">

            {{-- ⭐ Lege optie --}}
            <option value="" disabled {{ $currentUser ? '' : 'selected' }}>
                -- Selecteer {{ $label }} --
            </option>

            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ $currentUser == $user->id ? 'selected' : '' }}>
                    {{ $user->naam }}
                </option>
            @endforeach
        </select>
    </form>

</div>
