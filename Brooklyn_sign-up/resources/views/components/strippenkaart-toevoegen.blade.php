@php
    $targetUserId = request('user', auth()->id());
@endphp

<div>
    <!-- Open button -->
    <button id="open-strippenkaart"
        class="w-[240px] h-10 bg-eisgeel rounded-md text-lg font-bold text-center flex items-center justify-center hover:bg-yellow-400 cursor-pointer">
        + Strippenkaart toevoegen
    </button>

    <!-- Modal -->
    <div id="strippenkaart-modal"
         class="fixed inset-0 bg-black bg-opacity-40 hidden z-50 flex items-center justify-center">

        <div class="bg-white p-6 rounded-lg shadow-xl min-w-[300px] relative">
            <button class="x-button absolute top-2 right-2 text-gray-500 hover:text-red-500 text-2xl">&times;</button>

            <h2 class="text-xl font-bold mb-4">Strippenkaart toevoegen</h2>

            <!-- Jouw inhoud – exact dezelfde layout als origineel -->
            <div class="flex flex-row gap-4 justify-center">
                <form method="POST" action="{{ route('strippenkaart.add') }}">
                    @csrf
                    <input type="hidden" name="amount" value="15">
                    <input type="hidden" name="user_id" value="{{ $targetUserId }}">
                    <button type="submit"
                        class="strippenkaart-close-button flex w-[50px] h-[50px] bg-eisgeel rounded-md justify-center items-center hover:bg-yellow-400">
                        +15
                    </button>
                </form>

                <form method="POST" action="{{ route('strippenkaart.add') }}">
                    @csrf
                    <input type="hidden" name="amount" value="20">
                    <input type="hidden" name="user_id" value="{{ $targetUserId }}">
                    <button type="submit"
                        class="strippenkaart-close-button flex w-[50px] h-[50px] bg-eisgeel rounded-md justify-center items-center hover:bg-yellow-400">
                        +20
                    </button>
                </form>

                <form method="POST" action="{{ route('strippenkaart.add') }}">
                    @csrf
                    <input type="hidden" name="amount" value="25">
                    <input type="hidden" name="user_id" value="{{ $targetUserId }}">
                    <button type="submit"
                        class="strippenkaart-close-button flex w-[50px] h-[50px] bg-eisgeel rounded-md justify-center items-center hover:bg-yellow-400">
                        +25
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
