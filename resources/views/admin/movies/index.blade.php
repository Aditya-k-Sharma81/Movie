<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Movies | Admin</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        .glass {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>

<body class="min-h-screen text-slate-200 p-4 md:p-8">

    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white">All Movies</h1>
                <p class="text-slate-400">Manage all movies listed in your theatre</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <!-- Filter Form -->
                <form method="GET" action="{{ route('admin.movies.index') }}" class="flex items-center gap-3 glass px-3 py-1.5 rounded-xl">
                    <div class="flex items-center gap-2">
                        <label for="date" class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Date</label>
                        <input type="date" name="date" id="date" value="{{ request('date') }}" onchange="this.form.submit()" 
                            class="bg-slate-900/80 border border-slate-700/50 text-white text-xs rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block px-2 py-1.5 cursor-pointer">
                    </div>
                    <div class="w-px h-5 bg-slate-700/50"></div>
                    <div class="flex items-center gap-2">
                        <label for="sort" class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Sort</label>
                        <select name="sort" id="sort" onchange="this.form.submit()" 
                            class="bg-slate-900/80 border border-slate-700/50 text-white text-xs rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block px-2 py-1.5 cursor-pointer">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Recently Added</option>
                            <option value="shows_asc" {{ request('sort') == 'shows_asc' ? 'selected' : '' }}>Show Time (Soonest)</option>
                            <option value="release_desc" {{ request('sort') == 'release_desc' ? 'selected' : '' }}>Release Date (Newest)</option>
                        </select>
                    </div>
                    @if(request('date') || (request('sort') && request('sort') != 'latest'))
                        <a href="{{ route('admin.movies.index') }}" class="text-xs text-rose-400 hover:text-rose-300 ml-1 px-1 transition-colors" title="Clear Filters"><i class="fa-solid fa-xmark text-sm"></i></a>
                    @endif
                </form>

                <div class="flex gap-3">
                    <a href="{{ route('admin.dashboard') }}"
                        class="glass px-4 py-2 rounded-xl text-sm hover:bg-slate-800 transition-all flex items-center justify-center">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <a href="{{ route('admin.movies.add') }}"
                        class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-2 px-4 rounded-xl transition-all shadow-lg shadow-indigo-500/20 flex items-center justify-center whitespace-nowrap">
                        <i class="fa-solid fa-plus mr-2"></i> Add Movie
                    </a>
                </div>
            </div>
        </div>

        <!-- Movies Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">

            @forelse($movies as $movie)
            @php
                $genres = is_array($movie->genre) ? $movie->genre : (is_string($movie->genre) ? json_decode($movie->genre, true) ?? [$movie->genre] : []);
                $categories = is_array($movie->category) ? $movie->category : (is_string($movie->category) ? json_decode($movie->category, true) ?? [$movie->category] : []);
                $startTime = \Carbon\Carbon::parse($movie->start_time);
                
                // Clean version of movie for JS modal
                $movieJson = [
                    'id' => (string) $movie->id,
                    'title' => $movie->title,
                    'poster' => $movie->poster,
                    'genre' => $genres,
                    'category' => $categories,
                    'duration' => $movie->duration,
                    'description' => $movie->description,
                    'language' => $movie->language,
                    'release_date' => \Carbon\Carbon::parse($movie->release_date)->format('d M, Y'),
                    'start_time' => $startTime->format('d M, Y h:i A'),
                    'end_time' => \Carbon\Carbon::parse($movie->end_time)->format('d M, Y h:i A'),
                    'price_normal' => $movie->price_normal,
                    'price_premium' => $movie->price_premium,
                    'price_vip' => $movie->price_vip,
                ];
            @endphp
            <div class="group cursor-pointer" onclick="showMovieDetails({{ json_encode($movieJson) }})">
                <div class="relative aspect-[2/3] rounded-3xl overflow-hidden mb-3 border border-slate-800 transition-all group-hover:scale-105 group-hover:border-indigo-500/50 group-hover:shadow-xl group-hover:shadow-indigo-900/10">
                    <img src="{{ $movie->poster ?? 'https://via.placeholder.com/400x600?text=No+Poster' }}" alt="{{ $movie->title }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent opacity-80"></div>
                    
                    <div class="absolute bottom-3 left-3 flex flex-col gap-1">
                        <span class="px-2 py-0.5 bg-indigo-600 text-[9px] font-bold text-white rounded uppercase tracking-wider w-fit shadow-lg shadow-indigo-900/40">
                            {{ $startTime->format('h:i A') }}
                        </span>
                    </div>
                </div>
                <h3 class="text-white font-bold text-sm truncate group-hover:text-indigo-400 transition-colors">{{ $movie->title }}</h3>
                <p class="text-slate-500 text-[10px] truncate">
                    {{ implode(' • ', $genres) }}
                </p>
            </div>
            @empty
            <div class="col-span-full glass rounded-3xl p-12 flex flex-col items-center justify-center text-center border-dashed border-slate-700">
                <div class="w-20 h-20 rounded-full bg-slate-900/50 flex items-center justify-center text-slate-500 mb-4 border border-slate-800">
                    <i class="fa-solid fa-film text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-1">No Movies Found</h3>
                <p class="text-sm text-slate-500">Add your first movie to see it here.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Movie Details Modal -->
    <div id="movieModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-950/90 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
        <div class="glass relative w-full max-w-2xl rounded-[2.5rem] overflow-hidden shadow-2xl transform transition-all scale-95 opacity-0 duration-300" id="modalPanel">
            <button onclick="closeModal()" class="absolute top-6 right-6 z-10 w-10 h-10 rounded-full bg-slate-900/50 border border-white/10 flex items-center justify-center text-white hover:bg-white/10 transition-all">
                <i class="fa-solid fa-xmark"></i>
            </button>
            
            <div id="modalContent">
                <!-- Data injected here -->
            </div>
        </div>
    </div>

    <script>
        function showMovieDetails(movie) {
            const modal = document.getElementById('movieModal');
            const panel = document.getElementById('modalPanel');
            const content = document.getElementById('modalContent');

            content.innerHTML = `
                <div class="flex flex-col md:flex-row gap-8 p-6 md:p-8">
                    <!-- Poster & Meta -->
                    <div class="w-full md:w-1/3 flex flex-col gap-4">
                        <div class="aspect-[2/3] rounded-2xl overflow-hidden shadow-2xl border border-white/10 group-hover:scale-[1.02] transition-transform duration-500">
                            <img src="${movie.poster || 'https://via.placeholder.com/400x600?text=No+Poster'}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex flex-wrap gap-2">
                            ${movie.genre.map(g => `<span class="px-2 py-0.5 bg-slate-800 text-slate-400 text-[9px] font-bold rounded uppercase border border-slate-700 tracking-tighter">${g}</span>`).join('')}
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="flex-1 flex flex-col">
                        <div class="flex flex-wrap gap-2 mb-3">
                            ${movie.category.map(cat => `<span class="px-2 py-0.5 bg-indigo-500/10 text-indigo-400 text-[10px] font-bold rounded border border-indigo-500/20 uppercase tracking-wider">${cat}</span>`).join('')}
                        </div>
                        <h2 class="text-3xl font-black text-white mb-4 leading-tight">${movie.title}</h2>
                        
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="p-3 rounded-xl bg-slate-900/50 border border-white/5">
                                <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest mb-1">Release Date</p>
                                <p class="text-xs text-slate-200 font-bold"><i class="fa-solid fa-calendar-check mr-1.5 text-indigo-400"></i>${movie.release_date}</p>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-900/50 border border-white/5">
                                <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest mb-1">Duration</p>
                                <p class="text-xs text-slate-200 font-bold"><i class="fa-regular fa-clock mr-1.5 text-indigo-400"></i>${movie.duration}m</p>
                            </div>
                        </div>

                        <div class="space-y-6 mb-8">
                            <!-- Show Timeline -->
                            <div class="relative pl-6 border-l-2 border-slate-800 py-1 space-y-6">
                                <div class="relative">
                                    <div class="absolute -left-[31px] top-1 w-3 h-3 rounded-full bg-indigo-500 shadow-[0_0_10px_rgba(99,102,241,0.5)] border-2 border-slate-950"></div>
                                    <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest mb-1">Show Starts</p>
                                    <p class="text-sm text-white font-black tracking-tight">${movie.start_time}</p>
                                </div>
                                <div class="relative">
                                    <div class="absolute -left-[31px] top-1 w-3 h-3 rounded-full bg-rose-500 shadow-[0_0_10px_rgba(244,63,94,0.5)] border-2 border-slate-950"></div>
                                    <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest mb-1">Show Ends</p>
                                    <p class="text-sm text-white font-black tracking-tight">${movie.end_time}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-2">
                                <div class="text-center p-2 rounded-lg bg-slate-900/30 border border-white/5">
                                    <p class="text-[8px] text-slate-500 uppercase font-black mb-1">Normal</p>
                                    <p class="text-xs text-indigo-400 font-bold">₹${movie.price_normal}</p>
                                </div>
                                <div class="text-center p-2 rounded-lg bg-slate-900/30 border border-white/5">
                                    <p class="text-[8px] text-slate-500 uppercase font-black mb-1">Premium</p>
                                    <p class="text-xs text-pink-400 font-bold">₹${movie.price_premium}</p>
                                </div>
                                <div class="text-center p-2 rounded-lg bg-slate-900/30 border border-white/5">
                                    <p class="text-[8px] text-slate-500 uppercase font-black mb-1">VIP</p>
                                    <p class="text-xs text-amber-400 font-bold">₹${movie.price_vip}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-900/50 rounded-2xl p-4 border border-white/5 mb-8">
                            <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest mb-2">Synopsis</p>
                            <p class="text-[11px] text-slate-400 leading-relaxed font-medium">${movie.description}</p>
                        </div>

                        <!-- Actions -->
                        <div class="mt-auto flex items-center gap-4">
                            <a href="/admin/movies/edit/${movie.id}" class="flex-1 bg-white text-slate-950 font-black py-4 px-6 rounded-2xl hover:bg-indigo-400 hover:text-white transition-all text-center uppercase tracking-widest text-[10px] shadow-xl">
                                <i class="fa-solid fa-pen-to-square mr-2"></i> Edit Movie
                            </a>
                            <button onclick="deleteMovie('${movie.id}')" class="w-14 h-14 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-500 hover:bg-rose-500 hover:text-white transition-all shadow-xl">
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
            const modal = document.getElementById('movieModal');
            const panel = document.getElementById('modalPanel');
            
            panel.classList.add('scale-95', 'opacity-0');
            panel.classList.remove('scale-100', 'opacity-100');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function deleteMovie(id) {
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
                    fetch(`/admin/movies/delete/${id}`, {
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
</body>

</html>