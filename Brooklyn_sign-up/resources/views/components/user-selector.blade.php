<div class="mb-6">
    <form method="GET" action="" class="flex flex-col items-center">
        <label for="user" class="mb-2 font-semibold text-eisblue">
            Kies {{ $type == 2 ? 'een instructeur' : 'een leerling' }}:
        </label>
        <select name="user" id="user" class="px-4 py-2 rounded shadow w-72 text-eisblue">
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ request('user', $selectedUserId) == $user->id ? 'selected' : '' }}>
                    {{ $user->naam }}
                </option>
            @endforeach
        </select>
    </form>
</div>
