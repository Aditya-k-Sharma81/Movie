@extends('admin.layouts.app')

@section('title', 'Bookings')

@section('header')
<header class="glass border-b border-slate-800 sticky top-0 z-50 px-6 py-4 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.dashboard') }}" class="p-2 bg-slate-800 hover:bg-slate-700 rounded-xl text-slate-400 hover:text-white transition-all">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
        </a>
        <div>
            <h1 class="text-xl font-black text-white">Booking Management</h1>
            <p class="text-[11px] text-slate-500">Date-wise movie & event booking overview</p>
        </div>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.movies.index') }}" class="text-slate-400 hover:text-white text-sm transition-colors">Movies</a>
        <span class="w-px h-4 bg-slate-700"></span>
        <a href="{{ route('admin.events.index') }}" class="text-slate-400 hover:text-white text-sm transition-colors">Events</a>
        <span class="w-px h-4 bg-slate-700"></span>
        <a href="{{ route('admin.logout') }}" class="text-slate-400 hover:text-rose-400 text-sm transition-colors">Logout</a>
    </div>
</header>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Page Title + Date Filter -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
        <div>
            <p class="text-[11px] text-rose-500 font-black uppercase tracking-widest mb-1">Admin Panel</p>
            <h2 class="text-3xl font-black text-white">
                {{ \Carbon\Carbon::parse($date)->isToday() ? "Today's Bookings" : \Carbon\Carbon::parse($date)->format('d M Y').' Bookings' }}
            </h2>
        </div>
        <form method="GET" action="{{ route('admin.bookings') }}" class="flex items-center gap-3">
            <input type="date" name="date" value="{{ $date }}"
                class="bg-slate-800 border border-slate-700 text-white text-sm rounded-xl px-4 py-2.5 focus:border-rose-500 outline-none transition-all">
            <button type="submit" class="px-6 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-sm font-black rounded-xl transition-all">
                Apply
            </button>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-10">
        <div class="glass border border-slate-800 rounded-2xl p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-rose-600/10 rounded-xl flex items-center justify-center border border-rose-500/20 shrink-0">
                <i data-lucide="indian-rupee" class="w-5 h-5 text-rose-500"></i>
            </div>
            <div>
                <p class="text-[10px] text-slate-500 uppercase font-black tracking-widest">Total Revenue</p>
                <p class="text-2xl font-black text-white mt-0.5">₹{{ number_format($totalRevenue) }}</p>
            </div>
        </div>
        <div class="glass border border-slate-800 rounded-2xl p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-indigo-600/10 rounded-xl flex items-center justify-center border border-indigo-500/20 shrink-0">
                <i data-lucide="ticket" class="w-5 h-5 text-indigo-400"></i>
            </div>
            <div>
                <p class="text-[10px] text-slate-500 uppercase font-black tracking-widest">Tickets Sold</p>
                <p class="text-2xl font-black text-white mt-0.5">{{ $totalTickets }}</p>
            </div>
        </div>
        <div class="glass border border-slate-800 rounded-2xl p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-600/10 rounded-xl flex items-center justify-center border border-emerald-500/20 shrink-0">
                <i data-lucide="receipt" class="w-5 h-5 text-emerald-400"></i>
            </div>
            <div>
                <p class="text-[10px] text-slate-500 uppercase font-black tracking-widest">Total Booked Items</p>
                <p class="text-2xl font-black text-white mt-0.5">{{ count($moviesWithStats) + count($eventsWithStats) }}</p>
            </div>
        </div>
    </div>

    <!-- MOVIE BOOKINGS SECTION -->
    <div class="space-y-6 mb-12">
        <div class="flex items-center gap-4">
            <span class="text-xs font-black text-rose-500 uppercase tracking-widest flex items-center gap-2 shrink-0">
                <i data-lucide="film" class="w-4 h-4"></i> Movie Shows Booked ({{ count($moviesWithStats) }})
            </span>
            <div class="h-px w-full bg-slate-800"></div>
        </div>

        @if(count($moviesWithStats) === 0)
            <div class="glass border border-dashed border-slate-850 rounded-2xl p-10 text-center">
                <p class="text-slate-500 text-xs">No movies booked on this date.</p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                @foreach($moviesWithStats as $item)
                @php $movie = $item['movie']; @endphp
                <a href="{{ route('admin.bookings.movie', $movie->id) }}?date={{ $date }}"
                   class="group glass border border-slate-800 rounded-2xl overflow-hidden hover:border-rose-500/40 transition-all hover:shadow-xl hover:shadow-rose-900/10 cursor-pointer flex flex-col">
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
                        <!-- Revenue Badge -->
                        <div class="absolute top-2 right-2 px-2 py-0.5 bg-rose-600 rounded-lg text-[11px] font-black text-white shadow-lg">
                            ₹{{ number_format($item['total_revenue']) }}
                        </div>
                        <!-- Click hint -->
                        <div class="absolute inset-0 bg-rose-600/0 group-hover:bg-rose-600/10 transition-all flex items-center justify-center">
                            <div class="opacity-0 group-hover:opacity-100 transition-all bg-rose-600 text-white text-xs font-black px-3 py-1.5 rounded-xl flex items-center gap-1.5">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i> View Bookings
                            </div>
                        </div>
                    </div>
                    <!-- Info -->
                    <div class="p-3 flex-grow">
                        <h3 class="text-white font-black text-sm mb-1 line-clamp-1">{{ $movie->title }}</h3>
                        <p class="text-[10px] text-slate-500 mb-3">{{ \Carbon\Carbon::parse($movie->start_time)->format('h:i A') }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] text-indigo-400 font-bold flex items-center gap-1">
                                <i data-lucide="users" class="w-3 h-3"></i> {{ $item['total_bookings'] }}
                            </span>
                            <span class="text-[10px] text-emerald-400 font-bold flex items-center gap-1">
                                <i data-lucide="ticket" class="w-3 h-3"></i> {{ $item['total_seats'] }} seats
                            </span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        @endif
    </div>

    <!-- EVENT BOOKINGS SECTION -->
    <div class="space-y-6">
        <div class="flex items-center gap-4">
            <span class="text-xs font-black text-purple-500 uppercase tracking-widest flex items-center gap-2 shrink-0">
                <i data-lucide="calendar" class="w-4 h-4"></i> Events Booked ({{ count($eventsWithStats) }})
            </span>
            <div class="h-px w-full bg-slate-800"></div>
        </div>

        @if(count($eventsWithStats) === 0)
            <div class="glass border border-dashed border-slate-850 rounded-2xl p-10 text-center">
                <p class="text-slate-500 text-xs">No events booked on this date.</p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                @foreach($eventsWithStats as $item)
                @php $event = $item['event']; @endphp
                <a href="{{ route('admin.bookings.event', $event->id) }}?date={{ $date }}"
                   class="group glass border border-slate-800 rounded-2xl overflow-hidden hover:border-purple-500/40 transition-all hover:shadow-xl hover:shadow-purple-900/10 cursor-pointer flex flex-col">
                    <!-- Poster -->
                    <div class="relative aspect-[2/3] overflow-hidden">
                        @if($event->poster)
                            <img src="{{ $event->poster }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-slate-800 flex items-center justify-center">
                                <i data-lucide="calendar" class="w-10 h-10 text-slate-600"></i>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent"></div>
                        <!-- Revenue Badge -->
                        <div class="absolute top-2 right-2 px-2 py-0.5 bg-purple-600 rounded-lg text-[11px] font-black text-white shadow-lg">
                            ₹{{ number_format($item['total_revenue']) }}
                        </div>
                        <!-- Click hint -->
                        <div class="absolute inset-0 bg-purple-600/0 group-hover:bg-purple-600/10 transition-all flex items-center justify-center">
                            <div class="opacity-0 group-hover:opacity-100 transition-all bg-purple-600 text-white text-xs font-black px-3 py-1.5 rounded-xl flex items-center gap-1.5">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i> View Bookings
                            </div>
                        </div>
                    </div>
                    <!-- Info -->
                    <div class="p-3 flex-grow">
                        <h3 class="text-white font-black text-sm mb-1 line-clamp-1">{{ $event->title }}</h3>
                        <p class="text-[10px] text-slate-500 mb-3">{{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] text-indigo-400 font-bold flex items-center gap-1">
                                <i data-lucide="users" class="w-3 h-3"></i> {{ $item['total_bookings'] }}
                            </span>
                            <span class="text-[10px] text-purple-400 font-bold flex items-center gap-1">
                                <i data-lucide="ticket" class="w-3 h-3"></i> {{ $item['total_seats'] }} seats
                            </span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
