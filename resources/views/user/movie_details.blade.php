@extends('layouts.user')

@section('title', $movie->title . ' | MovieTicket')

@section('content')
<div class="relative min-h-screen">
    <!-- Hero Backdrop -->
    <div class="absolute top-0 left-0 w-full h-[70vh] z-0">
        <img src="{{ $movie->poster ?? 'https://via.placeholder.com/1920x1080?text=No+Poster' }}" 
             alt="{{ $movie->title }}" 
             class="w-full h-full object-cover opacity-20 blur-sm">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/80 to-transparent"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-12">
        <div class="flex flex-col lg:flex-row gap-12 items-start">
            
            <!-- Movie Poster -->
            <div class="w-full lg:w-1/3 flex-shrink-0">
                <div class="relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-rose-600 to-indigo-600 rounded-[2.5rem] blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
                    <div class="relative rounded-[2rem] overflow-hidden border border-slate-800 shadow-2xl">
                        <img src="{{ $movie->poster ?? 'https://via.placeholder.com/600x900?text=No+Poster' }}" 
                             alt="{{ $movie->title }}" 
                             class="w-full aspect-[2/3] object-cover transition-transform duration-700 group-hover:scale-110">
                    </div>
                </div>
            </div>

            <!-- Movie Details -->
            <div class="flex-grow pt-4">
                <div class="flex flex-wrap gap-3 mb-6">
                    @php
                        $startTime = \Carbon\Carbon::parse($movie->start_time);
                        $dateLabel = $startTime->isToday() ? 'Today' : ($startTime->isTomorrow() ? 'Tomorrow' : $startTime->format('M d, Y'));
                    @endphp
                    <span class="px-4 py-1.5 bg-rose-600/10 text-rose-500 border border-rose-500/20 rounded-full text-xs font-bold uppercase tracking-widest">
                        {{ $dateLabel }}
                    </span>
                    <span class="px-4 py-1.5 bg-indigo-600/10 text-indigo-400 border border-indigo-500/20 rounded-full text-xs font-bold uppercase tracking-widest">
                        {{ $startTime->format('h:i A') }}
                    </span>
                    <span class="px-4 py-1.5 bg-slate-800 text-slate-300 border border-slate-700 rounded-full text-xs font-bold uppercase tracking-widest">
                        {{ $movie->duration ?? 'N/A' }} min
                    </span>
                </div>

                <h1 class="text-5xl lg:text-7xl font-black text-white mb-6 leading-tight">
                    {{ $movie->title }}
                </h1>

                <div class="flex items-center gap-4 mb-8 text-slate-400">
                    <div class="flex items-center gap-1.5">
                        <i data-lucide="tag" class="w-4 h-4 text-rose-500"></i>
                        <span class="text-sm font-medium">
                            @if(is_array($movie->genre))
                                {{ implode(', ', $movie->genre) }}
                            @else
                                {{ $movie->genre }}
                            @endif
                        </span>
                    </div>
                    <div class="w-1.5 h-1.5 bg-slate-700 rounded-full"></div>
                    <div class="flex items-center gap-1.5">
                        <i data-lucide="languages" class="w-4 h-4 text-rose-500"></i>
                        <span class="text-sm font-medium">{{ $movie->language ?? 'English' }}</span>
                    </div>
                </div>

                <div class="bg-slate-900/40 backdrop-blur-md border border-slate-800/50 rounded-3xl p-8 mb-10">
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                        <i data-lucide="align-left" class="w-5 h-5 text-rose-500"></i>
                        Synopsis
                    </h3>
                    <p class="text-slate-400 leading-relaxed text-lg">
                        {{ $movie->description ?? 'No description available for this movie.' }}
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-6">
                    <a href="{{ route('movies') }}" 
                       class="flex-1 flex items-center justify-center gap-3 py-4 px-8 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-2xl border border-slate-700 transition-all active:scale-95">
                        <i data-lucide="arrow-left" class="w-5 h-5"></i>
                        Back to List
                    </a>
                    <button class="flex-[2] group relative">
                        <div class="absolute -inset-1 bg-gradient-to-r from-rose-600 to-rose-500 rounded-2xl blur opacity-30 group-hover:opacity-60 transition duration-1000 group-hover:duration-200"></div>
                        <div class="relative flex items-center justify-center gap-3 py-4 px-8 bg-rose-600 hover:bg-rose-500 text-white font-black text-xl rounded-2xl transition-all active:scale-95 shadow-xl shadow-rose-900/20">
                            <i data-lucide="ticket" class="w-6 h-6"></i>
                            Book Tickets Now
                        </div>
                    </button>
                </div>
            </div>

        </div>

        <!-- Additional Info Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-16">
            <!-- Location Card -->
            <div class="bg-slate-900/40 backdrop-blur-sm border border-slate-800/50 p-5 rounded-2xl flex items-center gap-5 transition-all hover:border-rose-500/30 group">
                <div class="w-12 h-12 flex-shrink-0 bg-rose-600/10 rounded-xl flex items-center justify-center border border-rose-500/20 group-hover:bg-rose-600 group-hover:text-white transition-all duration-500">
                    <i data-lucide="map-pin" class="w-6 h-6 text-rose-500 group-hover:text-white"></i>
                </div>
                <div>
                    <h4 class="text-white font-bold text-base mb-0.5 tracking-tight">Location</h4>
                    <p class="text-slate-500 text-[13px] leading-snug line-clamp-2">{{ $theatreDetails['theatre_name'] }}, {{ $theatreDetails['location'] }}</p>
                </div>
            </div>

            <!-- Screen Card -->
            <div class="bg-slate-900/40 backdrop-blur-sm border border-slate-800/50 p-5 rounded-2xl flex items-center gap-5 transition-all hover:border-indigo-500/30 group">
                <div class="w-12 h-12 flex-shrink-0 bg-indigo-600/10 rounded-xl flex items-center justify-center border border-indigo-500/20 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500">
                    <i data-lucide="tv" class="w-6 h-6 text-indigo-400 group-hover:text-white"></i>
                </div>
                <div>
                    <h4 class="text-white font-bold text-base mb-0.5 tracking-tight">Screen</h4>
                    <p class="text-slate-500 text-[13px] leading-snug">{{ $theatreDetails['screen_type'] }} (Atmos 7.1)</p>
                </div>
            </div>

            <!-- Refund Card -->
            <div class="bg-slate-900/40 backdrop-blur-sm border border-slate-800/50 p-5 rounded-2xl flex items-center gap-5 transition-all hover:border-emerald-500/30 group">
                <div class="w-12 h-12 flex-shrink-0 bg-emerald-600/10 rounded-xl flex items-center justify-center border border-emerald-500/20 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                    <i data-lucide="shield-check" class="w-6 h-6 text-emerald-400 group-hover:text-white"></i>
                </div>
                <div>
                    <h4 class="text-white font-bold text-base mb-0.5 tracking-tight">Refund Policy</h4>
                    <p class="text-slate-500 text-[13px] leading-snug">Cancellation up to 2 hours before</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
