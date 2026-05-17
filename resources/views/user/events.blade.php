@extends('layouts.user')

@section('title', 'Events | MovieTicket')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-12">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
        <div>
            <h1 class="text-4xl font-black text-white mb-2 mt-2">Explore Live Events</h1>
            <p class="text-slate-400 text-sm">Discover local and national live events, concerts, and shows.</p>
        </div>
        
        <!-- Compact Search Bar -->
        <div class="relative group w-full md:w-[320px]">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i data-lucide="search" class="w-4 h-4 text-slate-500 group-focus-within:text-rose-500 transition-colors"></i>
            </div>
            <input type="text" id="eventSearch" placeholder="Search events..." 
                class="w-full pl-11 pr-4 py-2.5 bg-slate-900/40 backdrop-blur-xl border border-white/5 focus:border-rose-500/50 text-white placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-rose-500/10 rounded-2xl text-sm transition-all font-medium">
        </div>
    </div>

    <!-- Filters Section -->
    <div class="flex flex-wrap items-center gap-4 mb-12">
        <div class="flex items-center gap-2 px-4 py-2 bg-slate-900/40 backdrop-blur-xl border border-white/5 rounded-2xl">
            <i data-lucide="filter" class="w-4 h-4 text-rose-500"></i>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Filter By</span>
        </div>

        <div class="relative group">
            <select id="categoryFilter" class="pl-5 pr-10 py-3 bg-slate-900/40 backdrop-blur-xl border border-white/5 text-white focus:outline-none focus:border-rose-500/50 rounded-2xl text-sm transition-all cursor-pointer appearance-none font-semibold min-w-[160px] hover:bg-slate-800/60">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-500 group-hover:text-rose-500 transition-colors"></i>
            </div>
        </div>

        <button id="clearFilters" class="ml-auto flex items-center gap-2 px-6 py-3 bg-rose-600/10 hover:bg-rose-600 text-rose-500 hover:text-white rounded-2xl transition-all border border-rose-500/20 active:scale-95 group font-bold text-sm">
            <i data-lucide="refresh-cw" class="w-4 h-4 group-hover:rotate-180 transition-transform duration-500"></i>
            Reset Filters
        </button>
    </div>

    <!-- Dynamic Content Area -->
    <div class="relative min-h-[500px] flex flex-col">
        <!-- Event Grid -->
        <div id="allEventsGrid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-8 transition-all duration-300 {{ count($events) == 0 ? 'hidden' : '' }}">
            @include('user.partials.event_grid', ['events' => $events, 'showTime' => false])
        </div>

        <!-- Centered Empty State -->
        <div id="emptyState" class="flex-grow flex flex-col items-center justify-center py-20 text-center {{ count($events) > 0 ? 'hidden' : '' }}">
            <div class="inline-flex p-6 bg-slate-900/50 backdrop-blur-xl rounded-[2rem] mb-6 border border-white/5 shadow-2xl">
                <i data-lucide="calendar" class="w-12 h-12 text-slate-700"></i>
            </div>
            <h3 class="text-2xl font-black text-white mb-3">No Events Found</h3>
            <p class="text-slate-500 max-w-md mx-auto">We couldn't find any events matching your selection. Try a different search term or filter!</p>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            let searchTimer;

            function refreshEvents() {
                const search = $('#eventSearch').val();
                const category = $('#categoryFilter').val();

                $.ajax({
                    url: "{{ route('events') }}",
                    type: "GET",
                    data: {
                        search: search,
                        category: category,
                    },
                    dataType: "html",
                    success: function (res) {
                        // Extract content from response
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(res, 'text/html');
                        
                        const gridHtml = doc.getElementById('allEventsGrid').innerHTML;
                        const emptyClass = doc.getElementById('emptyState').classList.contains('hidden');
                        
                        $('#allEventsGrid').html(gridHtml);
                        
                        if (emptyClass) {
                            $('#allEventsGrid').removeClass('hidden');
                            $('#emptyState').addClass('hidden');
                        } else {
                            $('#allEventsGrid').addClass('hidden');
                            $('#emptyState').removeClass('hidden');
                        }

                        lucide.createIcons();
                    },
                    error: function (err) {
                        console.error("Events catalog sync failed:", err);
                    }
                });
            }

            // Event Listeners for Filters
            $('#categoryFilter').on('change', function() {
                refreshEvents();
            });

            // Search with Debounce (Wait 500ms after last character)
            $('#eventSearch').on('input', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(refreshEvents, 500);
            });

            // Clear Filters
            $('#clearFilters').on('click', function() {
                $('#eventSearch').val('');
                $('#categoryFilter').val('');
                refreshEvents();
            });

            /**
             * Immediate Removal Logic (Check every second)
             */
            function monitorEventTimes() {
                const now = new Date();
                let visibleCount = $('.event-card').length;

                $('.event-card').each(function () {
                    const startTimeStr = $(this).data('start-time');
                    if (startTimeStr) {
                        const startTime = new Date(startTimeStr);
                        if (startTime <= now) {
                            $(this).fadeOut(500, function () {
                                $(this).remove();
                                visibleCount--;
                                if (visibleCount === 0) {
                                    $('#allEventsGrid').addClass('hidden');
                                    $('#emptyState').removeClass('hidden');
                                }
                            });
                        }
                    }
                });
            }

            // Precise removal every 1 second
            setInterval(monitorEventTimes, 1000);
        });
    </script>
@endsection
