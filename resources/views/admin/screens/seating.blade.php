<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seating Arrangement Builder | Admin</title>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Seat Types */
        .seat { transition: all 0.2s ease; }
        .seat:hover { transform: scale(1.1); }
        .seat.standard { background-color: #6366f1; /* Indigo */ }
        .seat.premium { background-color: #ec4899; /* Pink */ }
        .seat.vip { background-color: #eab308; /* Yellow/Gold */ }
        .seat.blocked { background-color: #ef4444; /* Red */ opacity: 0.5; }
        .seat.empty { background-color: transparent; border: 1px dashed rgba(255,255,255,0.2); }
        
        /* Seat Grid */
        .seat-grid {
            display: grid;
            gap: 0.5rem;
        }

        /* Screen glow */
        .screen-curve {
            background: linear-gradient(to bottom, rgba(255,255,255,0.8), rgba(255,255,255,0.1));
            border-radius: 50% 50% 0 0 / 100% 100% 0 0;
            box-shadow: 0 10px 30px -5px rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body class="h-full text-slate-200">
    <div class="flex h-full">
        <!-- Sidebar -->
        <aside class="w-64 glass border-r border-slate-800 flex flex-col hidden md:flex">
            <div class="p-6">
                <h1 class="text-2xl font-extrabold tracking-tight text-white">
                    Movie<span class="text-indigo-500">Ticket</span>
                </h1>
            </div>
            
            <nav class="flex-1 mt-4 px-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-slate-400 hover:text-white hover:bg-slate-800/50 rounded-lg transition-all">
                    <i class="fa-solid fa-chart-pie w-5 h-5 mr-3"></i> Dashboard
                </a>
                <a href="{{ route('admin.movies.index') }}" class="flex items-center px-4 py-3 text-slate-400 hover:text-white hover:bg-slate-800/50 rounded-lg transition-all">
                    <i class="fa-solid fa-film w-5 h-5 mr-3"></i> All Movies
                </a>
                <a href="{{ route('admin.movies.add') }}" class="flex items-center px-4 py-3 text-slate-400 hover:text-white hover:bg-slate-800/50 rounded-lg transition-all">
                    <i class="fa-solid fa-plus w-5 h-5 mr-3"></i> Add Movie
                </a>
                <a href="/admin/screens/seating" class="flex items-center px-4 py-3 text-white bg-indigo-500/10 border border-indigo-500/20 rounded-lg transition-all">
                    <i class="fa-solid fa-chair w-5 h-5 mr-3 text-indigo-400"></i> Seating Map
                </a>
                <a href="{{ route('admin.profile') }}" class="flex items-center px-4 py-3 text-slate-400 hover:text-white hover:bg-slate-800/50 rounded-lg transition-all">
                    <i class="fa-solid fa-user w-5 h-5 mr-3"></i> My Profile
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col overflow-hidden bg-slate-950">
            <!-- Header -->
            <header class="h-16 glass border-b border-slate-800 flex items-center justify-between px-8 z-10">
                <h2 class="text-xl font-semibold text-white">Seating Arrangement Builder</h2>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">
                        <i class="fa-solid fa-arrow-left mr-1"></i> Back to Dashboard
                    </a>
                </div>
            </header>

            <!-- Builder Area -->
            <div class="flex-1 overflow-y-auto p-8 flex flex-col xl:flex-row gap-8">
                
                <!-- Tools Panel -->
                <div class="w-full xl:w-80 flex-shrink-0 space-y-6">
                    <div class="glass p-6 rounded-2xl border border-slate-800">
                        <h3 class="text-lg font-bold text-white mb-4">Seat Types</h3>
                        <p class="text-sm text-slate-400 mb-6">Select a type, then click on the seats in the grid to change them.</p>
                        
                        <div class="space-y-3">
                            <button onclick="setBrush('standard')" id="btn-standard" class="w-full flex items-center p-3 rounded-xl border border-indigo-500 bg-indigo-500/20 text-white transition-all">
                                <div class="w-6 h-6 rounded bg-indigo-500 mr-3"></div>
                                <span class="font-semibold text-sm">Standard Seat</span>
                            </button>
                            <button onclick="setBrush('premium')" id="btn-premium" class="w-full flex items-center p-3 rounded-xl border border-slate-700 hover:bg-slate-800 text-slate-300 transition-all">
                                <div class="w-6 h-6 rounded bg-pink-500 mr-3"></div>
                                <span class="font-semibold text-sm">Premium Seat</span>
                            </button>
                            <button onclick="setBrush('vip')" id="btn-vip" class="w-full flex items-center p-3 rounded-xl border border-slate-700 hover:bg-slate-800 text-slate-300 transition-all">
                                <div class="w-6 h-6 rounded bg-yellow-500 mr-3"></div>
                                <span class="font-semibold text-sm">VIP / Recliner</span>
                            </button>
                            <button onclick="setBrush('empty')" id="btn-empty" class="w-full flex items-center p-3 rounded-xl border border-slate-700 hover:bg-slate-800 text-slate-300 transition-all">
                                <div class="w-6 h-6 rounded border border-dashed border-slate-500 mr-3"></div>
                                <span class="font-semibold text-sm">Aisle / Empty Space</span>
                            </button>
                            <button onclick="setBrush('blocked')" id="btn-blocked" class="w-full flex items-center p-3 rounded-xl border border-slate-700 hover:bg-slate-800 text-slate-300 transition-all">
                                <div class="w-6 h-6 rounded bg-red-500 opacity-50 mr-3"></div>
                                <span class="font-semibold text-sm">Blocked / Broken</span>
                            </button>
                        </div>
                    </div>

                    <div class="glass p-6 rounded-2xl border border-slate-800">
                        <h3 class="text-lg font-bold text-white mb-4">Grid Settings</h3>
                        <div class="flex gap-4 mb-4">
                            <div class="flex-1">
                                <label class="text-xs text-slate-400 font-bold uppercase mb-1 block">Rows</label>
                                <input type="number" id="rowCount" value="10" min="1" max="26" onchange="generateGrid()" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500">
                            </div>
                            <div class="flex-1">
                                <label class="text-xs text-slate-400 font-bold uppercase mb-1 block">Columns</label>
                                <input type="number" id="colCount" value="20" min="1" max="50" onchange="generateGrid()" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500">
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-500 mb-6"><i class="fa-solid fa-info-circle"></i> Max 26 rows (A-Z) and 50 columns.</p>
                    </div>

                    <div class="glass p-6 rounded-2xl border border-slate-800">
                        <h3 class="text-lg font-bold text-white mb-4">Ticket Pricing (₹)</h3>
                        <div class="space-y-4 mb-6">
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-semibold text-indigo-400">Standard</label>
                                <input type="number" id="price-standard" value="150" class="w-24 bg-slate-900 border border-slate-700 rounded-lg px-3 py-1.5 text-white focus:outline-none focus:border-indigo-500 text-right">
                            </div>
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-semibold text-pink-400">Premium</label>
                                <input type="number" id="price-premium" value="250" class="w-24 bg-slate-900 border border-slate-700 rounded-lg px-3 py-1.5 text-white focus:outline-none focus:border-pink-500 text-right">
                            </div>
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-semibold text-yellow-400">VIP / Recliner</label>
                                <input type="number" id="price-vip" value="400" class="w-24 bg-slate-900 border border-slate-700 rounded-lg px-3 py-1.5 text-white focus:outline-none focus:border-yellow-500 text-right">
                            </div>
                        </div>

                        <button onclick="saveLayout()" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-3 rounded-xl transition-all shadow-lg shadow-emerald-500/20 flex items-center justify-center">
                            <i class="fa-solid fa-save mr-2"></i> Save Master Layout
                        </button>
                    </div>
                </div>

                <!-- Theatre Grid -->
                <div class="flex-1 glass p-8 rounded-2xl border border-slate-800 flex flex-col items-center justify-center overflow-x-auto">
                    
                    <!-- Screen -->
                    <div class="w-3/4 max-w-2xl h-12 mb-16 relative flex items-center justify-center">
                        <div class="absolute inset-0 screen-curve"></div>
                        <span class="relative text-white/50 text-sm font-bold tracking-widest uppercase">Screen</span>
                    </div>

                    <!-- Seats Container -->
                    <div class="flex">
                        <!-- Row Labels (Left) -->
                        <div class="flex flex-col justify-between mr-4 py-1" id="row-labels">
                            <!-- Injected by JS -->
                        </div>

                        <!-- The Grid -->
                        <div class="seat-grid" id="theatre-grid">
                            <!-- Seats injected by JS -->
                        </div>
                        
                        <!-- Row Labels (Right) -->
                        <div class="flex flex-col justify-between ml-4 py-1" id="row-labels-right">
                            <!-- Injected by JS -->
                        </div>
                    </div>

                </div>

            </div>
        </main>
    </div>

    <script>
        let ROWS = 10;
        let COLS = 20;
        let currentBrush = 'standard';
        let layout = [];

        // DOM Elements
        const gridEl = document.getElementById('theatre-grid');
        const rowLabelsEl = document.getElementById('row-labels');
        const rowLabelsRightEl = document.getElementById('row-labels-right');

        function generateGrid() {
            ROWS = parseInt(document.getElementById('rowCount').value) || 10;
            COLS = parseInt(document.getElementById('colCount').value) || 20;
            
            if (ROWS > 26) { ROWS = 26; document.getElementById('rowCount').value = 26; }
            if (COLS > 50) { COLS = 50; document.getElementById('colCount').value = 50; }

            layout = [];
            for(let r = 0; r < ROWS; r++) {
                let row = [];
                for(let c = 0; c < COLS; c++) {
                    row.push({
                        rowLabel: String.fromCharCode(65 + r),
                        colNum: c + 1,
                        id: `${String.fromCharCode(65 + r)}${c + 1}`,
                        type: 'standard'
                    });
                }
                layout.push(row);
            }
            renderGrid();
        }

        // Brush Logic
        function setBrush(type) {
            currentBrush = type;
            
            // Reset UI buttons
            const buttons = ['standard', 'premium', 'vip', 'empty', 'blocked'];
            buttons.forEach(btn => {
                const el = document.getElementById(`btn-${btn}`);
                el.className = `w-full flex items-center p-3 rounded-xl border transition-all ${
                    btn === type 
                    ? `border-${getBorderColor(btn)} bg-${getBgColor(btn)} text-white shadow-lg` 
                    : 'border-slate-700 hover:bg-slate-800 text-slate-300'
                }`;
            });
        }

        function getBorderColor(type) {
            switch(type) {
                case 'standard': return 'indigo-500';
                case 'premium': return 'pink-500';
                case 'vip': return 'yellow-500';
                case 'blocked': return 'red-500';
                case 'empty': return 'slate-500';
                default: return 'slate-700';
            }
        }
        function getBgColor(type) {
            switch(type) {
                case 'standard': return 'indigo-500/20';
                case 'premium': return 'pink-500/20';
                case 'vip': return 'yellow-500/20';
                case 'blocked': return 'red-500/20';
                case 'empty': return 'slate-800';
                default: return 'transparent';
            }
        }

        // Render Grid
        function renderGrid() {
            gridEl.innerHTML = '';
            rowLabelsEl.innerHTML = '';
            rowLabelsRightEl.innerHTML = '';
            gridEl.style.gridTemplateColumns = `repeat(${COLS}, minmax(0, 1fr))`;

            for(let r = 0; r < ROWS; r++) {
                // Add labels
                const label = document.createElement('div');
                label.className = 'w-6 h-6 flex items-center justify-center text-xs font-bold text-slate-500';
                label.innerText = String.fromCharCode(65 + r);
                rowLabelsEl.appendChild(label.cloneNode(true));
                rowLabelsRightEl.appendChild(label);

                for(let c = 0; c < COLS; c++) {
                    const seatData = layout[r][c];
                    
                    const seat = document.createElement('div');
                    seat.className = `seat ${seatData.type} w-6 h-6 sm:w-8 sm:h-8 rounded cursor-pointer flex items-center justify-center text-[8px] font-bold text-white/50`;
                    
                    if(seatData.type !== 'empty') {
                        seat.innerText = seatData.colNum;
                    }

                    // Helper to update seat visual
                    const updateSeatVisual = () => {
                        seat.className = `seat ${seatData.type} w-6 h-6 sm:w-8 sm:h-8 rounded cursor-pointer flex items-center justify-center text-[8px] font-bold text-white/50`;
                        if(seatData.type !== 'empty') {
                            seat.innerText = seatData.colNum;
                        } else {
                            seat.innerText = '';
                        }
                    };

                    // Click event to paint
                    seat.addEventListener('mousedown', (e) => {
                        seatData.type = currentBrush;
                        updateSeatVisual();
                    });

                    // Drag event to paint
                    seat.addEventListener('mouseenter', (e) => {
                        if(e.buttons === 1) { // Left mouse button down
                            seatData.type = currentBrush;
                            updateSeatVisual();
                        }
                    });

                    gridEl.appendChild(seat);
                }
            }
        }

        // Save Layout
        function saveLayout() {
            const pricing = {
                standard: parseFloat(document.getElementById('price-standard').value) || 0,
                premium: parseFloat(document.getElementById('price-premium').value) || 0,
                vip: parseFloat(document.getElementById('price-vip').value) || 0
            };

            const screenData = {
                rows: ROWS,
                columns: COLS,
                pricing: pricing,
                layout: layout
            };

            console.log("Master Layout Saved!", screenData);
            
            Swal.fire({
                icon: 'success',
                title: 'Layout Saved!',
                html: `Layout generated successfully.<br><br><b>Pricing set to:</b><br>Standard: ₹${pricing.standard} | Premium: ₹${pricing.premium} | VIP: ₹${pricing.vip}`,
                background: 'rgba(15, 23, 42, 0.95)',
                color: '#fff',
                confirmButtonColor: '#10b981',
                customClass: { popup: 'border border-slate-800 rounded-3xl backdrop-blur-md' }
            });
        }

        // Init
        generateGrid();
    </script>
</body>
</html>
