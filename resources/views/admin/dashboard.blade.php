<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | MovieTicket</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        .glass {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .stat-card {
            background: rgba(30, 41, 59, 0.5);
        }

        ::-webkit-scrollbar {
            width: 4px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 10px;
        }
    </style>
</head>

<body class="h-full text-slate-200">
    <div class="flex h-full">

        <!-- Sidebar -->
        <aside class="w-64 glass border-r border-slate-800 flex flex-col hidden md:flex shrink-0">
            <div class="p-6 border-b border-slate-800">
                <h1 class="text-xl font-black tracking-tight text-white">Movie<span class="text-rose-500">Ticket</span>
                </h1>
                <p class="text-[10px] text-slate-500 mt-0.5 uppercase tracking-widest font-bold">Admin Panel</p>
            </div>
            <nav class="flex-1 mt-4 px-3 space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center px-3 py-2.5 text-white bg-rose-600/10 border-l-2 border-rose-500 rounded-r-xl font-bold text-sm">
                    <i data-lucide="layout-dashboard" class="w-4 h-4 mr-3 text-rose-400"></i> Dashboard
                </a>
                <a href="{{ route('admin.movies.index') }}"
                    class="flex items-center px-3 py-2.5 text-slate-400 hover:text-white hover:bg-slate-800/50 rounded-xl transition-all text-sm">
                    <i data-lucide="film" class="w-4 h-4 mr-3"></i> All Movies
                </a>
                <a href="{{ route('admin.movies.add') }}"
                    class="flex items-center px-3 py-2.5 text-slate-400 hover:text-white hover:bg-slate-800/50 rounded-xl transition-all text-sm">
                    <i data-lucide="plus-circle" class="w-4 h-4 mr-3 text-indigo-400"></i> Add Movie
                </a>
                <a href="{{ route('admin.bookings') }}"
                    class="flex items-center px-3 py-2.5 text-slate-400 hover:text-white hover:bg-slate-800/50 rounded-xl transition-all text-sm">
                    <i data-lucide="ticket" class="w-4 h-4 mr-3 text-rose-400"></i> Bookings
                </a>
                <a href="{{ route('admin.today.movies') }}"
                    class="flex items-center px-3 py-2.5 text-slate-400 hover:text-white hover:bg-amber-500/10 rounded-xl transition-all text-sm group">
                    <i data-lucide="calendar-days" class="w-4 h-4 mr-3 text-amber-400 group-hover:text-amber-300"></i>
                    <span>Today's Movies</span>
                    <span class="ml-auto text-[9px] font-black px-1.5 py-0.5 bg-amber-500/20 text-amber-400 rounded-md border border-amber-500/20">TODAY</span>
                </a>
                <a href="{{ route('admin.screens.seating') }}"
                    class="flex items-center px-3 py-2.5 text-slate-400 hover:text-white hover:bg-slate-800/50 rounded-xl transition-all text-sm">
                    <i data-lucide="armchair" class="w-4 h-4 mr-3 text-blue-400"></i> Seating Map
                </a>
                <a href="{{ route('admin.profile') }}"
                    class="flex items-center px-3 py-2.5 text-slate-400 hover:text-white hover:bg-slate-800/50 rounded-xl transition-all text-sm">
                    <i data-lucide="user-circle" class="w-4 h-4 mr-3"></i> My Profile
                </a>
            </nav>
            <!-- Admin Info -->
            <div class="p-4 border-t border-slate-800">
                <a href="{{ route('admin.profile') }}"
                    class="flex items-center p-2.5 rounded-xl bg-slate-900/50 border border-slate-800 hover:bg-slate-800 transition-all">
                    <div
                        class="w-9 h-9 rounded-full bg-rose-600 flex items-center justify-center text-white font-black text-sm shrink-0">
                        {{ strtoupper(substr(session('admin_name') ?? 'A', 0, 1)) }}
                    </div>
                    <div class="ml-3 overflow-hidden">
                        <p class="text-sm font-bold text-white truncate">{{ session('admin_name') ?? 'Admin' }}</p>
                        <p class="text-[10px] text-slate-500 truncate">{{ session('admin_email') ?? '' }}</p>
                    </div>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="h-14 glass border-b border-slate-800 flex items-center justify-between px-6 z-10 shrink-0">
                <div>
                    <h2 class="text-white font-black text-base">Overview</h2>
                    <p class="text-[10px] text-slate-500">{{ now('Asia/Kolkata')->format('l, d M Y') }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.bookings') }}"
                        class="flex items-center gap-2 px-4 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-black rounded-xl transition-all">
                        <i data-lucide="ticket" class="w-3.5 h-3.5"></i> View Bookings
                    </a>
                    <a href="{{ route('admin.logout') }}"
                        class="text-slate-500 hover:text-rose-400 text-xs transition-colors">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                    </a>
                </div>
            </header>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6">

                <!-- All-Time Stats -->
                <div>
                    <p class="text-[10px] text-slate-500 uppercase font-black tracking-widest mb-3">All-Time Statistics
                    </p>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="stat-card border border-slate-800 rounded-2xl p-5">
                            <div class="flex items-center justify-between mb-3">
                                <div
                                    class="w-9 h-9 bg-rose-600/10 rounded-xl flex items-center justify-center border border-rose-500/20">
                                    <i data-lucide="indian-rupee" class="w-4 h-4 text-rose-500"></i>
                                </div>
                                <span
                                    class="text-[9px] font-black text-rose-400 bg-rose-500/10 border border-rose-500/20 px-2 py-0.5 rounded-full uppercase">Revenue</span>
                            </div>
                            <p class="text-2xl font-black text-white">₹{{ number_format($totalRevenue) }}</p>
                            <p class="text-[10px] text-slate-500 mt-1">Total Earnings</p>
                        </div>
                        <div class="stat-card border border-slate-800 rounded-2xl p-5">
                            <div class="flex items-center justify-between mb-3">
                                <div
                                    class="w-9 h-9 bg-indigo-600/10 rounded-xl flex items-center justify-center border border-indigo-500/20">
                                    <i data-lucide="ticket" class="w-4 h-4 text-indigo-400"></i>
                                </div>
                                <span
                                    class="text-[9px] font-black text-indigo-400 bg-indigo-500/10 border border-indigo-500/20 px-2 py-0.5 rounded-full uppercase">Tickets</span>
                            </div>
                            <p class="text-2xl font-black text-white">{{ number_format($totalTickets) }}</p>
                            <p class="text-[10px] text-slate-500 mt-1">Seats Sold</p>
                        </div>
                        <div class="stat-card border border-slate-800 rounded-2xl p-5">
                            <div class="flex items-center justify-between mb-3">
                                <div
                                    class="w-9 h-9 bg-emerald-600/10 rounded-xl flex items-center justify-center border border-emerald-500/20">
                                    <i data-lucide="film" class="w-4 h-4 text-emerald-400"></i>
                                </div>
                                <span
                                    class="text-[9px] font-black text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded-full uppercase">Movies</span>
                            </div>
                            <p class="text-2xl font-black text-white">{{ $totalMovies }}</p>
                            <p class="text-[10px] text-slate-500 mt-1">Listed Movies</p>
                        </div>
                        <div class="stat-card border border-slate-800 rounded-2xl p-5">
                            <div class="flex items-center justify-between mb-3">
                                <div
                                    class="w-9 h-9 bg-yellow-600/10 rounded-xl flex items-center justify-center border border-yellow-500/20">
                                    <i data-lucide="receipt" class="w-4 h-4 text-yellow-400"></i>
                                </div>
                                <span
                                    class="text-[9px] font-black text-yellow-400 bg-yellow-500/10 border border-yellow-500/20 px-2 py-0.5 rounded-full uppercase">Bookings</span>
                            </div>
                            <p class="text-2xl font-black text-white">{{ $totalBookings }}</p>
                            <p class="text-[10px] text-slate-500 mt-1">Total Bookings</p>
                        </div>
                    </div>
                </div>

                <!-- Today's Stats -->
                <div>
                    <p class="text-[10px] text-slate-500 uppercase font-black tracking-widest mb-3">Today —
                        {{ now('Asia/Kolkata')->format('d M Y') }}</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="glass border border-slate-800 rounded-2xl p-5 flex items-center gap-4">
                            <div
                                class="w-12 h-12 bg-rose-600/20 rounded-2xl flex items-center justify-center border border-rose-500/30">
                                <i data-lucide="trending-up" class="w-5 h-5 text-rose-400"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-500 uppercase font-black tracking-widest">Today's
                                    Revenue</p>
                                <p class="text-3xl font-black text-white mt-0.5">₹{{ number_format($todayRevenue) }}</p>
                            </div>
                        </div>
                        <div class="glass border border-slate-800 rounded-2xl p-5 flex items-center gap-4">
                            <div
                                class="w-12 h-12 bg-indigo-600/20 rounded-2xl flex items-center justify-center border border-indigo-500/30">
                                <i data-lucide="users" class="w-5 h-5 text-indigo-400"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-500 uppercase font-black tracking-widest">Tickets Today
                                </p>
                                <p class="text-3xl font-black text-white mt-0.5">{{ $todayTickets }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Grid: Recent Bookings + Top Movies -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <!-- Recent Bookings -->
                    <div class="glass border border-slate-800 rounded-2xl overflow-hidden flex flex-col">
                        <div class="px-5 py-4 border-b border-slate-800 flex items-center justify-between">
                            <h3 class="text-white font-black text-sm">Recent Bookings</h3>
                            <a href="{{ route('admin.bookings') }}"
                                class="text-[10px] text-rose-400 hover:text-rose-300 font-bold transition-colors">View
                                All →</a>
                        </div>
                        <div class="divide-y divide-slate-800/60">
                            @forelse($recentBookings as $rb)
                                @php $rbTime = \Carbon\Carbon::parse($rb->booking_date)->timezone('Asia/Kolkata'); @endphp
                                <div
                                    class="flex items-center justify-between px-5 py-3.5 hover:bg-slate-800/20 transition-colors">
                                    <div class="flex items-center gap-3 min-w-0">
                                        @if($rb->movie && $rb->movie->poster)
                                            <img src="{{ $rb->movie->poster }}"
                                                class="w-8 h-10 object-cover rounded-lg shrink-0">
                                        @else
                                            <div
                                                class="w-8 h-10 bg-slate-800 rounded-lg flex items-center justify-center shrink-0">
                                                <i data-lucide="film" class="w-3.5 h-3.5 text-slate-600"></i>
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="text-white font-bold text-sm truncate">
                                                {{ $rb->movie->title ?? 'Unknown Movie' }}</p>
                                            <p class="text-[10px] text-slate-500">{{ count($rb->seats ?? []) }} seat(s) ·
                                                {{ $rbTime->format('h:i A, d M') }}</p>
                                        </div>
                                    </div>
                                    <span
                                        class="text-rose-400 font-black text-sm shrink-0 ml-3">₹{{ number_format($rb->total_price) }}</span>
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
                                <div
                                    class="flex items-center justify-between px-5 py-3.5 hover:bg-slate-800/20 transition-colors">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="text-[11px] font-black text-slate-600 w-5 shrink-0">{{ $i + 1 }}</span>
                                        @if($item['movie'] && $item['movie']->poster)
                                            <img src="{{ $item['movie']->poster }}"
                                                class="w-8 h-10 object-cover rounded-lg shrink-0">
                                        @else
                                            <div
                                                class="w-8 h-10 bg-slate-800 rounded-lg flex items-center justify-center shrink-0">
                                                <i data-lucide="film" class="w-3.5 h-3.5 text-slate-600"></i>
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="text-white font-bold text-sm truncate">
                                                {{ $item['movie']->title ?? 'Unknown' }}</p>
                                            <p class="text-[10px] text-slate-500">{{ $item['tickets'] }} tickets sold</p>
                                        </div>
                                    </div>
                                    <span
                                        class="text-emerald-400 font-black text-sm shrink-0 ml-3">₹{{ number_format($item['revenue']) }}</span>
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
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <a href="{{ route('admin.movies.add') }}"
                            class="flex flex-col items-center gap-2 p-4 bg-indigo-600/10 hover:bg-indigo-600 border border-indigo-500/20 hover:border-indigo-600 rounded-2xl transition-all group">
                            <i data-lucide="plus-circle" class="w-6 h-6 text-indigo-400 group-hover:text-white"></i>
                            <span class="text-xs font-black text-indigo-400 group-hover:text-white">Add Movie</span>
                        </a>
                        <a href="{{ route('admin.bookings') }}"
                            class="flex flex-col items-center gap-2 p-4 bg-rose-600/10 hover:bg-rose-600 border border-rose-500/20 hover:border-rose-600 rounded-2xl transition-all group">
                            <i data-lucide="ticket" class="w-6 h-6 text-rose-400 group-hover:text-white"></i>
                            <span class="text-xs font-black text-rose-400 group-hover:text-white">Bookings</span>
                        </a>
                        <a href="{{ route('admin.movies.index') }}"
                            class="flex flex-col items-center gap-2 p-4 bg-slate-800/50 hover:bg-slate-700 border border-slate-700 rounded-2xl transition-all group">
                            <i data-lucide="film" class="w-6 h-6 text-slate-400 group-hover:text-white"></i>
                            <span class="text-xs font-black text-slate-400 group-hover:text-white">All Movies</span>
                        </a>
                        <a href="{{ route('admin.screens.seating') }}"
                            class="flex flex-col items-center gap-2 p-4 bg-slate-800/50 hover:bg-slate-700 border border-slate-700 rounded-2xl transition-all group">
                            <i data-lucide="armchair" class="w-6 h-6 text-slate-400 group-hover:text-white"></i>
                            <span class="text-xs font-black text-slate-400 group-hover:text-white">Seating Map</span>
                        </a>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script>lucide.createIcons();</script>
</body>

</html>