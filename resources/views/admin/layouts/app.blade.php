<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') | MovieTicket</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <!-- Common sweetalert for add/edit forms -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    @yield('head_scripts')
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass { background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.08); }
        .stat-card { background: rgba(30, 41, 59, 0.5); }
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        @yield('styles')
    </style>
</head>

<body class="h-full text-slate-200">
    <div class="flex h-full">
        <!-- Mobile Sidebar Backdrop -->
        <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-40 hidden md:hidden transition-opacity opacity-0"></div>

        <!-- Sidebar -->
        <aside id="sidebar" class="fixed md:static inset-y-0 left-0 z-50 w-64 glass border-r border-slate-800 flex flex-col transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out shrink-0 h-full">
            <div class="p-6 border-b border-slate-800 flex justify-between items-center">
                <div>
                    <h1 class="text-xl font-black tracking-tight text-white">Movie<span class="text-rose-500">Ticket</span></h1>
                    <p class="text-[10px] text-slate-500 mt-0.5 uppercase tracking-widest font-bold">Admin Panel</p>
                </div>
                <button id="close-sidebar" class="md:hidden text-slate-400 hover:text-white p-1">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <nav class="flex-1 mt-4 px-3 space-y-1">
                @php $currentRoute = request()->route()->getName() ?? ''; @endphp
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center px-3 py-2.5 rounded-xl transition-all text-sm {{ $currentRoute == 'admin.dashboard' ? 'text-white bg-rose-600/10 border-l-2 border-rose-500 rounded-l-none font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                    <i data-lucide="layout-dashboard" class="w-4 h-4 mr-3 {{ $currentRoute == 'admin.dashboard' ? 'text-rose-400' : '' }}"></i> Dashboard
                </a>
                <a href="{{ route('admin.movies.index') }}"
                    class="flex items-center px-3 py-2.5 rounded-xl transition-all text-sm {{ str_starts_with($currentRoute, 'admin.movies') && $currentRoute != 'admin.movies.add' ? 'text-white bg-rose-600/10 border-l-2 border-rose-500 rounded-l-none font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                    <i data-lucide="film" class="w-4 h-4 mr-3 {{ str_starts_with($currentRoute, 'admin.movies') && $currentRoute != 'admin.movies.add' ? 'text-rose-400' : '' }}"></i> All Movies
                </a>
                <a href="{{ route('admin.movies.add') }}"
                    class="flex items-center px-3 py-2.5 rounded-xl transition-all text-sm {{ $currentRoute == 'admin.movies.add' ? 'text-white bg-indigo-600/10 border-l-2 border-indigo-500 rounded-l-none font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                    <i data-lucide="plus-circle" class="w-4 h-4 mr-3 {{ $currentRoute == 'admin.movies.add' ? 'text-indigo-400' : 'text-indigo-400' }}"></i> Add Movie
                </a>
                <a href="{{ route('admin.bookings') }}"
                    class="flex items-center px-3 py-2.5 rounded-xl transition-all text-sm {{ str_starts_with($currentRoute, 'admin.bookings') ? 'text-white bg-rose-600/10 border-l-2 border-rose-500 rounded-l-none font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                    <i data-lucide="ticket" class="w-4 h-4 mr-3 {{ str_starts_with($currentRoute, 'admin.bookings') ? 'text-rose-400' : 'text-rose-400' }}"></i> Bookings
                </a>
                <a href="{{ route('admin.today.movies') }}"
                    class="flex items-center px-3 py-2.5 rounded-xl transition-all text-sm group {{ $currentRoute == 'admin.today.movies' ? 'text-white bg-amber-500/20 border-l-2 border-amber-500 rounded-l-none font-bold' : 'text-slate-400 hover:text-white hover:bg-amber-500/10' }}">
                    <i data-lucide="calendar-days" class="w-4 h-4 mr-3 {{ $currentRoute == 'admin.today.movies' ? 'text-amber-400' : 'text-amber-400 group-hover:text-amber-300' }}"></i>
                    <span>Today's Movies</span>
                    <span class="ml-auto text-[9px] font-black px-1.5 py-0.5 bg-amber-500/20 text-amber-400 rounded-md border border-amber-500/20">TODAY</span>
                </a>
                <a href="{{ route('admin.screens.seating') }}"
                    class="flex items-center px-3 py-2.5 rounded-xl transition-all text-sm {{ $currentRoute == 'admin.screens.seating' ? 'text-white bg-blue-600/10 border-l-2 border-blue-500 rounded-l-none font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                    <i data-lucide="armchair" class="w-4 h-4 mr-3 {{ $currentRoute == 'admin.screens.seating' ? 'text-blue-400' : 'text-blue-400' }}"></i> Seating Map
                </a>
                <a href="{{ route('admin.profile') }}"
                    class="flex items-center px-3 py-2.5 rounded-xl transition-all text-sm {{ $currentRoute == 'admin.profile' ? 'text-white bg-emerald-600/10 border-l-2 border-emerald-500 rounded-l-none font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                    <i data-lucide="user-circle" class="w-4 h-4 mr-3 {{ $currentRoute == 'admin.profile' ? 'text-emerald-400' : '' }}"></i> My Profile
                </a>
            </nav>
            <!-- Admin Info -->
            <div class="p-4 border-t border-slate-800">
                <a href="{{ route('admin.profile') }}"
                    class="flex items-center p-2.5 rounded-xl bg-slate-900/50 border border-slate-800 hover:bg-slate-800 transition-all">
                    <div class="w-9 h-9 rounded-full bg-rose-600 flex items-center justify-center text-white font-black text-sm shrink-0">
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
        <main class="flex-1 flex flex-col overflow-hidden bg-slate-950">
            @hasSection('header')
                @yield('header')
            @else
                <!-- Default Top Bar -->
                <header class="h-14 glass border-b border-slate-800 flex items-center justify-between px-4 sm:px-6 z-10 shrink-0">
                    <div class="flex items-center gap-3 min-w-0">
                        <button id="mobile-menu-btn" class="md:hidden text-slate-400 hover:text-white shrink-0 p-1">
                            <i data-lucide="menu" class="w-5 h-5"></i>
                        </button>
                        <div class="min-w-0 truncate">
                            <h2 class="text-white font-black text-base truncate">@yield('page_title', 'Overview')</h2>
                            <p class="text-[10px] text-slate-500 hidden sm:block">{{ now('Asia/Kolkata')->format('l, d M Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 shrink-0 ml-2">
                        <a href="{{ route('admin.bookings') }}" class="flex items-center gap-2 px-3 sm:px-4 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-[10px] sm:text-xs font-black rounded-xl transition-all">
                            <i data-lucide="ticket" class="w-3.5 h-3.5"></i> <span class="hidden sm:inline">View Bookings</span>
                        </a>
                        <a href="{{ route('admin.logout') }}" class="text-slate-500 hover:text-rose-400 text-xs transition-colors">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                        </a>
                    </div>
                </header>
            @endif

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto relative w-full">
                @yield('content')
            </div>
        </main>
    </div>

    @yield('scripts')
    <script>
        $(document).ready(function() {
            const sidebar = $('#sidebar');
            const backdrop = $('#sidebar-backdrop');
            
            $('#mobile-menu-btn').click(function() {
                sidebar.removeClass('-translate-x-full');
                backdrop.removeClass('hidden');
                setTimeout(() => backdrop.removeClass('opacity-0'), 10);
            });

            $('#close-sidebar, #sidebar-backdrop').click(function() {
                sidebar.addClass('-translate-x-full');
                backdrop.addClass('opacity-0');
                setTimeout(() => backdrop.addClass('hidden'), 300);
            });
        });
        lucide.createIcons();
    </script>
</body>
</html>
