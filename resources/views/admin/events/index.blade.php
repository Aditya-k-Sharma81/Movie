@extends('admin.layouts.app')

@section('title', 'All Events')
@section('page_title', 'All Events')

@section('content')
<div class="p-4 md:p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-6 md:mb-8 gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-white">All Events</h1>
                <p class="text-sm text-slate-400">Manage all events listed in your venue</p>
            </div>
            <div class="flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-3 w-full lg:w-auto">
                <!-- Filter Form -->
                <form method="GET" action="{{ route('admin.events.index') }}" class="flex flex-wrap items-center justify-between sm:justify-start gap-3 glass px-3 py-2 sm:py-1.5 rounded-xl w-full sm:w-auto">
                    <div class="flex items-center gap-2 w-full sm:w-auto justify-between sm:justify-start">
                        <label for="date" class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Date</label>
                        <input type="date" name="date" id="date" value="{{ request('date') }}" onchange="this.form.submit()" 
                            class="bg-slate-900/80 border border-slate-700/50 text-white text-xs rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block px-2 py-1.5 cursor-pointer">
                    </div>
                    <div class="hidden sm:block w-px h-5 bg-slate-700/50"></div>
                    <div class="flex items-center gap-2 w-full sm:w-auto justify-between sm:justify-start">
                        <label for="sort" class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Sort</label>
                        <select name="sort" id="sort" onchange="this.form.submit()" 
                            class="bg-slate-900/80 border border-slate-700/50 text-white text-xs rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block px-2 py-1.5 cursor-pointer">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Recently Added</option>
                            <option value="shows_asc" {{ request('sort') == 'shows_asc' ? 'selected' : '' }}>Event Time (Soonest)</option>
                            <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Event Date (Newest)</option>
                        </select>
                    </div>
                    @if(request('date') || (request('sort') && request('sort') != 'latest'))
                        <a href="{{ route('admin.events.index') }}" class="text-xs text-rose-400 hover:text-rose-300 ml-1 px-1 transition-colors" title="Clear Filters"><i class="fa-solid fa-xmark text-sm"></i></a>
                    @endif
                </form>

                <div class="flex gap-2 sm:gap-3 w-full sm:w-auto">
                    <a href="{{ route('admin.dashboard') }}"
                        class="glass px-4 py-2 rounded-xl text-sm hover:bg-slate-800 transition-all flex items-center justify-center flex-1 sm:flex-none">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <a href="{{ route('admin.events.add') }}"
                        class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-2 px-4 rounded-xl transition-all shadow-lg shadow-indigo-500/20 flex items-center justify-center flex-1 sm:flex-none whitespace-nowrap text-sm sm:text-base">
                        <i class="fa-solid fa-plus mr-2"></i> Add Event
                    </a>
                </div>
            </div>
        </div>

        <!-- Events Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 sm:gap-6">

            @forelse($events as $event)
            @php
                $categories = is_array($event->category) ? $event->category : (is_string($event->category) ? json_decode($event->category, true) ?? [$event->category] : []);
                $startTime = \Carbon\Carbon::parse($event->start_time);
                
                $eventJson = [
                    'id' => (string) $event->id,
                    'title' => $event->title,
                    'poster' => $event->poster,
                    'category' => $categories,
                    'venue' => $event->venue,
                    'duration' => $event->duration,
                    'description' => $event->description,
                    'event_date' => \Carbon\Carbon::parse($event->event_date)->format('d M, Y'),
                    'start_time' => $startTime->format('d M, Y h:i A'),
                    'end_time' => \Carbon\Carbon::parse($event->end_time)->format('d M, Y h:i A'),
                    'price_normal' => $event->price_normal,
                    'price_premium' => $event->price_premium,
                    'price_vip' => $event->price_vip,
                ];
            @endphp
            <div class="group cursor-pointer" onclick="showEventDetails({{ json_encode($eventJson) }})">
                <div class="relative aspect-[2/3] rounded-3xl overflow-hidden mb-3 border border-slate-800 transition-all group-hover:scale-105 group-hover:border-indigo-500/50 group-hover:shadow-xl group-hover:shadow-indigo-900/10">
                    <img src="{{ $event->poster ?? 'https://via.placeholder.com/400x600?text=No+Poster' }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent opacity-80"></div>
                    
                    <div class="absolute bottom-3 left-3 flex flex-col gap-1">
                        <span class="px-2 py-0.5 bg-indigo-600 text-[9px] font-bold text-white rounded uppercase tracking-wider w-fit shadow-lg shadow-indigo-900/40">
                            {{ $startTime->format('h:i A') }}
                        </span>
                    </div>
                </div>
                <h3 class="text-white font-bold text-sm truncate group-hover:text-indigo-400 transition-colors">{{ $event->title }}</h3>
                <p class="text-slate-500 text-[10px] truncate">
                    {{ implode(' • ', $categories) }}
                </p>
            </div>
            @empty
            <div class="col-span-full glass rounded-3xl p-12 flex flex-col items-center justify-center text-center border-dashed border-slate-700">
                <div class="w-20 h-20 rounded-full bg-slate-900/50 flex items-center justify-center text-slate-500 mb-4 border border-slate-800">
                    <i class="fa-solid fa-calendar-days text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-1">No Events Found</h3>
                <p class="text-sm text-slate-500">Add your first event to see it here.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

    <!-- Event Details Modal -->
    <div id="eventModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-950/90 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
        <div class="glass relative w-full max-w-2xl rounded-[2.5rem] shadow-2xl transform transition-all scale-95 opacity-0 duration-300 max-h-[90vh] overflow-y-auto" id="modalPanel">
            <button onclick="closeModal()" class="absolute top-4 right-4 z-10 w-10 h-10 rounded-full bg-slate-900/80 border border-white/10 flex items-center justify-center text-white hover:bg-white/10 transition-all">
                <i class="fa-solid fa-xmark"></i>
            </button>
            
            <div id="modalContent">
                <!-- Data injected here -->
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        function showEventDetails(event) {
            const modal = document.getElementById('eventModal');
            const panel = document.getElementById('modalPanel');
            const content = document.getElementById('modalContent');

            content.innerHTML = `
                <div class="flex flex-col md:flex-row gap-8 p-6 md:p-8">
                    <!-- Poster & Meta -->
                    <div class="w-full md:w-1/3 flex flex-col gap-4">
                        <div class="aspect-[2/3] w-2/3 mx-auto md:w-full rounded-2xl overflow-hidden shadow-2xl border border-white/10 group-hover:scale-[1.02] transition-transform duration-500">
                            <img src="${event.poster || 'https://via.placeholder.com/400x600?text=No+Poster'}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex flex-wrap justify-center md:justify-start gap-2">
                            <span class="px-2 py-0.5 bg-slate-800 text-slate-400 text-[9px] font-bold rounded uppercase border border-slate-700 tracking-tighter">${event.venue}</span>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="flex-1 flex flex-col items-center md:items-start text-center md:text-left">
                        <div class="flex flex-wrap justify-center md:justify-start gap-2 mb-3">
                            ${event.category.map(cat => `<span class="px-2 py-0.5 bg-indigo-500/10 text-indigo-400 text-[10px] font-bold rounded border border-indigo-500/20 uppercase tracking-wider">${cat}</span>`).join('')}
                        </div>
                        <h2 class="text-3xl font-black text-white mb-4 leading-tight">${event.title}</h2>
                        
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="p-3 rounded-xl bg-slate-900/50 border border-white/5">
                                <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest mb-1">Event Date</p>
                                <p class="text-xs text-slate-200 font-bold"><i class="fa-solid fa-calendar-check mr-1.5 text-indigo-400"></i>${event.event_date}</p>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-900/50 border border-white/5">
                                <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest mb-1">Duration</p>
                                <p class="text-xs text-slate-200 font-bold"><i class="fa-regular fa-clock mr-1.5 text-indigo-400"></i>${event.duration}m</p>
                            </div>
                        </div>

                        <div class="space-y-6 mb-8">
                            <div class="relative pl-6 border-l-2 border-slate-800 py-1 space-y-6">
                                <div class="relative">
                                    <div class="absolute -left-[31px] top-1 w-3 h-3 rounded-full bg-indigo-500 shadow-[0_0_10px_rgba(99,102,241,0.5)] border-2 border-slate-950"></div>
                                    <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest mb-1">Event Starts</p>
                                    <p class="text-sm text-white font-black tracking-tight">${event.start_time}</p>
                                </div>
                                <div class="relative">
                                    <div class="absolute -left-[31px] top-1 w-3 h-3 rounded-full bg-rose-500 shadow-[0_0_10px_rgba(244,63,94,0.5)] border-2 border-slate-950"></div>
                                    <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest mb-1">Event Ends</p>
                                    <p class="text-sm text-white font-black tracking-tight">${event.end_time}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-2">
                                <div class="text-center p-2 rounded-lg bg-slate-900/30 border border-white/5">
                                    <p class="text-[8px] text-slate-500 uppercase font-black mb-1">Normal</p>
                                    <p class="text-xs text-indigo-400 font-bold">₹${event.price_normal}</p>
                                </div>
                                <div class="text-center p-2 rounded-lg bg-slate-900/30 border border-white/5">
                                    <p class="text-[8px] text-slate-500 uppercase font-black mb-1">Premium</p>
                                    <p class="text-xs text-pink-400 font-bold">₹${event.price_premium}</p>
                                </div>
                                <div class="text-center p-2 rounded-lg bg-slate-900/30 border border-white/5">
                                    <p class="text-[8px] text-slate-500 uppercase font-black mb-1">VIP</p>
                                    <p class="text-xs text-amber-400 font-bold">₹${event.price_vip}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-900/50 rounded-2xl p-4 border border-white/5 mb-8">
                            <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest mb-2">Description</p>
                            <p class="text-[11px] text-slate-400 leading-relaxed font-medium">${event.description}</p>
                        </div>

                        <!-- Actions -->
                        <div class="mt-auto flex items-center gap-4">
                            <a href="/admin/events/edit/${event.id}" class="flex-1 bg-white text-slate-950 font-black py-4 px-6 rounded-2xl hover:bg-indigo-400 hover:text-white transition-all text-center uppercase tracking-widest text-[10px] shadow-xl">
                                <i class="fa-solid fa-pen-to-square mr-2"></i> Edit Event
                            </a>
                            <button onclick="deleteEvent('${event.id}')" class="w-14 h-14 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-500 hover:bg-rose-500 hover:text-white transition-all shadow-xl">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;

            modal.classList.remove('hidden');
            setTimeout(() => {
                panel.classList.remove('scale-95', 'opacity-0');
                panel.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeModal() {
            const modal = document.getElementById('eventModal');
            const panel = document.getElementById('modalPanel');
            
            panel.classList.add('scale-95', 'opacity-0');
            panel.classList.remove('scale-100', 'opacity-100');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function deleteEvent(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#4f46e5',
                confirmButtonText: 'Yes, delete it!',
                background: 'rgba(15, 23, 42, 0.95)',
                color: '#fff',
                customClass: {
                    popup: 'border border-slate-800 rounded-3xl backdrop-blur-md'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/events/delete/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: data.message,
                                background: 'rgba(15, 23, 42, 0.95)',
                                color: '#fff',
                                confirmButtonColor: '#6366f1',
                                customClass: {
                                    popup: 'border border-slate-800 rounded-3xl backdrop-blur-md'
                                }
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: data.message || 'Something went wrong.',
                                background: 'rgba(15, 23, 42, 0.95)',
                                color: '#fff',
                                confirmButtonColor: '#6366f1'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error!', 'An unexpected error occurred.', 'error');
                    });
                }
            });
        }
    </script>
@endsection