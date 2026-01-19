@php
    $label = auth()->user()->type === 3
        ? 'een instructeur'
        : 'een leerling';
@endphp

<div class="mb-6">
    <form method="GET" action="" class="flex flex-col items-center" id="userForm">
        <label for="user" class="mb-2 font-semibold text-eisblue">
            Kies {{ $label }}:
        </label>
        <select name="user" id="user"
                class="px-4 py-2 rounded shadow w-72 text-eisblue"
                onchange="document.getElementById('userForm').submit();">
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ request('user', $selectedUserId) == $user->id ? 'selected' : '' }}>
                    {{ $user->naam }}
                </option>
            @endforeach
        </select>
    </form>
</div>
