@extends('admin.layouts.app')

@section('title', 'Add Movie')
@section('page_title', 'Add New Movie')

@section('head_scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection

@section('styles')
    .form-input {
        background: rgba(30, 41, 59, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: white;
        transition: all 0.3s ease;
    }

    .form-input:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
        outline: none;
    }

    /* Seating Builder Styles */
    .seat { transition: all 0.2s ease; cursor: pointer; }
    .seat:hover { transform: scale(1.1); }
    .seat.standard { background-color: #6366f1; }
    .seat.premium { background-color: #ec4899; }
    .seat.vip { background-color: #eab308; }
    .seat.blocked { background-color: #ef4444; opacity: 0.5; }
    .seat.empty { background-color: transparent; border: 1px dashed rgba(255,255,255,0.2); }
    .seat-grid { display: grid; gap: 0.5rem; }
    .screen-curve {
        background: linear-gradient(to bottom, rgba(255,255,255,0.8), rgba(255,255,255,0.1));
        border-radius: 50% 50% 0 0 / 100% 100% 0 0;
        box-shadow: 0 10px 30px -5px rgba(255, 255, 255, 0.3);
    }

    /* Custom Select2 Dark Theme styling */
    .select2-container--default .select2-selection--multiple {
        background-color: rgba(30, 41, 59, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 0.75rem; /* rounded-xl to match form-input */
        min-height: 48px;
        padding: 4px;
    }
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #6366f1;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #6366f1;
        border: none;
        color: white;
        border-radius: 0.5rem;
        padding: 4px 10px;
        margin-top: 5px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: rgba(255, 255, 255, 0.8);
        margin-right: 8px;
        border-right: none;
        font-weight: bold;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: white;
        background: none;
    }
    .select2-dropdown {
        background-color: #1e293b;
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: white;
        border-radius: 0.75rem;
        overflow: hidden;
    }
    .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
        background-color: #6366f1;
        color: white;
    }
    .select2-container--default .select2-results__option--selected {
        background-color: #334155;
    }
    .select2-search--inline .select2-search__field {
        color: white;
        margin-top: 8px;
    }
@endsection

@section('content')
<div class="p-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-white">Add New Movie</h1>
                <p class="text-slate-400">Enter movie details to list it in your theatre.</p>
            </div>
            <a href="/admin/dashboard" class="glass px-4 py-2 rounded-xl text-sm hover:bg-slate-800 transition-all">
                <i class="fa-solid fa-arrow-left mr-2"></i> Back to Dashboard
            </a>
        </div>

        <!-- Form Section -->
        <div class="glass rounded-3xl p-8">
            <form id="addMovieForm" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Movie Title -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-slate-500 uppercase mb-2">Movie Title</label>
                    <input type="text" name="title" class="form-input w-full px-4 py-3 rounded-xl" placeholder="e.g. Inception">
                </div>

                <!-- Category (Multi-select) -->
                <div>
                    <label class="block text-xs font-medium text-slate-500 uppercase mb-2">Category</label>
                    <select name="category[]" multiple class="select2-multiple w-full">
                        <option value="Bollywood">Bollywood</option>
                        <option value="Hollywood">Hollywood</option>
                        <option value="Tollywood">Tollywood</option>
                        <option value="Anime">Anime</option>
                        <option value="Regional">Regional</option>
                    </select>
                </div>

                <!-- Genre (Multi-select) -->
                <div>
                    <label class="block text-xs font-medium text-slate-500 uppercase mb-2">Genre</label>
                    <select name="genre[]" multiple class="select2-multiple w-full">
                        <option value="Action">Action</option>
                        <option value="Comedy">Comedy</option>
                        <option value="Drama">Drama</option>
                        <option value="Sci-Fi">Sci-Fi</option>
                        <option value="Horror">Horror</option>
                        <option value="Romance">Romance</option>
                        <option value="Thriller">Thriller</option>
                    </select>
                </div>

                <!-- Show Timings Section -->
                <div class="sm:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 p-4 rounded-xl border border-slate-800 bg-slate-900/30">
                    <!-- Start Date & Time -->
                    <div>
                        <label class="block text-xs font-medium text-slate-500 uppercase mb-2">Start Date & Time</label>
                        <input type="datetime-local" name="start_time" class="form-input w-full px-4 py-3 rounded-xl">
                    </div>

                    <!-- End Date & Time -->
                    <div>
                        <label class="block text-xs font-medium text-slate-500 uppercase mb-2">End Date & Time</label>
                        <input type="datetime-local" name="end_time" class="form-input w-full px-4 py-3 rounded-xl">
                    </div>
                </div>

                <!-- Duration -->
                <div>
                    <label class="block text-xs font-medium text-slate-500 uppercase mb-2">Duration (mins)</label>
                    <input type="number" name="duration" class="form-input w-full px-4 py-3 rounded-xl" placeholder="e.g. 148">
                </div>

                <!-- Pricing Section -->
                <div class="sm:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6 p-4 rounded-xl border border-slate-800 bg-slate-900/30">
                    <div>
                        <label class="block text-xs font-medium text-slate-500 uppercase mb-2">Normal Ticket Price (₹)</label>
                        <input type="number" name="price_normal" class="form-input w-full px-4 py-3 rounded-xl" placeholder="e.g. 150">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-500 uppercase mb-2">Premium Ticket Price (₹)</label>
                        <input type="number" name="price_premium" class="form-input w-full px-4 py-3 rounded-xl" placeholder="e.g. 250">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-500 uppercase mb-2">VIP Ticket Price (₹)</label>
                        <input type="number" name="price_vip" class="form-input w-full px-4 py-3 rounded-xl" placeholder="e.g. 500">
                    </div>
                </div>

                <!-- Release Date -->
                <div>
                    <label class="block text-xs font-medium text-slate-500 uppercase mb-2">Release Date</label>
                    <input type="date" name="release_date" class="form-input w-full px-4 py-3 rounded-xl">
                </div>

                <!-- Language -->
                <div>
                    <label class="block text-xs font-medium text-slate-500 uppercase mb-2">Language</label>
                    <input type="text" name="language" class="form-input w-full px-4 py-3 rounded-xl" placeholder="Hindi, English">
                </div>

                <!-- Description -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-slate-500 uppercase mb-2">Description</label>
                    <textarea name="description" rows="4" class="form-input w-full px-4 py-3 rounded-xl" placeholder="Write a short description of the movie..."></textarea>
                </div>

                <!-- Seating Arrangement Section -->
                <div class="sm:col-span-2 space-y-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-white">Seating Arrangement</h3>
                        <p class="text-xs text-slate-400">Tweak the layout for this specific movie. Default loaded from Master Layout.</p>
                    </div>
                    
                    <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
                        <!-- Tools -->
                        <div class="xl:col-span-1 space-y-4">
                            <div class="p-4 rounded-xl border border-slate-800 bg-slate-900/50">
                                <label class="block text-xs font-medium text-slate-500 uppercase mb-3">Brush Tool</label>
                                <div class="grid grid-cols-1 gap-2">
                                    <button type="button" onclick="setBrush('standard')" id="btn-standard" class="flex items-center p-2 rounded-lg border border-indigo-500 bg-indigo-500/20 text-white text-xs">
                                        <div class="w-4 h-4 rounded bg-indigo-500 mr-2"></div> Standard
                                    </button>
                                    <button type="button" onclick="setBrush('premium')" id="btn-premium" class="flex items-center p-2 rounded-lg border border-slate-700 hover:bg-slate-800 text-slate-300 text-xs">
                                        <div class="w-4 h-4 rounded bg-pink-500 mr-2"></div> Premium
                                    </button>
                                    <button type="button" onclick="setBrush('vip')" id="btn-vip" class="flex items-center p-2 rounded-lg border border-slate-700 hover:bg-slate-800 text-slate-300 text-xs">
                                        <div class="w-4 h-4 rounded bg-yellow-500 mr-2"></div> VIP
                                    </button>
                                    <button type="button" onclick="setBrush('empty')" id="btn-empty" class="flex items-center p-2 rounded-lg border border-slate-700 hover:bg-slate-800 text-slate-300 text-xs">
                                        <div class="w-4 h-4 rounded border border-dashed border-slate-500 mr-2"></div> Aisle
                                    </button>
                                    <button type="button" onclick="setBrush('blocked')" id="btn-blocked" class="flex items-center p-2 rounded-lg border border-slate-700 hover:bg-slate-800 text-slate-300 text-xs">
                                        <div class="w-4 h-4 rounded bg-red-500 opacity-50 mr-2"></div> Blocked
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Grid -->
                        <div class="xl:col-span-3 p-6 rounded-xl border border-slate-800 bg-slate-900/50 flex flex-col items-center overflow-x-auto">
                            <div class="w-48 h-4 mb-8 relative flex items-center justify-center">
                                <div class="absolute inset-0 screen-curve"></div>
                                <span class="relative text-[10px] text-white/50 font-bold tracking-widest uppercase">Screen</span>
                            </div>
                            
                            <div class="flex">
                                <div class="flex flex-col justify-between mr-2 py-1" id="row-labels"></div>
                                <div class="seat-grid" id="theatre-grid"></div>
                                <div class="flex flex-col justify-between ml-2 py-1" id="row-labels-right"></div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="seating_layout" id="seating_layout_input">
                </div>

                <!-- Movie Poster -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-slate-500 uppercase mb-2">Movie Poster</label>
                    <input type="file" name="poster" class="form-input w-full px-4 py-3 rounded-xl file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500">
                </div>

                <!-- Submit Button -->
                <div class="sm:col-span-2 pt-4">
                    <button type="submit" id="submitBtn" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3 px-8 rounded-xl transition-all shadow-lg shadow-indigo-500/20">
                        Add Movie
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Setup CSRF Token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Initialize Select2 for multiple selections
            $('.select2-multiple').select2({
                placeholder: "Click to select options...",
                allowClear: true,
                width: '100%'
            });

            // Seating Builder Logic
            let ROWS = 10;
            let COLS = 20;
            let currentBrush = 'standard';
            let layout = @json($masterLayout['layout'] ?? []);
            
            const gridEl = document.getElementById('theatre-grid');
            const rowLabelsEl = document.getElementById('row-labels');
            const rowLabelsRightEl = document.getElementById('row-labels-right');

            function initSeating() {
                const master = @json($masterLayout ?? null);
                if (master) {
                    ROWS = master.rows;
                    COLS = master.columns;
                    layout = master.layout;
                    renderGrid();
                }
            }

            window.setBrush = function(type) {
                currentBrush = type;
                ['standard', 'premium', 'vip', 'empty', 'blocked'].forEach(btn => {
                    const el = document.getElementById(`btn-${btn}`);
                    if (el) {
                        el.className = `flex items-center p-2 rounded-lg border transition-all text-xs ${
                            btn === type ? 'border-indigo-500 bg-indigo-500/20 text-white' : 'border-slate-700 hover:bg-slate-800 text-slate-300'
                        }`;
                    }
                });
            }

            function renderGrid() {
                gridEl.innerHTML = '';
                rowLabelsEl.innerHTML = '';
                rowLabelsRightEl.innerHTML = '';
                gridEl.style.gridTemplateColumns = `repeat(${COLS}, minmax(0, 1fr))`;

                for(let r = 0; r < ROWS; r++) {
                    const label = document.createElement('div');
                    label.className = 'w-4 h-4 flex items-center justify-center text-[10px] font-bold text-slate-500';
                    label.innerText = String.fromCharCode(65 + r);
                    rowLabelsEl.appendChild(label.cloneNode(true));
                    rowLabelsRightEl.appendChild(label);

                    for(let c = 0; c < COLS; c++) {
                        const seatData = layout[r][c];
                        const seat = document.createElement('div');
                        seat.className = `seat ${seatData.type} w-4 h-4 rounded-sm flex items-center justify-center text-[6px] font-bold text-white/50`;
                        seat.innerText = seatData.type !== 'empty' ? seatData.id : '';

                        const updateSeatVisual = () => {
                            seat.className = `seat ${seatData.type} w-4 h-4 rounded-sm flex items-center justify-center text-[6px] font-bold text-white/50`;
                            seat.innerText = seatData.type !== 'empty' ? seatData.id : '';
                            // Sync with hidden input
                            document.getElementById('seating_layout_input').value = JSON.stringify({
                                rows: ROWS,
                                columns: COLS,
                                layout: layout
                            });
                        };

                        seat.addEventListener('mousedown', (e) => { seatData.type = currentBrush; updateSeatVisual(); });
                        seat.addEventListener('mouseenter', (e) => { if(e.buttons === 1) { seatData.type = currentBrush; updateSeatVisual(); } });
                        gridEl.appendChild(seat);
                    }
                }
                document.getElementById('seating_layout_input').value = JSON.stringify({ rows: ROWS, columns: COLS, layout: layout });
            }

            initSeating();

            // Handle Form Submission
            $('#addMovieForm').on('submit', function(e) {
                e.preventDefault();
                
                let formData = new FormData(this);
                let $btn = $('#submitBtn');
                let originalText = $btn.text();
                
                $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin mr-2"></i> Adding...');

                $.ajax({
                    url: "{{ route('admin.movies.store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                background: 'rgba(15, 23, 42, 0.9)',
                                color: '#fff',
                                confirmButtonColor: '#6366f1',
                                customClass: {
                                    popup: 'border border-slate-800 rounded-3xl backdrop-blur-md'
                                }
                            });
                            $('#addMovieForm')[0].reset();
                            $('.select2-multiple').val(null).trigger('change');
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = xhr.responseJSON?.message || 'An error occurred while adding the movie.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: errorMessage,
                            background: 'rgba(15, 23, 42, 0.9)',
                            color: '#fff',
                            confirmButtonColor: '#6366f1',
                            customClass: {
                                    popup: 'border border-slate-800 rounded-3xl backdrop-blur-md'
                            }
                        });
                    },
                    complete: function() {
                        $btn.prop('disabled', false).text(originalText.trim());
                    }
                });
            });
        });
    </script>
@endsection
