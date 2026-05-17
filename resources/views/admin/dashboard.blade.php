@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')
@section('page_title', 'Overview')

@section('content')
<div class="p-4 sm:p-6 space-y-6">
    <!-- All-Time Stats -->
    <div>
        <p class="text-[10px] text-slate-500 uppercase font-black tracking-widest mb-3">All-Time Statistics</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Revenue -->
            <div class="stat-card border border-slate-800 rounded-2xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-9 h-9 bg-rose-600/10 rounded-xl flex items-center justify-center border border-rose-500/20">
                        <i data-lucide="indian-rupee" class="w-4 h-4 text-rose-500"></i>
                    </div>
                    <span class="text-[9px] font-black text-rose-400 bg-rose-500/10 border border-rose-500/20 px-2 py-0.5 rounded-full uppercase">Revenue</span>
                </div>
                <p class="text-2xl font-black text-white">₹{{ number_format($totalRevenue) }}</p>
                <p class="text-[10px] text-slate-500 mt-1">Total Earnings</p>
            </div>

            <!-- Tickets -->
            <div class="stat-card border border-slate-800 rounded-2xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-9 h-9 bg-indigo-600/10 rounded-xl flex items-center justify-center border border-indigo-500/20">
                        <i data-lucide="ticket" class="w-4 h-4 text-indigo-400"></i>
                    </div>
                    <span class="text-[9px] font-black text-indigo-400 bg-indigo-500/10 border border-indigo-500/20 px-2 py-0.5 rounded-full uppercase">Tickets</span>
                </div>
                <p class="text-2xl font-black text-white">{{ number_format($totalTickets) }}</p>
                <p class="text-[10px] text-slate-500 mt-1">Seats Sold</p>
            </div>

            <!-- Movies -->
            <div class="stat-card border border-slate-800 rounded-2xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-9 h-9 bg-emerald-600/10 rounded-xl flex items-center justify-center border border-emerald-500/20">
                        <i data-lucide="film" class="w-4 h-4 text-emerald-400"></i>
                    </div>
                    <span class="text-[9px] font-black text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded-full uppercase">Movies</span>
                </div>
                <p class="text-2xl font-black text-white">{{ $totalMovies }}</p>
                <p class="text-[10px] text-slate-500 mt-1">Listed Movies</p>
            </div>

            <!-- Events -->
            <div class="stat-card border border-slate-800 rounded-2xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-9 h-9 bg-purple-600/10 rounded-xl flex items-center justify-center border border-purple-500/20">
                        <i data-lucide="calendar" class="w-4 h-4 text-purple-400"></i>
                    </div>
                    <span class="text-[9px] font-black text-purple-400 bg-purple-500/10 border border-purple-500/20 px-2 py-0.5 rounded-full uppercase">Events</span>
                </div>
                <p class="text-2xl font-black text-white">{{ $totalEvents }}</p>
                <p class="text-[10px] text-slate-500 mt-1">Listed Events</p>
            </div>

            <!-- Bookings -->
            <div class="stat-card border border-slate-800 rounded-2xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-9 h-9 bg-yellow-600/10 rounded-xl flex items-center justify-center border border-yellow-500/20">
                        <i data-lucide="receipt" class="w-4 h-4 text-yellow-400"></i>
                    </div>
                    <span class="text-[9px] font-black text-yellow-400 bg-yellow-500/10 border border-yellow-500/20 px-2 py-0.5 rounded-full uppercase">Bookings</span>
                </div>
                <p class="text-2xl font-black text-white">{{ $totalBookings }}</p>
                <p class="text-[10px] text-slate-500 mt-1">Total Bookings</p>
            </div>
        </div>
    </div>

    <!-- Today's Stats -->
    <div>
        <p class="text-[10px] text-slate-500 uppercase font-black tracking-widest mb-3">Today — {{ now('Asia/Kolkata')->format('d M Y') }}</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="glass border border-slate-800 rounded-2xl p-5 flex items-center gap-4">
                <div class="w-12 h-12 bg-rose-600/20 rounded-2xl flex items-center justify-center border border-rose-500/30">
                    <i data-lucide="trending-up" class="w-5 h-5 text-rose-400"></i>
                </div>
                <div>
                    <p class="text-[10px] text-slate-500 uppercase font-black tracking-widest">Today's Revenue</p>
                    <p class="text-3xl font-black text-white mt-0.5">₹{{ number_format($todayRevenue) }}</p>
                </div>
            </div>
            <div class="glass border border-slate-800 rounded-2xl p-5 flex items-center gap-4">
                <div class="w-12 h-12 bg-indigo-600/20 rounded-2xl flex items-center justify-center border border-indigo-500/30">
                    <i data-lucide="users" class="w-5 h-5 text-indigo-400"></i>
                </div>
                <div>
                    <p class="text-[10px] text-slate-500 uppercase font-black tracking-widest">Tickets Today</p>
                    <p class="text-3xl font-black text-white mt-0.5">{{ $todayTickets }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Grid: Recent Bookings + Top Movies + Top Events -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- Recent Bookings -->
        <div class="glass border border-slate-800 rounded-2xl overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-slate-800 flex items-center justify-between">
                <h3 class="text-white font-black text-sm">Recent Bookings</h3>
                <a href="{{ route('admin.bookings') }}" class="text-[10px] text-rose-400 hover:text-rose-300 font-bold transition-colors">View All →</a>
            </div>
            <div class="divide-y divide-slate-800/60">
                @forelse($recentBookings as $rb)
                    @php $rbTime = \Carbon\Carbon::parse($rb->booking_date)->timezone('Asia/Kolkata'); @endphp
                    <div class="flex items-center justify-between px-5 py-3.5 hover:bg-slate-800/20 transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            @if($rb->movie && $rb->movie->poster)
                                <img src="{{ $rb->movie->poster }}" class="w-8 h-10 object-cover rounded-lg shrink-0">
                            @elseif($rb->event && $rb->event->poster)
                                <img src="{{ $rb->event->poster }}" class="w-8 h-10 object-cover rounded-lg shrink-0">
                            @else
                                <div class="w-8 h-10 bg-slate-800 rounded-lg flex items-center justify-center shrink-0">
                                    <i data-lucide="film" class="w-3.5 h-3.5 text-slate-600"></i>
                                </div>
                            @endif
                            <div class="min-w-0">
                                <div class="flex items-center gap-1.5">
                                    <p class="text-white font-bold text-sm truncate">
                                        {{ $rb->movie->title ?? $rb->event->title ?? 'Unknown Item' }}
                                    </p>
                                    @if($rb->event_id)
                                        <span class="text-[8px] font-extrabold px-1.5 py-0.5 bg-purple-500/20 text-purple-400 rounded-md border border-purple-500/25 uppercase shrink-0">Event</span>
                                    @else
                                        <span class="text-[8px] font-extrabold px-1.5 py-0.5 bg-rose-500/20 text-rose-400 rounded-md border border-rose-500/25 uppercase shrink-0">Movie</span>
                                    @endif
                                </div>
                                <p class="text-[10px] text-slate-500">{{ count($rb->seats ?? []) }} seat(s) · {{ $rbTime->format('h:i A, d M') }}</p>
                            </div>
                        </div>
                        <span class="text-rose-400 font-black text-sm shrink-0 ml-3">₹{{ number_format($rb->total_price) }}</span>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center text-slate-600 text-sm">No bookings yet.</div>
                @endforelse
            </div>
        </div>

        <!-- Top Movies by Revenue -->
        <div class="glass border border-slate-800 rounded-2xl overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-slate-800 flex items-center justify-between">
                <h3 class="text-white font-black text-sm">Top Movies by Revenue</h3>
                <span class="text-[10px] text-slate-500 font-bold">All time</span>
            </div>
            <div class="divide-y divide-slate-800/60">
                @forelse($topMovies as $i => $item)
                    <div class="flex items-center justify-between px-5 py-3.5 hover:bg-slate-800/20 transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="text-[11px] font-black text-slate-600 w-5 shrink-0">{{ $i + 1 }}</span>
                            @if($item['movie'] && $item['movie']->poster)
                                <img src="{{ $item['movie']->poster }}" class="w-8 h-10 object-cover rounded-lg shrink-0">
                            @else
                                <div class="w-8 h-10 bg-slate-800 rounded-lg flex items-center justify-center shrink-0">
                                    <i data-lucide="film" class="w-3.5 h-3.5 text-slate-600"></i>
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="text-white font-bold text-sm truncate">{{ $item['movie']->title ?? 'Unknown' }}</p>
                                <p class="text-[10px] text-slate-500">{{ $item['tickets'] }} tickets sold</p>
                            </div>
                        </div>
                        <span class="text-emerald-400 font-black text-sm shrink-0 ml-3">₹{{ number_format($item['revenue']) }}</span>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center text-slate-600 text-sm">No data yet.</div>
                @endforelse
            </div>
        </div>

        <!-- Top Events by Revenue -->
        <div class="glass border border-slate-800 rounded-2xl overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-slate-800 flex items-center justify-between">
                <h3 class="text-white font-black text-sm">Top Events by Revenue</h3>
                <span class="text-[10px] text-slate-500 font-bold">All time</span>
            </div>
            <div class="divide-y divide-slate-800/60">
                @forelse($topEvents as $i => $item)
                    <div class="flex items-center justify-between px-5 py-3.5 hover:bg-slate-800/20 transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="text-[11px] font-black text-slate-600 w-5 shrink-0">{{ $i + 1 }}</span>
                            @if($item['event'] && $item['event']->poster)
                                <img src="{{ $item['event']->poster }}" class="w-8 h-10 object-cover rounded-lg shrink-0">
                            @else
                                <div class="w-8 h-10 bg-slate-800 rounded-lg flex items-center justify-center shrink-0">
                                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-600"></i>
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="text-white font-bold text-sm truncate">{{ $item['event']->title ?? 'Unknown' }}</p>
                                <p class="text-[10px] text-slate-500">{{ $item['tickets'] }} tickets sold</p>
                            </div>
                        </div>
                        <span class="text-purple-400 font-black text-sm shrink-0 ml-3">₹{{ number_format($item['revenue']) }}</span>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center text-slate-600 text-sm">No data yet.</div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Quick Actions -->
    <div>
        <p class="text-[10px] text-slate-500 uppercase font-black tracking-widest mb-3">Quick Actions</p>
        <div class="grid grid-cols-2 md:grid-cols-6 gap-3">
            <a href="{{ route('admin.movies.add') }}"
                class="flex flex-col items-center gap-2 p-4 bg-indigo-600/10 hover:bg-indigo-600 border border-indigo-500/20 hover:border-indigo-600 rounded-2xl transition-all group text-center">
                <i data-lucide="plus-circle" class="w-6 h-6 text-indigo-400 group-hover:text-white"></i>
                <span class="text-xs font-black text-indigo-400 group-hover:text-white">Add Movie</span>
            </a>
            <a href="{{ route('admin.events.add') }}"
                class="flex flex-col items-center gap-2 p-4 bg-purple-600/10 hover:bg-purple-600 border border-purple-500/20 hover:border-purple-600 rounded-2xl transition-all group text-center">
                <i data-lucide="plus-square" class="w-6 h-6 text-purple-400 group-hover:text-white"></i>
                <span class="text-xs font-black text-purple-400 group-hover:text-white">Add Event</span>
            </a>
            <a href="{{ route('admin.bookings') }}"
                class="flex flex-col items-center gap-2 p-4 bg-rose-600/10 hover:bg-rose-600 border border-rose-500/20 hover:border-rose-600 rounded-2xl transition-all group text-center">
                <i data-lucide="ticket" class="w-6 h-6 text-rose-400 group-hover:text-white"></i>
                <span class="text-xs font-black text-rose-400 group-hover:text-white">Bookings</span>
            </a>
            <a href="{{ route('admin.movies.index') }}"
                class="flex flex-col items-center gap-2 p-4 bg-slate-800/50 hover:bg-slate-700 border border-slate-700 rounded-2xl transition-all group text-center">
                <i data-lucide="film" class="w-6 h-6 text-slate-400 group-hover:text-white"></i>
                <span class="text-xs font-black text-slate-400 group-hover:text-white">All Movies</span>
            </a>
            <a href="{{ route('admin.screens.seating') }}"
                class="flex flex-col items-center gap-2 p-4 bg-slate-800/50 hover:bg-slate-700 border border-slate-700 rounded-2xl transition-all group text-center">
                <i data-lucide="armchair" class="w-6 h-6 text-slate-400 group-hover:text-white"></i>
                <span class="text-xs font-black text-slate-400 group-hover:text-white">Movie Seating</span>
            </a>
            <a href="{{ route('admin.screens.event_seating') }}"
                class="flex flex-col items-center gap-2 p-4 bg-purple-600/10 hover:bg-purple-600 border border-purple-500/20 hover:border-purple-600 rounded-2xl transition-all group text-center">
                <i data-lucide="armchair" class="w-6 h-6 text-purple-400 group-hover:text-white"></i>
                <span class="text-xs font-black text-purple-400 group-hover:text-white">Event Seating</span>
            </a>
        </div>
    </div>

</div>
@endsection