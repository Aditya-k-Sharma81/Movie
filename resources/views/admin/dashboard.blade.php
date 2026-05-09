<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | MovieTicket</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .glass {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-item-active {
            background: linear-gradient(to right, rgba(99, 102, 241, 0.2), transparent);
            border-left: 4px solid #6366f1;
        }
    </style>
</head>

<body class="h-full text-slate-200">
    <div class="flex h-full">
        <!-- Sidebar -->
        <aside class="w-64 glass border-r border-slate-800 flex flex-col hidden md:flex">
            <div class="p-6">
                <h1 class="text-2xl font-extrabold tracking-tight text-white">
                    Movie<span class="text-indigo-500">Ticket</span>
                </h1>
            </div>

            <nav class="flex-1 mt-4 px-4 space-y-2">
                <a href="#"
                    class="sidebar-item-active flex items-center px-4 py-3 text-white rounded-lg transition-all">
                    <svg class="w-5 h-5 mr-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.movies.index') }}"
                    class="flex items-center px-4 py-3 text-slate-400 hover:text-white hover:bg-slate-800/50 rounded-lg transition-all">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z">
                        </path>
                    </svg>
                    All Movies
                </a>
                <a href="{{ route('admin.movies.add') }}"
                    class="flex items-center px-4 py-3 text-slate-400 hover:text-white hover:bg-slate-800/50 rounded-lg transition-all">
                    <svg class="w-5 h-5 mr-3 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Movie
                </a>

                <a href="{{ route('admin.screens.seating') }}"
                    class="flex items-center px-4 py-3 text-slate-400 hover:text-white hover:bg-slate-800/50 rounded-lg transition-all">
                    <svg class="w-5 h-5 mr-3 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    Seating Layout
                </a>

                <a href="{{ route('admin.profile') }}"
                    class="flex items-center px-4 py-3 text-slate-400 hover:text-white hover:bg-slate-800/50 rounded-lg transition-all">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    My Profile
                </a>
            </nav>

            <div class="p-4 border-t border-slate-800">
                <a href="{{ route('admin.profile') }}"
                    class="flex items-center p-2 rounded-xl bg-slate-900/50 border border-slate-800 hover:bg-slate-800 transition-all cursor-pointer">
                    <div
                        class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr(session('admin_name') ?? 'A', 0, 1)) }}
                    </div>
                    <div class="ml-3 overflow-hidden">
                        <p class="text-sm font-medium text-white truncate">{{ session('admin_name') ?? 'Admin User' }}
                        </p>
                        <p class="text-xs text-slate-500 truncate">{{ session('admin_email') ?? 'admin@movieticket.com'
                            }}</p>
                    </div>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="h-16 glass border-b border-slate-800 flex items-center justify-between px-8 z-10">
                <h2 class="text-xl font-semibold text-white">Overview</h2>
                <div class="flex items-center space-x-4">
                    <button class="p-2 text-slate-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                    </button>
                    <div class="h-8 w-px bg-slate-800"></div>
                    <a href="/admin/logout"
                        class="flex items-center text-sm font-medium text-slate-300 hover:text-white transition-colors">
                        Logout
                    </a>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="flex-1 overflow-y-auto p-8 bg-slate-950/50">
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="glass p-6 rounded-2xl border border-slate-800">
                        <p class="text-sm text-slate-400 font-medium mb-1">Total Revenue</p>
                        <h3 class="text-3xl font-bold text-white">$24,500</h3>
                        <p class="text-xs text-emerald-500 mt-2 flex items-center font-medium">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            12% from last month
                        </p>
                    </div>
                    <div class="glass p-6 rounded-2xl border border-slate-800">
                        <p class="text-sm text-slate-400 font-medium mb-1">Movies Playing</p>
                        <h3 class="text-3xl font-bold text-white">42</h3>
                        <p class="text-xs text-slate-500 mt-2 flex items-center font-medium">
                            Currently showing in 12 venues
                        </p>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 gap-8">
                    <!-- Side Card -->
                    <div class="glass p-6 rounded-2xl border border-slate-800 max-w-md w-full">
                        <h3 class="text-lg font-bold text-white mb-6">Quick Actions</h3>
                        <div class="space-y-4">
                            <a href="{{ route('admin.movies.add') }}"
                                class="block text-center w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3 rounded-xl transition-all shadow-lg shadow-indigo-500/20">
                                + Add New Movie
                            </a>
                            <a href="{{ route('admin.movies.index') }}"
                                class="block text-center w-full bg-slate-800 hover:bg-slate-700 text-white font-bold py-3 rounded-xl transition-all">
                                View All Movies
                            </a>
                            <a href="{{ route('admin.screens.seating') }}"
                                class="block text-center w-full border border-slate-700 hover:border-slate-600 text-slate-300 hover:text-white font-bold py-3 rounded-xl transition-all">
                                Manage Seating
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>