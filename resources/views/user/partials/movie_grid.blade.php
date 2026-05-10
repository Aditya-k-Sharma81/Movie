@foreach($movies as $movie)
    <a href="{{ route('movie.details', ['id' => $movie->_id]) }}" class="group cursor-pointer movie-card block"
        data-start-time="{{ \Carbon\Carbon::parse($movie->start_time)->toIso8601String() }}">
        <div
            class="relative aspect-[2/3] rounded-3xl overflow-hidden mb-4 border border-slate-800 transition-all group-hover:scale-105 group-hover:border-rose-500/50 group-hover:shadow-xl group-hover:shadow-rose-900/10">
            <img src="{{ $movie->poster ?? 'https://via.placeholder.com/400x600?text=No+Poster' }}"
                alt="{{ $movie->title }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent opacity-80">
            </div>
            <div class="absolute bottom-3 left-3 flex flex-col gap-1">
                @php
                    $startTime = \Carbon\Carbon::parse($movie->start_time);
                    $dateLabel = $startTime->isToday() ? 'Today' : ($startTime->isTomorrow() ? 'Tomorrow' : $startTime->format('M d'));
                @endphp
                @if(!isset($showDate) || $showDate !== false)
                <span
                    class="px-2 py-0.5 bg-slate-950/80 backdrop-blur-md text-[9px] font-black text-rose-500 rounded border border-rose-500/20 uppercase tracking-tighter w-fit">
                    {{ $dateLabel }}
                </span>
                @endif
                @if(!isset($showTime) || $showTime !== false)
                    <span
                        class="px-2 py-0.5 bg-rose-600 text-[10px] font-bold text-white rounded uppercase tracking-wider w-fit shadow-lg shadow-rose-900/40">
                        {{ $startTime->format('h:i A') }}
                    </span>
                @endif
            </div>
        </div>
        <h3 class="text-white font-bold text-sm truncate group-hover:text-rose-500 transition-colors">
            {{ $movie->title }}
        </h3>
        <p class="text-slate-500 text-xs truncate">
            @if(is_array($movie->genre))
                {{ implode(' • ', $movie->genre) }}
            @else
                {{ $movie->genre }}
            @endif
        </p>
    </a>
@endforeach