@extends('layouts.user')

@section('title', 'Login | MovieTicket')

@section('content')
<div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8 z-10 relative">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <!-- Brand -->
        <div class="flex justify-center">
            <div class="p-2 bg-rose-600 rounded-xl shadow-lg shadow-rose-900/20">
                <i data-lucide="clapperboard" class="w-6 h-6 text-white"></i>
            </div>
        </div>
        <h2 class="mt-4 text-center text-2xl font-extrabold text-white tracking-tight">
            Welcome back
        </h2>
        <p class="mt-1 text-center text-xs text-slate-400">
            Don't have an account? 
            <a href="/register" class="font-medium text-rose-500 hover:text-rose-400 transition-colors">
                Register here
            </a>
        </p>
    </div>

    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-slate-900/50 backdrop-blur-xl py-8 px-6 shadow-2xl border border-slate-800 sm:rounded-3xl sm:px-10">
            <form id="loginForm" class="space-y-6">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider ml-1">
                        Email Address
                    </label>
                    <div class="mt-1 relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i data-lucide="mail" class="h-4 w-4 text-slate-500"></i>
                        </div>
                        <input type="email" id="email" required
                            class="block w-full pl-10 pr-4 py-2.5 bg-slate-800/50 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 rounded-xl text-sm transition-all"
                            placeholder="name@example.com">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <div class="flex justify-between items-center">
                        <label for="password" class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider ml-1">
                            Password
                        </label>
                        <a href="#" class="text-[10px] font-bold text-rose-500 hover:text-rose-400 uppercase tracking-wider">
                            Forgot?
                        </a>
                    </div>
                    <div class="mt-1 relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="h-4 w-4 text-slate-500"></i>
                        </div>
                        <input type="password" id="password" required
                            class="block w-full pl-10 pr-4 py-2.5 bg-slate-800/50 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 rounded-xl text-sm transition-all"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember_me" type="checkbox"
                            class="h-3.5 w-3.5 text-rose-600 focus:ring-rose-500 bg-slate-800 border-slate-700 rounded transition-all">
                        <label for="remember_me" class="ml-2 block text-xs text-slate-400">
                            Remember me
                        </label>
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 transition-all active:scale-[0.98]">
                        Sign In
                    </button>
                </div>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-800"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-slate-900/50 text-slate-500 uppercase tracking-tighter text-[10px] font-bold">
                            Secure Access
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#loginForm').on('submit', function (e) {
        e.preventDefault();

        const email = $('#email').val();
        const password = $('#password').val();

        if (!email || !password) {
            Swal.fire({
                icon: 'error',
                title: 'Wait!',
                text: 'Please fill in all fields.',
                background: '#0f172a',
                color: '#f8fafc',
                confirmButtonColor: '#e11d48'
            });
            return;
        }

        Swal.fire({
            title: 'Signing in...',
            allowOutsideClick: false,
            background: '#0f172a',
            color: '#f8fafc',
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: "{{ route('login.post') }}",
            type: "POST",
            data: {
                email: email,
                password: password,
                remember: $('#remember_me').is(':checked') ? '1' : '0'
            },
            success: function (res) {
                if (res.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Welcome back!',
                        text: res.message,
                        background: '#0f172a',
                        color: '#f8fafc',
                        confirmButtonColor: '#e11d48'
                    }).then(() => {
                        window.location.href = "/";
                    });
                }
            },
            error: function (xhr) {
                let msg = xhr.responseJSON?.message || 'Invalid credentials.';
                Swal.fire({
                    icon: 'error',
                    title: 'Login Failed',
                    text: msg,
                    background: '#0f172a',
                    color: '#f8fafc',
                    confirmButtonColor: '#e11d48'
                });
            }
        });
    });
});
</script>
@endsection