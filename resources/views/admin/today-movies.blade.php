<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Today's Movies | Admin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass { background: rgba(15,23,42,0.6); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.08); }
    </style>
</head>
<body class="min-h-screen text-slate-200 flex flex-col">

    <!-- Top Nav -->
    <header class="glass border-b border-slate-800 sticky top-0 z-50 px-6 py-4 flex items-center justify-between shrink-0">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="p-2 bg-slate-800 hover:bg-slate-700 rounded-xl text-slate-400 hover:text-white transition-all">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
            </a>
            <div>
                <h1 class="text-xl font-black text-white flex items-center gap-2">
                    <i data-lucide="calendar-days" class="w-5 h-5 text-amber-400"></i> Today's Movies
                </h1>
                <p class="text-[11px] text-slate-500">{{ \Carbon\Carbon::parse($today)->format('l, d M Y') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.logout') }}" class="text-slate-400 hover:text-rose-400 text-sm transition-colors">Logout</a>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full flex-grow">
        <!-- Page Title -->
        <div class="mb-10">
            <p class="text-[11px] text-amber-500 font-black uppercase tracking-widest mb-1">Schedule</p>
            <h2 class="text-3xl font-black text-white">Movies playing today</h2>
        </div>

        @if($todayMovies->isEmpty())
            <div class="glass border border-dashed border-slate-800 rounded-3xl p-20 text-center">
                <i data-lucide="calendar-x" class="w-14 h-14 text-slate-700 mx-auto mb-5"></i>
                <h3 class="text-white font-black text-xl mb-2">No Movies Scheduled</h3>
                <p class="text-slate-500 text-sm">There are no movies scheduled to play today.</p>
                <a href="{{ route('admin.movies.add') }}" class="inline-block mt-6 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-sm rounded-xl transition-colors">
                    Add a Movie
                </a>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                @foreach($todayMovies as $movie)
                <a href="{{ route('admin.bookings.movie', $movie->id) }}?date={{ $today }}"
                   class="group glass border border-slate-800 rounded-2xl overflow-hidden hover:border-amber-500/40 transition-all hover:shadow-xl hover:shadow-amber-900/10 cursor-pointer flex flex-col relative">
                    <!-- Poster -->
                    <div class="relative aspect-[2/3] overflow-hidden">
                        @if($movie->poster)
                            <img src="{{ $movie->poster }}" alt="{{ $movie->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-slate-800 flex items-center justify-center">
                                <i data-lucide="film" class="w-10 h-10 text-slate-600"></i>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent"></div>
                        
                        <!-- Status Badge -->
                        @php
                            $startTime = \Carbon\Carbon::parse($movie->start_time)->timezone('Asia/Kolkata');
                            $endTime = \Carbon\Carbon::parse($movie->end_time)->timezone('Asia/Kolkata');
                            $now = now('Asia/Kolkata');
                            $status = '';
                            $color = '';
                            if ($now->between($startTime, $endTime)) {
                                $status = 'PLAYING NOW';
                                $color = 'bg-rose-600';
                            } elseif ($now->lt($startTime)) {
                                $status = 'UPCOMING';
                                $color = 'bg-amber-500';
                            } else {
                                $status = 'ENDED';
                                $color = 'bg-slate-600';
                            }
                        @endphp
                        <div class="absolute top-2 left-2 px-2 py-0.5 {{ $color }} rounded-lg text-[10px] font-black text-white shadow-lg tracking-wider">
                            {{ $status }}
                        </div>

                        <!-- Click hint -->
                        <div class="absolute inset-0 bg-amber-600/0 group-hover:bg-amber-600/10 transition-all flex items-center justify-center">
                            <div class="opacity-0 group-hover:opacity-100 transition-all bg-amber-500 text-white text-xs font-black px-3 py-1.5 rounded-xl flex items-center gap-1.5">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i> View Bookings
                            </div>
                        </div>
                    </div>
                    <!-- Info -->
                    <div class="p-4 flex-grow flex flex-col justify-between">
                        <div>
                            <h3 class="text-white font-black text-sm mb-1 line-clamp-1">{{ $movie->title }}</h3>
                            <div class="flex items-center gap-2 text-[11px] text-slate-400 mb-2">
                                <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                <span>{{ $startTime->format('h:i A') }} - {{ $endTime->format('h:i A') }}</span>
                            </div>
                        </div>
                        <div class="pt-3 border-t border-slate-800/60 mt-2">
                            <span class="text-[10px] font-black text-amber-400 flex items-center gap-1.5">
                                <i data-lucide="ticket" class="w-3 h-3"></i> Check Attendees
                            </span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        @endif
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>
