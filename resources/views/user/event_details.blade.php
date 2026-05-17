@extends('layouts.user')

@section('title', $event->title . ' | MovieTicket')

@section('styles')
<style>
    .seat { transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
    .seat.available:hover { transform: scale(1.25); z-index: 10; cursor: pointer; }
    .seat.selected { background-color: #16a34a !important; box-shadow: 0 0 15px rgba(22, 163, 74, 0.6); transform: scale(1.1); }
    .seat.booked { background-color: #dc2626 !important; opacity: 0.7; cursor: not-allowed; pointer-events: none; }
    .seat.blocked { background-color: #1e293b !important; opacity: 0.3; cursor: not-allowed; pointer-events: none; }
    .seat.standard { background-color: #6366f1; }
    .seat.premium { background-color: #ec4899; }
    .seat.vip { background-color: #eab308; }
    .seat.empty { background-color: transparent !important; border: none !important; pointer-events: none; }
    
    .screen-glow {
        background: linear-gradient(180deg, rgba(225, 29, 72, 0.4) 0%, rgba(225, 29, 72, 0) 100%);
        filter: blur(20px);
    }

    /* Custom Scrollbar for Attendees */
    #attendeesContainer::-webkit-scrollbar { height: 4px; }
    #attendeesContainer::-webkit-scrollbar-track { background: transparent; }
    #attendeesContainer::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
</style>
@endsection

@section('content')
<div class="relative min-h-screen">
    <!-- Hero Backdrop -->
    <div class="absolute top-0 left-0 w-full h-[70vh] z-0">
        <img src="{{ $event->poster ?? 'https://via.placeholder.com/1920x1080?text=No+Poster' }}" 
             alt="{{ $event->title }}" 
             class="w-full h-full object-cover opacity-20 blur-sm">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/80 to-transparent"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-12">
        <div class="flex flex-col lg:flex-row gap-12 items-start">
            
            <!-- Event Poster -->
            <div class="w-full lg:w-1/3 flex-shrink-0">
                <div class="relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-rose-600 to-indigo-600 rounded-[2.5rem] blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
                    <div class="relative rounded-[2rem] overflow-hidden border border-slate-800 shadow-2xl">
                        <img src="{{ $event->poster ?? 'https://via.placeholder.com/600x900?text=No+Poster' }}" 
                             alt="{{ $event->title }}" 
                             class="w-full aspect-[2/3] object-cover transition-transform duration-700 group-hover:scale-110">
                    </div>
                </div>
            </div>

            <!-- Event Details -->
            <div class="flex-grow pt-4">
                <div class="flex flex-wrap gap-3 mb-6">
                    @php
                        $startTime = \Carbon\Carbon::parse($event->start_time);
                        $dateLabel = $startTime->isToday() ? 'Today' : ($startTime->isTomorrow() ? 'Tomorrow' : $startTime->format('M d, Y'));
                    @endphp
                    <span class="px-4 py-1.5 bg-rose-600/10 text-rose-500 border border-rose-500/20 rounded-full text-xs font-bold uppercase tracking-widest">
                        {{ $dateLabel }}
                    </span>
                    <span class="px-4 py-1.5 bg-indigo-600/10 text-indigo-400 border border-indigo-500/20 rounded-full text-xs font-bold uppercase tracking-widest">
                        {{ $startTime->format('h:i A') }}
                    </span>
                    <span class="px-4 py-1.5 bg-slate-800 text-slate-300 border border-slate-700 rounded-full text-xs font-bold uppercase tracking-widest">
                        {{ $event->duration ?? 'N/A' }} min
                    </span>
                </div>

                <h1 class="text-5xl lg:text-7xl font-black text-white mb-6 leading-tight">
                    {{ $event->title }}
                </h1>

                <div class="flex items-center gap-4 mb-8 text-slate-400">
                    <div class="flex items-center gap-1.5">
                        <i data-lucide="tag" class="w-4 h-4 text-rose-500"></i>
                        <span class="text-sm font-medium">
                            @if(is_array($event->category))
                                {{ implode(', ', $event->category) }}
                            @else
                                {{ $event->category }}
                            @endif
                        </span>
                    </div>
                    <div class="w-1.5 h-1.5 bg-slate-700 rounded-full"></div>
                    <div class="flex items-center gap-1.5">
                        <i data-lucide="map-pin" class="w-4 h-4 text-rose-500"></i>
                        <span class="text-sm font-medium">{{ $event->venue ?? 'Main Venue' }}</span>
                    </div>
                </div>

                <div class="bg-slate-900/40 backdrop-blur-md border border-slate-800/50 rounded-3xl p-8 mb-10">
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                        <i data-lucide="align-left" class="w-5 h-5 text-rose-500"></i>
                        Event Description
                    </h3>
                    <p class="text-slate-400 leading-relaxed text-lg">
                        {{ $event->description ?? 'No description available for this event.' }}
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-6">
                    <a href="{{ route('events') }}" 
                       class="flex-1 flex items-center justify-center gap-3 py-4 px-8 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-2xl border border-slate-700 transition-all active:scale-95">
                        <i data-lucide="arrow-left" class="w-5 h-5"></i>
                        Back to List
                    </a>
                    <button onclick="openBooking()" class="flex-[2] group relative">
                        <div class="absolute -inset-1 bg-gradient-to-r from-rose-600 to-rose-500 rounded-2xl blur opacity-30 group-hover:opacity-60 transition duration-1000 group-hover:duration-200"></div>
                        <div class="relative flex items-center justify-center gap-3 py-4 px-8 bg-rose-600 hover:bg-rose-500 text-white font-black text-xl rounded-2xl transition-all active:scale-95 shadow-xl shadow-rose-900/20">
                            <i data-lucide="ticket" class="w-6 h-6"></i>
                            Book Tickets Now
                        </div>
                    </button>
                </div>
            </div>

        </div>

        <!-- Additional Info Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-16">
            <!-- Location Card -->
            <div class="bg-slate-900/40 backdrop-blur-sm border border-slate-800/50 p-5 rounded-2xl flex items-center gap-5 transition-all hover:border-rose-500/30 group">
                <div class="w-12 h-12 flex-shrink-0 bg-rose-600/10 rounded-xl flex items-center justify-center border border-rose-500/20 group-hover:bg-rose-600 group-hover:text-white transition-all duration-500">
                    <i data-lucide="map-pin" class="w-6 h-6 text-rose-500 group-hover:text-white"></i>
                </div>
                <div>
                    <h4 class="text-white font-bold text-base mb-0.5 tracking-tight">Venue</h4>
                    <p class="text-slate-500 text-[13px] leading-snug line-clamp-2">{{ $theatreDetails['location'] }}</p>
                </div>
            </div>

            <!-- Screen Card -->
            <div class="bg-slate-900/40 backdrop-blur-sm border border-slate-800/50 p-5 rounded-2xl flex items-center gap-5 transition-all hover:border-indigo-500/30 group">
                <div class="w-12 h-12 flex-shrink-0 bg-indigo-600/10 rounded-xl flex items-center justify-center border border-indigo-500/20 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500">
                    <i data-lucide="tv" class="w-6 h-6 text-indigo-400 group-hover:text-white"></i>
                </div>
                <div>
                    <h4 class="text-white font-bold text-base mb-0.5 tracking-tight">Format</h4>
                    <p class="text-slate-500 text-[13px] leading-snug">{{ $theatreDetails['screen_type'] }}</p>
                </div>
            </div>

            <!-- Refund Card -->
            <div class="bg-slate-900/40 backdrop-blur-sm border border-slate-800/50 p-5 rounded-2xl flex items-center gap-5 transition-all hover:border-emerald-500/30 group">
                <div class="w-12 h-12 flex-shrink-0 bg-emerald-600/10 rounded-xl flex items-center justify-center border border-emerald-500/20 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                    <i data-lucide="shield-check" class="w-6 h-6 text-emerald-400 group-hover:text-white"></i>
                </div>
                <div>
                    <h4 class="text-white font-bold text-base mb-0.5 tracking-tight">Refund Policy</h4>
                    <p class="text-slate-500 text-[13px] leading-snug">Cancellation up to 2 hours before</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Booking Modal — Two Column Layout -->
<div id="bookingModal" class="fixed inset-0 z-[100] hidden flex flex-col bg-slate-950 overflow-hidden">

    <!-- Header -->
    <div class="flex items-center justify-between px-6 py-4 bg-slate-900/50 border-b border-slate-800 backdrop-blur-xl shrink-0">
        <div class="flex items-center gap-4">
            <button onclick="closeBooking()" class="p-2 text-slate-400 hover:text-white transition-colors">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
            <div>
                <h2 class="text-white font-black text-lg leading-none mb-1">{{ $event->title }}</h2>
                <p class="text-[10px] text-slate-500 uppercase font-black tracking-widest">{{ \Carbon\Carbon::parse($event->start_time)->format('d M | h:i A') }}</p>
            </div>
        </div>
        <span id="syncStatus" class="flex items-center gap-2 text-[10px] font-bold text-emerald-500 uppercase bg-emerald-500/10 px-3 py-1 rounded-full border border-emerald-500/20">
            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
            Live Sync Active
        </span>
    </div>

    <!-- Two-Column Body -->
    <div class="flex flex-grow overflow-hidden">

        <!-- LEFT: Attendee Details Sidebar -->
        <div class="w-[360px] shrink-0 flex flex-col border-r border-slate-800 bg-slate-900/30">
            <!-- Sidebar Header -->
            <div class="px-6 py-4 border-b border-slate-800">
                <h3 class="text-white font-black text-sm uppercase tracking-widest flex items-center gap-2">
                    <i data-lucide="users" class="w-4 h-4 text-rose-500"></i>
                    Attendee Details
                </h3>
                <p class="text-[10px] text-slate-500 mt-1">One entry required per selected seat.</p>
            </div>

            <!-- Scrollable Cards -->
            <div id="attendeesContainer" class="flex-grow overflow-y-auto p-5 flex flex-col gap-4">
                <div class="py-12 text-center">
                    <div class="w-12 h-12 bg-slate-800/50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-700/50">
                        <i data-lucide="armchair" class="w-5 h-5 text-slate-600"></i>
                    </div>
                    <p class="text-slate-600 text-xs italic">Select seats from the map to add attendee details.</p>
                </div>
            </div>

            <!-- Sidebar Footer: Summary + Confirm -->
            <div class="p-5 border-t border-slate-800 bg-slate-900/50 shrink-0">
                <div class="flex items-end justify-between mb-4">
                    <div>
                        <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest">Total Amount</p>
                        <p class="text-2xl text-white font-black leading-none mt-1">₹<span id="totalPrice">0</span></p>
                    </div>
                    <p id="selectedSeatsLabel" class="text-[10px] text-indigo-400 font-bold">0 seats</p>
                </div>
                <button onclick="confirmBooking()" id="payBtn" disabled
                    class="w-full py-3.5 bg-rose-600 disabled:bg-slate-800 disabled:text-slate-600 text-white font-black rounded-2xl transition-all active:scale-95 flex items-center justify-center gap-2">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    Confirm Booking
                </button>
            </div>
        </div>

        <!-- RIGHT: Seating Map -->
        <div class="flex-grow overflow-auto p-8 flex flex-col items-center bg-slate-950/40">
            <!-- Screen -->
            <div class="w-full max-w-3xl flex flex-col items-center mb-14">
                <div class="w-[80%] h-2 bg-slate-800 rounded-full shadow-[0_15px_40px_rgba(225,29,72,0.3)] mb-4"></div>
                <p class="text-[10px] text-slate-600 uppercase font-black tracking-[0.5em]">Stage This Way</p>
                <div class="w-full h-20 screen-glow -mt-4"></div>
            </div>

            <!-- Grid -->
            <div class="relative" id="seatingContainer">
                <div id="rowLabelsLeft" class="absolute -left-8 top-0 flex flex-col justify-between h-full py-1 text-[10px] font-bold text-slate-600"></div>
                <div id="seatingGrid" class="grid gap-2"></div>
                <div id="rowLabelsRight" class="absolute -right-8 top-0 flex flex-col justify-between h-full py-1 text-[10px] font-bold text-slate-600"></div>
            </div>

            <!-- Legend -->
            <div class="mt-16 flex flex-wrap justify-center gap-6 px-6 py-3 bg-slate-900/30 rounded-2xl border border-slate-800">
                <div class="flex items-center gap-2"><div class="w-3.5 h-3.5 rounded-full bg-emerald-500 ring-2 ring-emerald-500/30"></div><span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Selected</span></div>
                <div class="flex items-center gap-2"><div class="w-3.5 h-3.5 rounded bg-red-700 opacity-70"></div><span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Booked</span></div>
                <div class="w-px h-3.5 bg-slate-800"></div>
                <div class="flex items-center gap-2"><div class="w-3.5 h-3.5 rounded bg-indigo-600"></div><span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Standard ₹{{ $event->price_normal }}</span></div>
                <div class="flex items-center gap-2"><div class="w-3.5 h-3.5 rounded bg-pink-500"></div><span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Premium ₹{{ $event->price_premium }}</span></div>
                <div class="flex items-center gap-2"><div class="w-3.5 h-3.5 rounded bg-yellow-500"></div><span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">VIP ₹{{ $event->price_vip }}</span></div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    const EVENT_ID = "{{ $event->id }}";
    let selectedSeats = [];
    let pollInterval = null;
    let currentLayout = null;

    const PRICES = {
        standard: {{ $event->price_normal }},
        premium: {{ $event->price_premium }},
        vip: {{ $event->price_vip }}
    };

    function openBooking() {
        $('#bookingModal').removeClass('hidden').addClass('flex');
        $('body').addClass('overflow-hidden');
        startPolling();
    }

    function closeBooking() {
        $('#bookingModal').addClass('hidden').removeClass('flex');
        $('body').removeClass('overflow-hidden');
        stopPolling();
        selectedSeats = [];
        updateFooter();
    }

    function startPolling() {
        fetchSeats();
        pollInterval = setInterval(fetchSeats, 1000); 
    }

    function stopPolling() {
        if(pollInterval) clearInterval(pollInterval);
    }

    function fetchSeats() {
        $.get(`/event/${EVENT_ID}/seats`, function(data) {
            if(data.status) {
                renderGrid(data.layout);
            }
        });
    }

    function renderGrid(layoutData) {
        const grid = $('#seatingGrid');
        const rowLeft = $('#rowLabelsLeft');
        const rowRight = $('#rowLabelsRight');
        
        if (JSON.stringify(layoutData) === JSON.stringify(currentLayout)) return;
        currentLayout = layoutData;

        grid.html('');
        rowLeft.html('');
        rowRight.html('');

        const { rows, columns, layout } = layoutData;
        grid.css('grid-template-columns', `repeat(${columns}, minmax(0, 1fr))`);

        for(let r = 0; r < rows; r++) {
            const label = `<div class="h-6 flex items-center justify-center">${String.fromCharCode(65 + r)}</div>`;
            rowLeft.append(label);
            rowRight.append(label);

            for(let c = 0; c < columns; c++) {
                const seat = layout[r][c];
                const isSelected = selectedSeats.some(s => s.id === seat.id);
                
                const seatEl = $(`
                    <div class="seat ${seat.type} ${seat.status} ${isSelected ? 'selected' : ''} w-6 h-6 rounded-md flex items-center justify-center text-[6px] font-bold text-white/40" 
                          title="${seat.id} (${seat.type})"
                          data-id="${seat.id}">
                        ${seat.type !== 'empty' ? seat.id : ''}
                    </div>
                `);

                if(seat.status === 'available' && seat.type !== 'empty') {
                    seatEl.on('click', () => toggleSeat(seat));
                }

                grid.append(seatEl);
            }
        }
    }

    function toggleSeat(seat) {
        const index = selectedSeats.findIndex(s => s.id === seat.id);
        if(index > -1) {
            selectedSeats.splice(index, 1);
        } else {
            selectedSeats.push(seat);
        }
        updateFooter();
        const savedLayout = currentLayout;
        currentLayout = null;
        renderGrid(savedLayout);
    }

    function updateFooter() {
        const label = $('#selectedSeatsLabel');
        const total = $('#totalPrice');
        const btn = $('#payBtn');
        const container = $('#attendeesContainer');

        if(selectedSeats.length === 0) {
            label.text('0 seats');
            total.text('0');
            btn.prop('disabled', true);
            container.html(`
                <div class="py-12 text-center">
                    <div class="w-12 h-12 bg-slate-800/50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-700/50">
                        <i data-lucide="armchair" class="w-5 h-5 text-slate-600"></i>
                    </div>
                    <p class="text-slate-600 text-xs italic">Select seats from the map to add attendee details.</p>
                </div>
            `);
            lucide.createIcons();
        } else {
            const cost = selectedSeats.reduce((sum, s) => sum + PRICES[s.type], 0);
            total.text(cost.toLocaleString());
            label.text(`${selectedSeats.length} seat(s) — ₹${cost.toLocaleString()}`);
            btn.prop('disabled', false);

            const currentData = {};
            $('.attendee-card').each(function() {
                const seatId = $(this).data('seat');
                currentData[seatId] = {
                    name: $(this).find('.at-name').val(),
                    email: $(this).find('.at-email').val(),
                    phone: $(this).find('.at-phone').val()
                };
            });

            container.html('');
            selectedSeats.forEach(seat => {
                const data = currentData[seat.id] || {
                    name: (seat.id === selectedSeats[0].id ? "{{ session('user_name') }}" : ''),
                    email: (seat.id === selectedSeats[0].id ? "{{ session('user_email') }}" : ''),
                    phone: ''
                };

                container.append(`
                    <div class="attendee-card bg-slate-900/50 border border-slate-800 p-4 rounded-2xl flex flex-col gap-3" data-seat="${seat.id}">
                        <div class="flex items-center justify-between border-b border-slate-800 pb-2 mb-1">
                            <span class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Seat ${seat.id}</span>
                            <span class="text-[8px] px-2 py-0.5 bg-indigo-500/10 text-indigo-400 rounded-full border border-indigo-500/20 uppercase font-bold">${seat.type}</span>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <input type="text" class="at-name bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:border-indigo-500 outline-none transition-all" value="${data.name}" placeholder="Full Name">
                            <input type="email" class="at-email bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:border-indigo-500 outline-none transition-all" value="${data.email}" placeholder="Email Address">
                            <input type="tel" class="at-phone bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:border-indigo-500 outline-none transition-all" value="${data.phone}" placeholder="Mobile Number">
                        </div>
                    </div>
                `);
            });
            lucide.createIcons();
        }
    }

    function confirmBooking() {
        const attendees = [];
        let allFilled = true;

        $('.attendee-card').each(function() {
            const seatId = $(this).data('seat');
            const name = $(this).find('.at-name').val().trim();
            const email = $(this).find('.at-email').val().trim();
            const phone = $(this).find('.at-phone').val().trim();

            if(!name || !email || !phone) allFilled = false;
            
            attendees.push({
                seat: seatId,
                name: name,
                email: email,
                phone: phone
            });
        });

        if(!allFilled) {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Info',
                text: 'Please provide Name, Email, and Phone for every person in your group.',
                background: '#0f172a',
                color: '#f8fafc',
                confirmButtonColor: '#e11d48'
            });
            return;
        }

        const btn = $('#payBtn');
        const originalText = btn.html();
        btn.prop('disabled', true).html('<i data-lucide="loader-2" class="w-5 h-5 animate-spin"></i> Processing...');
        lucide.createIcons();

        $.ajax({
            url: `/event/${EVENT_ID}/payment/init`,
            type: 'POST',
            data: {
                seats: selectedSeats.map(s => s.id),
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(initResponse) {
                if(initResponse.status) {
                    var options = {
                        "key": initResponse.key,
                        "amount": initResponse.amount,
                        "currency": "INR",
                        "name": "MovieTicket",
                        "description": "Event Ticket Booking",
                        "order_id": initResponse.order_id,
                        "handler": function (response){
                            $.ajax({
                                url: `/event/${EVENT_ID}/book`,
                                type: 'POST',
                                data: {
                                    seats: selectedSeats.map(s => s.id),
                                    attendees: attendees,
                                    razorpay_payment_id: response.razorpay_payment_id,
                                    razorpay_order_id: response.razorpay_order_id,
                                    razorpay_signature: response.razorpay_signature,
                                    _token: $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(bookResponse) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Booking Confirmed!',
                                        text: bookResponse.message,
                                        background: '#0f172a',
                                        color: '#f8fafc',
                                        confirmButtonColor: '#e11d48'
                                    }).then(() => {
                                        window.location.href = "{{ route('bookings') }}";
                                    });
                                },
                                error: function(xhr) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Booking Failed',
                                        text: xhr.responseJSON.message || 'Something went wrong.',
                                        background: '#0f172a',
                                        color: '#f8fafc',
                                        confirmButtonColor: '#e11d48'
                                    });
                                    btn.prop('disabled', false).html(originalText);
                                    lucide.createIcons();
                                }
                            });
                        },
                        "prefill": {
                            "name": attendees[0].name,
                            "email": attendees[0].email,
                            "contact": attendees[0].phone
                        },
                        "theme": {
                            "color": "#e11d48"
                        },
                        "modal": {
                            "ondismiss": function() {
                                btn.prop('disabled', false).html(originalText);
                                lucide.createIcons();
                            }
                        }
                    };
                    var rzp1 = new Razorpay(options);
                    rzp1.on('payment.failed', function (response){
                        Swal.fire({
                            icon: 'error',
                            title: 'Payment Failed',
                            text: response.error.description,
                            background: '#0f172a',
                            color: '#f8fafc',
                            confirmButtonColor: '#e11d48'
                        });
                        btn.prop('disabled', false).html(originalText);
                        lucide.createIcons();
                    });
                    rzp1.open();
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Payment Initialization Failed',
                    text: xhr.responseJSON ? xhr.responseJSON.message : 'Something went wrong.',
                    background: '#0f172a',
                    color: '#f8fafc',
                    confirmButtonColor: '#e11d48'
                });
                btn.prop('disabled', false).html(originalText);
                lucide.createIcons();
            }
        });
    }
</script>
@endsection
