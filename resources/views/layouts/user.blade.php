<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MovieTicket')</title>

    <!-- CSRF TOKEN -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        @yield('styles')
    </style>
</head>

<body class="min-h-screen bg-slate-950 text-slate-200 antialiased overflow-x-hidden flex flex-col">

    <!-- Background Decoration -->
    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute top-[-10%] right-[-10%] w-[50%] h-[50%] bg-rose-900/10 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[50%] h-[50%] bg-indigo-900/10 rounded-full blur-[120px]"></div>
    </div>

    <!-- Navigation -->
    @if(session('user_id'))
    <nav class="fixed top-0 left-0 w-full z-[100] bg-slate-950/90 backdrop-blur-xl border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 bg-rose-600 rounded-lg">
                        <i data-lucide="clapperboard" class="w-5 h-5 text-white"></i>
                    </div>
                    <a href="/" class="text-xl font-bold text-white tracking-tight">MovieTicket</a>
                </div>
                <div class="flex items-center gap-6">
                    <a href="{{ route('movies') }}" class="text-sm font-medium {{ request()->is('movies') ? 'text-rose-500' : 'text-slate-400 hover:text-white' }} transition-colors">Movies</a>
                    <a href="{{ route('events') }}" class="text-sm font-medium {{ request()->is('events') ? 'text-rose-500' : 'text-slate-400 hover:text-white' }} transition-colors">Events</a>
                    <a href="{{ route('bookings') }}" class="text-sm font-medium {{ request()->is('bookings') ? 'text-rose-500' : 'text-slate-400 hover:text-white' }} transition-colors">Bookings</a>
                    <div class="h-8 w-px bg-slate-800"></div>
                    <div class="flex items-center gap-3">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-bold text-white">{{ session('user_name') }}</p>
                            <p class="text-[10px] text-slate-500">{{ session('user_email') }}</p>
                        </div>
                        <div class="w-10 h-10 bg-slate-800 rounded-full border border-slate-700 flex items-center justify-center">
                            <i data-lucide="user" class="w-5 h-5 text-slate-400"></i>
                        </div>
                        <a href="javascript:void(0)" id="userLogoutBtn" class="p-2 text-slate-400 hover:text-rose-500 transition-colors">
                            <i data-lucide="log-out" class="w-5 h-5"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <div class="h-16"></div>
    @endif

    <main class="flex-grow relative z-10">
        @yield('content')
    </main>

    @yield('footer')

    <script>
        lucide.createIcons();

        // Logout Confirmation
        $('#userLogoutBtn').on('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Sign Out?',
                text: "Are you sure you want to log out of your account?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#334155',
                confirmButtonText: 'Yes, Log out',
                background: '#0f172a',
                color: '#f8fafc'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('logout') }}";
                }
            });
        });
    </script>

    @yield('scripts')
</body>
</html>
