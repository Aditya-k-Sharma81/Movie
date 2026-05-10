@extends('layouts.user')

@section('title', 'My Bookings | MovieTicket')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-10">
        <h1 class="text-4xl font-black text-white mb-2">My Bookings</h1>
        <p class="text-slate-400">Manage your upcoming and past movie experiences.</p>
    </div>

    <div class="space-y-6">
        <!-- Empty State Dummy -->
        <div class="bg-slate-900/50 border border-slate-800 border-dashed rounded-[2.5rem] p-16 text-center backdrop-blur-sm">
            <div class="w-20 h-20 bg-slate-800/50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="ticket" class="w-10 h-10 text-slate-600"></i>
            </div>
            <h2 class="text-2xl font-bold text-white mb-2">No Bookings Yet</h2>
            <p class="text-slate-500 max-w-xs mx-auto mb-8">
                It looks like you haven't booked any movies recently. Start exploring the latest shows!
            </p>
            <a href="{{ route('movies') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-rose-600 text-white font-bold rounded-2xl hover:bg-rose-700 transition-all active:scale-95">
                Explore Movies <i data-lucide="arrow-right" class="w-5 h-5"></i>
            </a>
        </div>

        <!-- History Placeholder -->
        <div class="pt-10">
            <h3 class="text-xl font-bold text-white mb-6">Past Experiences</h3>
            <div class="bg-slate-900/30 border border-slate-800 rounded-3xl p-6 flex items-center justify-between opacity-50 grayscale transition-all hover:grayscale-0 hover:opacity-100">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-20 bg-slate-800 rounded-xl"></div>
                    <div>
                        <h4 class="font-bold text-white">Example Movie History</h4>
                        <p class="text-sm text-slate-500">Oct 24, 2023 • Screen 4 • Seats A12, A13</p>
                    </div>
                </div>
                <span class="px-4 py-1.5 bg-slate-800 text-xs font-bold text-slate-400 rounded-full">Completed</span>
            </div>
        </div>
    </div>
</div>
@endsection
