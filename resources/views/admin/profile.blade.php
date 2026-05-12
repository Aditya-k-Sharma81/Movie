<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile | MovieTicket</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass { background: rgba(15,23,42,0.6); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.07); }
        .field {
            background: rgba(15,23,42,0.9);
            border: 1px solid #1e293b;
            color: #f1f5f9;
            transition: border-color .2s, box-shadow .2s;
        }
        .field:focus { border-color: #e11d48; box-shadow: 0 0 0 3px rgba(225,29,72,.1); outline: none; }
        .field::placeholder { color: #475569; }
        select.field option { background: #0f172a; }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
    </style>
</head>
<body class="min-h-screen bg-slate-950 text-slate-200">

<!-- Navbar -->
<header class="glass border-b border-slate-800 sticky top-0 z-50 px-8 h-14 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.dashboard') }}" class="p-1.5 bg-slate-800 hover:bg-slate-700 rounded-xl text-slate-400 hover:text-white transition-all">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
        </a>
        <span class="text-white font-black text-sm">My Profile</span>
    </div>
    <a href="{{ route('admin.logout') }}" class="text-slate-500 hover:text-rose-400 text-xs font-bold flex items-center gap-1.5 transition-colors">
        <i data-lucide="log-out" class="w-3.5 h-3.5"></i> Logout
    </a>
</header>

<!-- Page -->
<div class="max-w-5xl mx-auto px-6 py-10 space-y-6">

    <!-- Profile Header Card -->
    <div class="glass border border-slate-800 rounded-3xl overflow-hidden">
        <!-- Colored strip -->
        <div class="h-24 bg-gradient-to-r from-rose-600/30 via-indigo-600/20 to-slate-900 relative">
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?q=40&w=1200&auto=format&fit=crop')] bg-cover bg-center opacity-10"></div>
        </div>
        <!-- Avatar + Info Row -->
        <div class="px-8 pb-6 -mt-10 flex flex-col sm:flex-row sm:items-end gap-5">
            <!-- Avatar -->
            <div class="relative shrink-0">
                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-rose-500 to-indigo-600 p-px shadow-xl">
                    <div class="w-full h-full rounded-[calc(1rem-1px)] bg-slate-900 flex items-center justify-center overflow-hidden">
                        @if($admin->theatre_pic)
                            <img src="{{ $admin->theatre_pic }}" id="avatarPreview" class="w-full h-full object-cover">
                        @else
                            <span id="avatarInitial" class="text-3xl font-black text-white select-none">
                                {{ strtoupper(substr($admin->name ?? 'A', 0, 1)) }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 rounded-full border-2 border-slate-950"></div>
            </div>

            <!-- Name & Badges -->
            <div class="flex-grow">
                <h1 id="displayName" class="text-2xl font-black text-white mb-1">{{ $admin->name ?? 'Admin User' }}</h1>
                <p class="text-slate-500 text-sm mb-3">{{ $admin->email ?? '' }}</p>
                <div class="flex flex-wrap gap-2">
                    <span class="px-2.5 py-0.5 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-full text-[9px] font-black uppercase tracking-widest">
                        ● Active Admin
                    </span>
                    @if($admin->theatre_name)
                    <span class="px-2.5 py-0.5 bg-slate-800 text-slate-400 border border-slate-700 rounded-full text-[9px] font-bold flex items-center gap-1">
                        <i data-lucide="clapperboard" class="w-2.5 h-2.5"></i> {{ $admin->theatre_name }}
                    </span>
                    @endif
                    @if($admin->theatre_type)
                    <span class="px-2.5 py-0.5 bg-slate-800 text-slate-400 border border-slate-700 rounded-full text-[9px] font-bold">
                        {{ $admin->theatre_type }}
                    </span>
                    @endif
                    @if($admin->capacity)
                    <span class="px-2.5 py-0.5 bg-slate-800 text-slate-400 border border-slate-700 rounded-full text-[9px] font-bold flex items-center gap-1">
                        <i data-lucide="armchair" class="w-2.5 h-2.5"></i> {{ $admin->capacity }} seats
                    </span>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="flex gap-4 shrink-0">
                <div class="text-center">
                    <p class="text-[9px] text-slate-600 uppercase font-black tracking-widest">Type</p>
                    <p class="text-sm font-black text-white mt-0.5">{{ $admin->theatre_type ?? '—' }}</p>
                </div>
                <div class="w-px bg-slate-800"></div>
                <div class="text-center">
                    <p class="text-[9px] text-slate-600 uppercase font-black tracking-widest">Capacity</p>
                    <p class="text-sm font-black text-white mt-0.5">{{ $admin->capacity ?? '—' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Two-column form -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Left: Theatre Photo -->
        <div class="space-y-5">
            <div class="glass border border-slate-800 rounded-2xl overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-800 flex items-center gap-2">
                    <i data-lucide="image" class="w-4 h-4 text-rose-500"></i>
                    <h3 class="text-white font-black text-xs uppercase tracking-widest">Theatre Photo</h3>
                </div>
                <!-- Photo preview / upload zone -->
                <div class="relative aspect-video cursor-pointer overflow-hidden group bg-slate-900"
                     onclick="document.getElementById('theatre_pic_input').click()">
                    @if($admin->theatre_pic)
                        <img id="theatrePreview" src="{{ $admin->theatre_pic }}" class="w-full h-full object-cover">
                    @else
                        <img id="theatrePreview" src="" class="w-full h-full object-cover hidden">
                        <div id="theatrePlaceholder" class="w-full h-full flex flex-col items-center justify-center text-slate-600 gap-2">
                            <i data-lucide="image-plus" class="w-8 h-8"></i>
                            <p class="text-xs font-bold">Upload Photo</p>
                        </div>
                    @endif
                    <!-- Hover overlay -->
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/50 transition-all flex items-center justify-center">
                        <span class="opacity-0 group-hover:opacity-100 transition-all text-white text-xs font-black bg-rose-600 px-3 py-1.5 rounded-xl flex items-center gap-1.5">
                            <i data-lucide="upload" class="w-3.5 h-3.5"></i> Change
                        </span>
                    </div>
                </div>
                <div class="px-5 py-3 border-t border-slate-800">
                    <p id="fileLabel" class="text-[10px] text-slate-600 truncate">No file chosen</p>
                </div>
            </div>

            @if($admin->address)
            <div class="glass border border-slate-800 rounded-2xl p-5">
                <div class="flex items-center gap-2 mb-3">
                    <i data-lucide="map-pin" class="w-4 h-4 text-rose-500 shrink-0"></i>
                    <h3 class="text-white font-black text-xs uppercase tracking-widest">Address</h3>
                </div>
                <p class="text-slate-400 text-sm leading-relaxed">{{ $admin->address }}</p>
            </div>
            @endif
        </div>

        <!-- Right: Edit Form -->
        <div class="md:col-span-2">
            <div class="glass border border-slate-800 rounded-2xl">
                <div class="px-6 py-4 border-b border-slate-800 flex items-center gap-3">
                    <i data-lucide="pencil-line" class="w-4 h-4 text-rose-500"></i>
                    <h3 class="text-white font-black text-sm">Edit Information</h3>
                </div>

                <form id="profileForm" enctype="multipart/form-data" class="p-6 space-y-6">
                    <input type="file" id="theatre_pic_input" name="theatre_pic" class="hidden" accept="image/*">

                    <!-- Personal -->
                    <div class="space-y-4">
                        <p class="text-[9px] text-slate-600 uppercase font-black tracking-widest border-b border-slate-800 pb-2">Personal</p>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="block text-[10px] text-slate-500 uppercase font-black tracking-widest mb-1.5">Full Name</label>
                                <input type="text" name="name" value="{{ $admin->name ?? '' }}"
                                    class="field w-full px-4 py-2.5 rounded-xl text-sm" placeholder="Full name">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-500 uppercase font-black tracking-widest mb-1.5">Phone</label>
                                <input type="text" name="phone" value="{{ $admin->phone ?? '' }}"
                                    class="field w-full px-4 py-2.5 rounded-xl text-sm" placeholder="+91 00000 00000">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-500 uppercase font-black tracking-widest mb-1.5">Email (read-only)</label>
                                <input type="email" value="{{ $admin->email ?? '' }}" readonly
                                    class="field w-full px-4 py-2.5 rounded-xl text-sm opacity-40 cursor-not-allowed">
                            </div>
                        </div>
                    </div>

                    <!-- Theatre -->
                    <div class="space-y-4">
                        <p class="text-[9px] text-slate-600 uppercase font-black tracking-widest border-b border-slate-800 pb-2">Theatre Details</p>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] text-slate-500 uppercase font-black tracking-widest mb-1.5">Theatre Name</label>
                                <input type="text" name="theatre_name" value="{{ $admin->theatre_name ?? '' }}"
                                    class="field w-full px-4 py-2.5 rounded-xl text-sm" placeholder="e.g. Galaxy Cinemas">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-500 uppercase font-black tracking-widest mb-1.5">Theatre Type</label>
                                <select name="theatre_type" class="field w-full px-4 py-2.5 rounded-xl text-sm">
                                    <option value="" disabled {{ !($admin->theatre_type ?? null) ? 'selected' : '' }}>Select type</option>
                                    <option value="Single Screen" {{ ($admin->theatre_type ?? '') == 'Single Screen' ? 'selected' : '' }}>Single Screen</option>
                                    <option value="Multiplex"     {{ ($admin->theatre_type ?? '') == 'Multiplex'     ? 'selected' : '' }}>Multiplex</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-500 uppercase font-black tracking-widest mb-1.5">Seating Capacity</label>
                                <input type="number" name="capacity" value="{{ $admin->capacity ?? '' }}"
                                    class="field w-full px-4 py-2.5 rounded-xl text-sm" placeholder="Total seats">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-500 uppercase font-black tracking-widest mb-1.5">Theatre Photo</label>
                                <button type="button" onclick="document.getElementById('theatre_pic_input').click()"
                                    class="field w-full px-4 py-2.5 rounded-xl text-sm text-slate-500 hover:text-white hover:border-rose-500 transition-all flex items-center gap-2">
                                    <i data-lucide="upload" class="w-3.5 h-3.5 shrink-0"></i>
                                    <span class="truncate" id="uploadBtnLabel">Choose image...</span>
                                </button>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-[10px] text-slate-500 uppercase font-black tracking-widest mb-1.5">Address</label>
                                <textarea name="address" rows="3"
                                    class="field w-full px-4 py-2.5 rounded-xl text-sm resize-none"
                                    placeholder="Full address of the theatre...">{{ $admin->address ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit" id="saveBtn"
                        class="w-full py-3 bg-rose-600 hover:bg-rose-700 active:scale-[.98] text-white font-black rounded-2xl transition-all shadow-lg shadow-rose-900/20 flex items-center justify-center gap-2 text-sm">
                        <i data-lucide="save" class="w-4 h-4"></i> Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    // File pick → preview + label
    $('#theatre_pic_input').on('change', function () {
        const file = this.files[0];
        if (!file) return;
        $('#fileLabel, #uploadBtnLabel').text(file.name);
        const r = new FileReader();
        r.onload = e => {
            $('#theatrePlaceholder').addClass('hidden');
            $('#theatrePreview').attr('src', e.target.result).removeClass('hidden');
            $('#avatarPreview').length && $('#avatarPreview').attr('src', e.target.result);
        };
        r.readAsDataURL(file);
    });

    // Submit
    $('#profileForm').on('submit', function (e) {
        e.preventDefault();
        const fd = new FormData(this);

        $('#saveBtn').prop('disabled', true).html(
            '<svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg> Saving...'
        );

        $.ajax({
            url: "{{ route('admin.profile.update') }}",
            type: 'POST', data: fd, processData: false, contentType: false,
            success: res => {
                if (res.status) {
                    Swal.fire({ icon: 'success', title: 'Saved!', text: res.message,
                        background: '#0f172a', color: '#f8fafc', confirmButtonColor: '#e11d48',
                        timer: 2000, showConfirmButton: false
                    }).then(() => {
                        const n = $('input[name="name"]').val();
                        $('#displayName').text(n);
                        $('#avatarInitial').text(n.charAt(0).toUpperCase());
                    });
                }
            },
            error: xhr => {
                let msg = xhr.responseJSON?.message || 'Something went wrong.';
                if (xhr.responseJSON?.errors) msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                Swal.fire({ icon: 'error', title: 'Error', html: msg,
                    background: '#0f172a', color: '#f8fafc', confirmButtonColor: '#e11d48' });
            },
            complete: () => {
                $('#saveBtn').prop('disabled', false).html(
                    '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg> Save Changes'
                );
            }
        });
    });
</script>
</body>
</html>
