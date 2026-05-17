@extends('admin.layouts.app')

@section('title', "Today's Events")

@section('header')
<header class="glass border-b border-slate-800 sticky top-0 z-50 px-6 py-4 flex items-center justify-between shrink-0">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.dashboard') }}" class="p-2 bg-slate-800 hover:bg-slate-700 rounded-xl text-slate-400 hover:text-white transition-all">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
        </a>
        <div>
            <h1 class="text-xl font-black text-white flex items-center gap-2">
                <i data-lucide="calendar" class="w-5 h-5 text-purple-400"></i> Today's Events
            </h1>
            <p class="text-[11px] text-slate-500">{{ \Carbon\Carbon::parse($today)->format('l, d M Y') }}</p>
        </div>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.logout') }}" class="text-slate-400 hover:text-rose-400 text-sm transition-colors">Logout</a>
    </div>
</header>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full flex-grow">
    <!-- Page Title & Filter -->
    <div class="mb-10 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <p class="text-[11px] text-purple-500 font-black uppercase tracking-widest mb-1">Schedule</p>
            <h2 class="text-3xl font-black text-white">
                {{ \Carbon\Carbon::parse($today)->isToday() ? 'Events happening today' : 'Events on ' . \Carbon\Carbon::parse($today)->format('d M Y') }}
            </h2>
        </div>
        
        <form method="GET" action="{{ route('admin.today.events') }}" class="flex items-center gap-3">
            <div class="relative">
                <input type="date" name="date" value="{{ $today }}" onchange="this.form.submit()" 
                       class="bg-slate-900 border border-slate-700 text-slate-300 text-sm rounded-xl focus:ring-purple-500 focus:border-purple-500 block w-full pl-3 pr-4 py-2 cursor-pointer outline-none transition-all hover:border-purple-500/50">
            </div>
            @if(!\Carbon\Carbon::parse($today)->isToday())
                <a href="{{ route('admin.today.events') }}" class="px-4 py-2 text-sm bg-slate-800 hover:bg-slate-700 text-white rounded-xl transition-all border border-slate-700">
                    Reset
                </a>
            @endif
        </form>
    </div>

    @if($todayEvents->isEmpty())
        <div class="glass border border-dashed border-slate-800 rounded-3xl p-20 text-center">
            <i data-lucide="calendar-x" class="w-14 h-14 text-slate-700 mx-auto mb-5"></i>
            <h3 class="text-white font-black text-xl mb-2">No Events Scheduled</h3>
            <p class="text-slate-500 text-sm">There are no events scheduled {{ \Carbon\Carbon::parse($today)->isToday() ? 'today' : 'on ' . \Carbon\Carbon::parse($today)->format('d M Y') }}.</p>
            <a href="{{ route('admin.events.add') }}" class="inline-block mt-6 px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-black text-sm rounded-xl transition-colors">
                Add an Event
            </a>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
            @foreach($todayEvents as $event)
            <a href="{{ route('admin.bookings.event', $event->id) }}?date={{ $today }}"
               class="group glass border border-slate-800 rounded-2xl overflow-hidden hover:border-purple-500/40 transition-all hover:shadow-xl hover:shadow-purple-900/10 cursor-pointer flex flex-col relative">
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
                    
                    <!-- Status Badge -->
                    @php
                        $startTime = \Carbon\Carbon::parse($event->start_time)->timezone('Asia/Kolkata');
                        $endTime = \Carbon\Carbon::parse($event->end_time)->timezone('Asia/Kolkata');
                        $now = now('Asia/Kolkata');
                        $status = '';
                        $color = '';
                        if ($now->between($startTime, $endTime)) {
                            $status = 'LIVE NOW';
                            $color = 'bg-rose-600';
                        } elseif ($now->lt($startTime)) {
                            $status = 'UPCOMING';
                            $color = 'bg-purple-500';
                        } else {
                            $status = 'ENDED';
                            $color = 'bg-slate-600';
                        }
                    @endphp
                    <div class="absolute top-2 left-2 px-2 py-0.5 {{ $color }} rounded-lg text-[10px] font-black text-white shadow-lg tracking-wider">
                        {{ $status }}
                    </div>

                    <!-- Click hint -->
                    <div class="absolute inset-0 bg-purple-600/0 group-hover:bg-purple-600/10 transition-all flex items-center justify-center">
                        <div class="opacity-0 group-hover:opacity-100 transition-all bg-purple-500 text-white text-xs font-black px-3 py-1.5 rounded-xl flex items-center gap-1.5">
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i> View Bookings
                        </div>
                    </div>
                </div>
                <!-- Info -->
                <div class="p-4 flex-grow flex flex-col justify-between">
                    <div>
                        <h3 class="text-white font-black text-sm mb-1 line-clamp-1">{{ $event->title }}</h3>
                        <div class="flex items-center gap-2 text-[11px] text-slate-400 mb-2">
                            <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                            <span>{{ $startTime->format('h:i A') }} - {{ $endTime->format('h:i A') }}</span>
                        </div>
                        <p class="text-[10px] text-slate-500 line-clamp-1 flex items-center gap-1">
                            <i data-lucide="map-pin" class="w-3 h-3 text-purple-400"></i> {{ $event->venue }}
                        </p>
                    </div>
                    <div class="pt-3 border-t border-slate-800/60 mt-2">
                        <span class="text-[10px] font-black text-purple-400 flex items-center gap-1.5">
                            <i data-lucide="ticket" class="w-3 h-3"></i> Check Attendees
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
