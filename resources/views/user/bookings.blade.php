@extends('layouts.user')

@section('title', 'My Bookings | MovieTicket')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <!-- Page Header -->
    <div class="mb-10">
        <h1 class="text-4xl font-black text-white mb-2">My Bookings</h1>
        <p class="text-slate-400">All your confirmed ticket reservations.</p>
    </div>

    @if($bookings->isEmpty())
        <!-- Empty State -->
        <div class="bg-slate-900/50 border border-dashed border-slate-800 rounded-[2.5rem] p-16 text-center">
            <div class="w-20 h-20 bg-slate-800/50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="ticket" class="w-10 h-10 text-slate-600"></i>
            </div>
            <h2 class="text-2xl font-bold text-white mb-2">No Bookings Yet</h2>
            <p class="text-slate-500 max-w-xs mx-auto mb-8">You haven't booked any tickets yet. Start exploring!</p>
            <a href="{{ route('movies') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-rose-600 text-white font-bold rounded-2xl hover:bg-rose-700 transition-all active:scale-95">
                Explore Movies <i data-lucide="arrow-right" class="w-5 h-5"></i>
            </a>
        </div>
    @else
        <div class="space-y-6">
            @foreach($bookings as $booking)
            @php
                $movie = $booking->movie;
                $bookingDate = \Carbon\Carbon::parse($booking->booking_date)->timezone('Asia/Kolkata');
                $showTime = $movie ? \Carbon\Carbon::parse($movie->start_time)->timezone('Asia/Kolkata') : null;
                $isUpcoming = $showTime && $showTime->isFuture();
            @endphp

            <div class="bg-slate-900/40 backdrop-blur-sm border border-slate-800/60 rounded-3xl overflow-hidden hover:border-slate-700 transition-all">
                
                <!-- Booking Card Header -->
                <div class="flex flex-col md:flex-row gap-0">
                    
                    <!-- Movie Poster -->
                    <div class="w-full md:w-28 h-36 md:h-auto shrink-0">
                        @if($movie && $movie->poster)
                            <img src="{{ $movie->poster }}" alt="{{ $movie->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-slate-800 flex items-center justify-center">
                                <i data-lucide="film" class="w-8 h-8 text-slate-600"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Main Info -->
                    <div class="flex-grow p-6">
                        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                            <div>
                                <!-- Movie Title & Status -->
                                <div class="flex items-center gap-3 mb-2">
                                    <h2 class="text-xl font-black text-white">{{ $movie ? $movie->title : 'Movie Unavailable' }}</h2>
                                    <span class="px-2.5 py-0.5 text-[9px] font-black uppercase rounded-full tracking-widest {{ $isUpcoming ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-slate-800 text-slate-500 border border-slate-700' }}">
                                        {{ $isUpcoming ? 'Upcoming' : 'Completed' }}
                                    </span>
                                </div>

                                <!-- Show Date & Time -->
                                @if($showTime)
                                <div class="flex flex-wrap items-center gap-4 text-sm text-slate-400 mb-4">
                                    <span class="flex items-center gap-1.5">
                                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-rose-500"></i>
                                        {{ $showTime->format('d M Y') }}
                                    </span>
                                    <span class="flex items-center gap-1.5">
                                        <i data-lucide="clock" class="w-3.5 h-3.5 text-rose-500"></i>
                                        {{ $showTime->format('h:i A') }}
                                    </span>
                                    <span class="flex items-center gap-1.5">
                                        <i data-lucide="timer" class="w-3.5 h-3.5 text-rose-500"></i>
                                        {{ $movie->duration ?? 'N/A' }} min
                                    </span>
                                </div>
                                @endif

                                <!-- Seats -->
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="text-[10px] text-slate-500 uppercase font-black tracking-widest">Seats:</span>
                                    @foreach($booking->seats as $seat)
                                        <span class="px-2 py-0.5 bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 rounded-lg text-[10px] font-black uppercase">{{ $seat }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Price & Booking Date -->
                            <div class="text-right shrink-0">
                                <p class="text-2xl font-black text-white">₹{{ number_format($booking->total_price) }}</p>
                                <p class="text-[10px] text-slate-500 uppercase font-bold mt-1">Booked {{ $bookingDate->format('d M, h:i A') }}</p>
                                @if($isUpcoming)
                                <button onclick="cancelBooking('{{ $booking->_id }}')"
                                    class="mt-3 px-4 py-2 bg-red-600/10 hover:bg-red-600 text-red-400 hover:text-white border border-red-600/30 hover:border-red-600 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all flex items-center gap-1.5 ml-auto">
                                    <i data-lucide="x-circle" class="w-3.5 h-3.5"></i>
                                    Cancel Ticket
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attendees Section -->
                @if($booking->attendees && count($booking->attendees) > 0)
                <div class="border-t border-slate-800 px-6 py-5">
                    <h4 class="text-[10px] text-slate-500 uppercase font-black tracking-widest mb-4 flex items-center gap-2">
                        <i data-lucide="users" class="w-3.5 h-3.5 text-rose-500"></i>
                        Attendees ({{ count($booking->attendees) }})
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($booking->attendees as $index => $attendee)
                        @php $seatId = $booking->seats[$index] ?? '?'; @endphp
                        <div class="bg-slate-950/50 border border-slate-800 rounded-2xl p-4 flex flex-col gap-2">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[9px] text-indigo-400 font-black uppercase tracking-widest">Seat {{ $seatId }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-white font-bold">
                                <i data-lucide="user" class="w-3.5 h-3.5 text-slate-500 shrink-0"></i>
                                {{ $attendee['name'] ?? 'N/A' }}
                            </div>
                            <div class="flex items-center gap-2 text-xs text-slate-400">
                                <i data-lucide="mail" class="w-3.5 h-3.5 text-slate-600 shrink-0"></i>
                                {{ $attendee['email'] ?? 'N/A' }}
                            </div>
                            <div class="flex items-center gap-2 text-xs text-slate-400">
                                <i data-lucide="phone" class="w-3.5 h-3.5 text-slate-600 shrink-0"></i>
                                {{ $attendee['phone'] ?? 'N/A' }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
function cancelBooking(bookingId) {
    Swal.fire({
        title: 'Cancel Ticket?',
        text: 'This will free up your seats and cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Cancel It',
        cancelButtonText: 'Keep Ticket',
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#334155',
        background: '#0f172a',
        color: '#f8fafc',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/booking/${bookingId}/cancel`,
                type: 'DELETE',
                data: { _token: $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Cancelled!',
                        text: response.message,
                        background: '#0f172a',
                        color: '#f8fafc',
                        confirmButtonColor: '#e11d48',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => location.reload());
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Could not cancel booking.',
                        background: '#0f172a',
                        color: '#f8fafc',
                        confirmButtonColor: '#e11d48'
                    });
                }
            });
        }
    });
}
</script>
@endsection
