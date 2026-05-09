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
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

            @forelse($movies as $movie)
            <div class="glass rounded-2xl overflow-hidden hover:-translate-y-2 hover:shadow-2xl hover:shadow-indigo-500/20 hover:border-indigo-500/50 transition-all duration-300 flex flex-col">
                <div class="relative h-[340px] w-full overflow-hidden bg-slate-900">
                    <img src="{{ $movie->poster ?? 'https://via.placeholder.com/400x600/1e293b/ffffff?text=Movie+Poster' }}" alt="{{ $movie->title }}"
                        class="w-full h-full object-cover object-center">
                        
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/20 to-transparent pointer-events-none"></div>
                    <div class="absolute bottom-4 left-4 right-4 flex justify-between items-end">
                        @php
                            $genres = is_array($movie->genre) ? $movie->genre : (is_string($movie->genre) ? json_decode($movie->genre, true) ?? [$movie->genre] : ['Genre']);
                        @endphp
                        <span class="bg-indigo-600/90 backdrop-blur text-white text-[10px] font-bold px-2 py-1 rounded-md uppercase tracking-wider max-w-[70%] line-clamp-1" title="{{ implode(', ', $genres) }}">
                            {{ implode(', ', $genres) }}
                        </span>
                        <span class="text-xs text-white/90 font-medium bg-slate-800/80 px-2 py-1 rounded-md backdrop-blur shadow-sm">
                            <i class="fa-regular fa-clock mr-1 text-indigo-400"></i>{{ $movie->duration }}m
                        </span>
                    </div>
                </div>
                <div class="p-5 flex-1 flex flex-col">
                    <h3 class="text-lg font-bold text-white mb-3 line-clamp-1 cursor-pointer hover:text-indigo-300 transition-colors" onclick="this.classList.toggle('line-clamp-1')" title="Click to toggle full title">{{ $movie->title }}</h3>
                    
                    <div class="mb-4 flex-1 flex flex-col justify-start">
                        <p class="text-[12px] text-slate-300 flex items-center mb-4">
                            <i class="fa-solid fa-calendar-days w-4 text-indigo-400"></i>
                            <span class="ml-1 font-medium">Release:</span> 
                            <span class="ml-1 text-slate-400">{{ \Carbon\Carbon::parse($movie->release_date)->format('d M, Y') }}</span>
                        </p>
                        
                        <!-- Shows Timeline Box -->
                        <div class="bg-slate-900/50 rounded-xl p-3 border border-slate-700/50 mt-auto">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-[9px] uppercase font-bold tracking-wider text-emerald-400"><i class="fa-solid fa-play mr-1"></i> Starts</span>
                                <span class="text-[9px] uppercase font-bold tracking-wider text-rose-400">Ends <i class="fa-solid fa-stop ml-1"></i></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <div class="text-left">
                                    <div class="text-[11px] text-slate-200 font-medium">{{ \Carbon\Carbon::parse($movie->start_time)->format('d M, Y') }}</div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($movie->start_time)->format('h:i A') }}</div>
                                </div>
                                <div class="flex-1 flex items-center justify-center px-2 opacity-50">
                                    <div class="h-[1px] w-full bg-slate-600"></div>
                                    <div class="w-1 h-1 rounded-full bg-slate-500 mx-1"></div>
                                    <div class="h-[1px] w-full bg-slate-600"></div>
                                </div>
                                <div class="text-right">
                                    <div class="text-[11px] text-slate-200 font-medium">{{ \Carbon\Carbon::parse($movie->end_time)->format('d M, Y') }}</div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($movie->end_time)->format('h:i A') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-slate-700/50 mt-auto">
                        <a href="{{ route('admin.movies.edit', $movie->id) }}" class="text-xs text-indigo-400 hover:text-indigo-300 font-semibold px-2 py-1 -ml-2 rounded hover:bg-indigo-500/10 transition-colors">
                            <i class="fa-solid fa-pen mr-1"></i> Edit
                        </a>
                        <button onclick="deleteMovie('{{ $movie->id }}')" class="text-xs text-rose-400 hover:text-rose-300 font-semibold px-2 py-1 -mr-2 rounded hover:bg-rose-500/10 transition-colors">
                            <i class="fa-solid fa-trash mr-1"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div
                class="col-span-full glass rounded-2xl p-6 flex flex-col items-center justify-center text-center border-dashed border-slate-700">
                <div
                    class="w-16 h-16 rounded-full bg-slate-800/50 flex items-center justify-center text-slate-400 mb-4">
                    <i class="fa-solid fa-film text-2xl"></i>
                </div>
                <h3 class="text-sm font-semibold text-white mb-1">No Movies Found</h3>
                <p class="text-xs text-slate-500">There are no movies currently listed in the database.</p>
            </div>
            @endforelse

        </div>
    </div>

    <script>
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