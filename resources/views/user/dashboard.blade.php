@extends('layouts.user')

@section('title', 'Dashboard | MovieTicket')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Hero Section -->
        <div class="relative rounded-[2rem] overflow-hidden bg-slate-900 border border-slate-800 shadow-2xl mb-12">
            <div
                class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?q=80&w=2070&auto=format&fit=crop')] bg-cover bg-center opacity-30">
            </div>
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/60 to-transparent"></div>

            <div class="relative p-8 md:p-16 max-w-2xl">
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-rose-500/20 text-rose-500 border border-rose-500/20 mb-6">
                    NOW SHOWING
                </span>
                <h1 class="text-5xl md:text-6xl font-black text-white leading-tight mb-6">
                    Experience Movies Like Never Before.
                </h1>
                <p class="text-lg text-slate-400 mb-8 leading-relaxed">
                    Book tickets for the latest blockbusters in premium theaters near you. Real-time availability and
                    seamless seat selection.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('movies') }}"
                        class="px-8 py-4 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-2xl transition-all active:scale-95 shadow-lg shadow-rose-900/20 text-center">
                        Browse Movies
                    </a>
                    <button
                        class="px-8 py-4 bg-slate-800/80 hover:bg-slate-800 text-white font-bold rounded-2xl transition-all border border-slate-700">
                        How it Works
                    </button>
                </div>
            </div>
        </div>

        <!-- Featured Section Header -->
        <div class="flex justify-between items-end mb-8">
            <div>
                <h2 class="text-3xl font-black text-white">Featured Movies</h2>
                <p class="text-slate-500">Hand-picked blockbusters for you.</p>
            </div>
            <a href="{{ route('movies') }}"
                class="text-rose-500 font-bold hover:text-rose-400 transition-colors flex items-center gap-2">
                View All <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </a>
        </div>

        <!-- Real-Time Movie Grid Container -->
        <div id="movieGrid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
            @include('user.partials.movie_grid', ['movies' => $movies, 'showTime' => false])
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            /**
             * Real-time movie fetching logic (Syncs with DB every 30s)
             */
            function refreshMovies() {
                $.ajax({
                    url: "{{ route('fetch.movies') }}",
                    type: "GET",
                    data: {
                        showTime: 'false'
                    },
                    success: function (res) {
                        if (res.status) {
                            $('#movieGrid').html(res.html);
                            lucide.createIcons();
                        }
                    },
                    error: function (err) {
                        console.error("Dashboard real-time sync failed:", err);
                    }
                });
            }

            /**
             * Immediate Removal Logic (Check every second)
             * Removes movie cards from UI the moment start_time is passed
             */
            function monitorMovieTimes() {
                const now = new Date();
                $('.movie-card').each(function () {
                    const startTimeStr = $(this).data('start-time');
                    if (startTimeStr) {
                        const startTime = new Date(startTimeStr);
                        if (startTime <= now) {
                            $(this).fadeOut(500, function () {
                                $(this).remove();
                                // If grid becomes empty, we might want to refresh to show empty state
                                if ($('.movie-card').length === 0) {
                                    refreshMovies();
                                }
                            });
                        }
                    }
                });
            }

            // sync with DB every 30 seconds
            setInterval(refreshMovies, 30000);

            // Precise removal every 1 second
            setInterval(monitorMovieTimes, 1000);
        });
    </script>
@endsection

@section('footer')
    <footer class="bg-slate-950 border-t border-slate-800 py-12 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="p-1.5 bg-rose-600 rounded-lg">
                            <i data-lucide="clapperboard" class="w-5 h-5 text-white"></i>
                        </div>
                        <span class="text-2xl font-black text-white tracking-tight">MovieTicket</span>
                    </div>
                    <p class="text-slate-400 text-lg leading-relaxed max-w-md">
                        The ultimate destination for movie enthusiasts. Book your favorite shows in the best theaters with
                        just a few clicks.
                    </p>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6 uppercase tracking-widest text-sm">Quick Links</h4>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-slate-400 hover:text-rose-500 transition-colors">Now Showing</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-rose-500 transition-colors">Coming Soon</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-rose-500 transition-colors">Theaters</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-rose-500 transition-colors">Offers</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6 uppercase tracking-widest text-sm">Support</h4>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-slate-400 hover:text-rose-500 transition-colors">Help Center</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-rose-500 transition-colors">Terms of Service</a>
                        </li>
                        <li><a href="#" class="text-slate-400 hover:text-rose-500 transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-rose-500 transition-colors">Contact Us</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-slate-900 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-slate-500 text-sm">© 2026 MovieTicket. All rights reserved.</p>
                <div class="flex gap-6">
                    <a href="#" class="text-slate-500 hover:text-white transition-colors"><i data-lucide="instagram"
                            class="w-5 h-5"></i></a>
                    <a href="#" class="text-slate-500 hover:text-white transition-colors"><i data-lucide="twitter"
                            class="w-5 h-5"></i></a>
                    <a href="#" class="text-slate-500 hover:text-white transition-colors"><i data-lucide="facebook"
                            class="w-5 h-5"></i></a>
                </div>
            </div>
        </div>
    </footer>
@endsection