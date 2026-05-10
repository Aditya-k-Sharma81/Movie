@extends('layouts.user')

@section('title', 'Create Account | MovieTicket')

@section('content')
<div class="min-h-full flex flex-col justify-center py-6 sm:px-6 lg:px-8 z-10 relative">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <!-- Brand -->
        <div class="flex justify-center">
            <div class="p-2 bg-rose-600 rounded-xl shadow-lg shadow-rose-900/20">
                <i data-lucide="clapperboard" class="w-6 h-6 text-white"></i>
            </div>
        </div>
        <h2 class="mt-4 text-center text-2xl font-extrabold text-white tracking-tight">
            Create your account
        </h2>
        <p class="mt-1 text-center text-xs text-slate-400">
            Already have an account? 
            <a href="/login" class="font-medium text-rose-500 hover:text-rose-400 transition-colors">
                Sign in here
            </a>
        </p>
    </div>

    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-slate-900/50 backdrop-blur-xl py-6 px-6 shadow-2xl border border-slate-800 sm:rounded-3xl sm:px-10">
            <form id="registerForm" class="space-y-4">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider ml-1">
                        Full Name
                    </label>
                    <div class="mt-0.5 relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i data-lucide="user" class="h-4 w-4 text-slate-500"></i>
                        </div>
                        <input type="text" id="name" required
                            class="block w-full pl-10 pr-4 py-2.5 bg-slate-800/50 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 rounded-xl text-sm transition-all"
                            placeholder="Enter your name">
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider ml-1">
                        Email Address
                    </label>
                    <div class="mt-0.5 relative rounded-xl shadow-sm">
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
                    <label for="password" class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider ml-1">
                        Password
                    </label>
                    <div class="mt-0.5 relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="h-4 w-4 text-slate-500"></i>
                        </div>
                        <input type="password" id="password" required
                            class="block w-full pl-10 pr-4 py-2.5 bg-slate-800/50 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 rounded-xl text-sm transition-all"
                            placeholder="••••••••">
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider ml-1">
                        Confirm Password
                    </label>
                    <div class="mt-0.5 relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i data-lucide="shield-check" class="h-4 w-4 text-slate-500"></i>
                        </div>
                        <input type="password" id="password_confirmation" required
                            class="block w-full pl-10 pr-4 py-2.5 bg-slate-800/50 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 rounded-xl text-sm transition-all"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center">
                    <input id="terms" name="terms" type="checkbox" required
                        class="h-3.5 w-3.5 text-rose-600 focus:ring-rose-500 bg-slate-800 border-slate-700 rounded transition-all">
                    <label for="terms" class="ml-2 block text-xs text-slate-400">
                        I agree to the <a href="#" class="text-rose-500 hover:underline">Terms</a> and <a href="#" class="text-rose-500 hover:underline">Privacy Policy</a>
                    </label>
                </div>

                <div class="pt-1">
                    <button type="submit"
                        class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 transition-all active:scale-[0.98]">
                        Create Account
                    </button>
                </div>
            </form>

            <div class="mt-4">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-800"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-slate-900/50 text-slate-500 uppercase tracking-tighter text-[10px] font-bold">
                            Secure Registration
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
    // CSRF Setup for AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#registerForm').on('submit', function (e) {
        e.preventDefault();

        const name = $('#name').val();
        const email = $('#email').val();
        const password = $('#password').val();
        const password_confirmation = $('#password_confirmation').val();
        const terms = $('#terms').is(':checked');

        if (!name || !email || !password) {
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

        if (password !== password_confirmation) {
            Swal.fire({
                icon: 'warning',
                title: 'Mismatch',
                text: 'Passwords do not match.',
                background: '#0f172a',
                color: '#f8fafc',
                confirmButtonColor: '#e11d48'
            });
            return;
        }

        if (!terms) {
            Swal.fire({
                icon: 'info',
                title: 'Terms & Conditions',
                text: 'You must agree to the terms and conditions.',
                background: '#0f172a',
                color: '#f8fafc',
                confirmButtonColor: '#e11d48'
            });
            return;
        }

        Swal.fire({
            title: 'Creating Account...',
            text: 'Please wait...',
            allowOutsideClick: false,
            background: '#0f172a',
            color: '#f8fafc',
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: "{{ route('register.post') }}",
            type: "POST",
            data: {
                name: name,
                email: email,
                password: password,
                password_confirmation: password_confirmation,
                terms: terms ? '1' : '0'
            },
            success: function (res) {
                if (res.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: res.message,
                        background: '#0f172a',
                        color: '#f8fafc',
                        confirmButtonColor: '#e11d48'
                    }).then(() => {
                        window.location.href = "/login";
                    });
                }
            },
            error: function (xhr) {
                let errors = xhr.responseJSON?.errors;
                let msg = xhr.responseJSON?.message || 'Something went wrong.';

                if (errors) {
                    msg = Object.values(errors).flat().join('<br>');
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: msg,
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
