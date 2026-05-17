@extends('admin.layouts.app')

@section('title', $event->title . ' — Bookings')

@section('styles')
    ::-webkit-scrollbar { width: 4px; height: 4px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
@endsection

@section('header')
<header class="glass border-b border-slate-800/60 sticky top-0 z-50 px-6 py-3 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('admin.bookings') }}"
           class="p-2 bg-slate-800 hover:bg-slate-700 rounded-xl text-slate-400 hover:text-white transition-all">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
        </a>
        @if($event->poster)
            <img src="{{ $event->poster }}" class="w-8 h-10 object-cover rounded-lg shrink-0">
        @endif
        <div>
            <h1 class="text-base font-black text-white leading-none">{{ $event->title }}</h1>
            <p class="text-[10px] text-slate-500 mt-0.5">
                {{ \Carbon\Carbon::parse($event->start_time)->format('d M Y · h:i A') }} ·
                Total Bookings
            </p>
        </div>
    </div>
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.logout') }}" class="text-slate-500 hover:text-rose-400 text-xs transition-colors">Logout</a>
    </div>
</header>
@endsection

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Summary Row -->
        <div class="grid grid-cols-3 gap-4 mb-8">
            <div class="glass border border-slate-800 rounded-2xl p-4 flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-600/10 rounded-xl flex items-center justify-center border border-purple-500/20 shrink-0">
                    <i data-lucide="indian-rupee" class="w-4 h-4 text-purple-500"></i>
                </div>
                <div>
                    <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest">Revenue</p>
                    <p class="text-xl font-black text-white">₹{{ number_format($totalRevenue) }}</p>
                </div>
            </div>
            <div class="glass border border-slate-800 rounded-2xl p-4 flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-600/10 rounded-xl flex items-center justify-center border border-indigo-500/20 shrink-0">
                    <i data-lucide="armchair" class="w-4 h-4 text-indigo-400"></i>
                </div>
                <div>
                    <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest">Seats Sold</p>
                    <p class="text-xl font-black text-white">{{ $totalSeats }}</p>
                </div>
            </div>
            <div class="glass border border-slate-800 rounded-2xl p-4 flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-600/10 rounded-xl flex items-center justify-center border border-emerald-500/20 shrink-0">
                    <i data-lucide="receipt" class="w-4 h-4 text-emerald-400"></i>
                </div>
                <div>
                    <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest">Bookings</p>
                    <p class="text-xl font-black text-white">{{ $bookings->count() }}</p>
                </div>
            </div>
        </div>

        @if($bookings->isEmpty())
            <div class="glass border border-dashed border-slate-800 rounded-3xl p-20 text-center">
                <i data-lucide="inbox" class="w-14 h-14 text-slate-700 mx-auto mb-4"></i>
                <h3 class="text-white font-black text-xl mb-2">No Bookings</h3>
                <p class="text-slate-500 text-sm">Nobody has booked this event yet.</p>
            </div>
        @else
            <!-- Master Table -->
            <div class="glass border border-slate-800 rounded-2xl overflow-hidden">
                <!-- Table Header -->
                <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between">
                    <div>
                        <h2 class="text-white font-black text-sm">All Attendees</h2>
                        <p class="text-[10px] text-slate-500 mt-0.5">{{ $totalSeats }} attendee(s) across {{ $bookings->count() }} booking(s)</p>
                    </div>
                </div>

                <!-- Scrollable Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-800 bg-slate-900/50">
                                <th class="px-5 py-3 text-left text-[9px] font-black text-slate-500 uppercase tracking-widest w-8">#</th>
                                <th class="px-5 py-3 text-left text-[9px] font-black text-slate-500 uppercase tracking-widest">Booking ID</th>
                                <th class="px-5 py-3 text-left text-[9px] font-black text-slate-500 uppercase tracking-widest">Seat</th>
                                <th class="px-5 py-3 text-left text-[9px] font-black text-slate-500 uppercase tracking-widest">Attendee Name</th>
                                <th class="px-5 py-3 text-left text-[9px] font-black text-slate-500 uppercase tracking-widest">Email</th>
                                <th class="px-5 py-3 text-left text-[9px] font-black text-slate-500 uppercase tracking-widest">Phone</th>
                                <th class="px-5 py-3 text-left text-[9px] font-black text-slate-500 uppercase tracking-widest">Booked At</th>
                                <th class="px-5 py-3 text-right text-[9px] font-black text-slate-500 uppercase tracking-widest">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @php $rowNum = 1; @endphp
                            @foreach($bookings as $booking)
                                @php
                                    $bookedAt = \Carbon\Carbon::parse($booking->booking_date)->timezone('Asia/Kolkata');
                                    $attendeeCount = count($booking->attendees ?? []);
                                    $pricePerSeat = $attendeeCount > 0 ? round($booking->total_price / $attendeeCount) : 0;
                                @endphp
                                @foreach($booking->attendees as $i => $attendee)
                                @php $seatId = $booking->seats[$i] ?? '?'; @endphp
                                <tr class="hover:bg-slate-800/30 transition-colors group">
                                    <!-- Row # -->
                                    <td class="px-5 py-3.5 text-slate-600 text-xs font-bold">{{ $rowNum++ }}</td>

                                    <!-- Booking ID (only show on first row of booking) -->
                                    <td class="px-5 py-3.5">
                                        @if($i === 0)
                                        <div class="flex flex-col gap-0.5">
                                            <span class="text-[10px] font-black text-purple-400 font-mono">#{{ strtoupper(substr($booking->_id, -8)) }}</span>
                                            <span class="text-[9px] text-slate-600">{{ $attendeeCount }} seat(s)</span>
                                        </div>
                                        @else
                                        <span class="text-[9px] text-slate-700 font-mono">↳ same</span>
                                        @endif
                                    </td>

                                    <!-- Seat -->
                                    <td class="px-5 py-3.5">
                                        <span class="px-2 py-0.5 bg-purple-500/10 text-purple-400 border border-purple-500/20 rounded-md text-[10px] font-black uppercase tracking-wider">
                                            {{ $seatId }}
                                        </span>
                                    </td>

                                    <!-- Name -->
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 bg-slate-800 rounded-full flex items-center justify-center shrink-0 border border-slate-700 text-[10px] font-black text-slate-400">
                                                {{ strtoupper(substr($attendee['name'] ?? 'A', 0, 1)) }}
                                            </div>
                                            <span class="text-white font-bold text-sm">{{ $attendee['name'] ?? '—' }}</span>
                                        </div>
                                    </td>

                                    <!-- Email -->
                                    <td class="px-5 py-3.5">
                                        <span class="text-slate-400 text-xs">{{ $attendee['email'] ?? '—' }}</span>
                                    </td>

                                    <!-- Phone -->
                                    <td class="px-5 py-3.5">
                                        <span class="text-slate-400 text-xs font-mono">{{ $attendee['phone'] ?? '—' }}</span>
                                    </td>

                                    <!-- Booked At -->
                                    <td class="px-5 py-3.5">
                                        <span class="text-slate-500 text-xs">{{ $bookedAt->format('h:i A') }}</span>
                                    </td>

                                    <!-- Amount (only on first row) -->
                                    <td class="px-5 py-3.5 text-right">
                                        @if($i === 0)
                                        <span class="px-3 py-1 bg-purple-600/10 text-purple-400 border border-purple-500/20 rounded-lg text-xs font-black">
                                            ₹{{ number_format($booking->total_price) }}
                                        </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach

                                <!-- Booking separator row -->
                                @if(!$loop->last)
                                <tr class="bg-slate-900/20">
                                    <td colspan="8" class="px-5 py-1">
                                        <div class="border-t border-dashed border-slate-800/80"></div>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>

                        <!-- Footer Total -->
                        <tfoot>
                            <tr class="border-t border-slate-700 bg-slate-900/60">
                                <td colspan="6" class="px-5 py-4">
                                    <span class="text-[10px] text-slate-500 uppercase font-black tracking-widest">Total</span>
                                </td>
                                <td class="px-5 py-4 text-xs text-slate-400 font-bold">{{ $totalSeats }} seats</td>
                                <td class="px-5 py-4 text-right">
                                    <span class="text-white font-black text-base">₹{{ number_format($totalRevenue) }}</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @endif
    </div>@endsection
