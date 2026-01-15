<x-app-layout>
    <div>
          <a href="/rooster{{ $history ? '' : '/history' }}"
              class="fixed z-50 px-6 py-2 font-semibold text-white transition-all duration-200 bg-eisblue rounded-full shadow-xl bottom-4 right-4 md:top-20 md:bottom-auto md:right-10 hover:bg-eisgroen focus:outline-none focus:ring-2 focus:ring-eisblue focus:ring-opacity-50"
              style="font-family: 'Inter', 'Segoe UI', Arial, sans-serif;">
            <span class="tracking-wide">
                {{ $history ? 'Toon huidig rooster' : 'Toon geschiedenis' }}
            </span>
        </a>
    </div>
    <x-rooster.week :rooster="$rooster" />
</x-app-layout>
