<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile | MovieTicket</title>

    <!-- CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- SweetAlert -->
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

        .profile-gradient {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        }

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
    </style>
</head>

<body class="min-h-screen text-slate-200 p-4 md:p-8">

    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-white">Admin Profile</h1>
                <p class="text-slate-400">Manage your personal information and preferences</p>
            </div>
            <a href="/admin/dashboard" class="glass px-4 py-2 rounded-xl text-sm hover:bg-slate-800 transition-all">
                <i class="fa-solid fa-arrow-left mr-2"></i> Back to Dashboard
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Sidebar / Avatar Card -->
            <div class="md:col-span-1 space-y-6">
                <div class="glass rounded-3xl p-6 text-center">
                    <div class="relative inline-block mb-4">
                        <div class="w-32 h-32 rounded-full profile-gradient p-1">
                            <img id="avatarImg" src="https://ui-avatars.com/api/?name={{ urlencode($admin->name ?? 'Admin') }}&background=0f172a&color=fff&size=128" 
                                 alt="Avatar" class="w-full h-full rounded-full object-cover border-4 border-slate-900">
                        </div>
                    </div>
                    <h2 id="display-name" class="text-xl font-bold text-white">{{ $admin->name ?? 'Admin User' }}</h2>
                    <div class="flex justify-center gap-2">
                        <span class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-500 text-xs border border-emerald-500/20">
                            Active Account
                        </span>
                    </div>
                </div>

                <!-- Theatre Pic Card -->
                <div class="glass rounded-3xl p-6 overflow-hidden">
                    <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-4">Theatre Picture</h3>
                    <div class="aspect-video w-full rounded-xl bg-slate-800 border border-slate-700 overflow-hidden relative group">
                        @if($admin->theatre_pic)
                            <img id="theatrePreview" src="{{ asset($admin->theatre_pic) }}" class="w-full h-full object-cover">
                        @else
                            <div id="theatrePlaceholder" class="w-full h-full flex flex-col items-center justify-center text-slate-600">
                                <i class="fa-solid fa-clapperboard text-3xl mb-2"></i>
                                <p class="text-xs">No Picture Set</p>
                            </div>
                            <img id="theatrePreview" src="" class="w-full h-full object-cover hidden">
                        @endif
                    </div>
                </div>
            </div>

            <!-- Form Section -->
            <div class="md:col-span-2 space-y-6">
                <div class="glass rounded-3xl p-8">
                    <h3 class="text-xl font-bold text-white mb-6">Edit Information</h3>

                    <form id="profileForm" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-medium text-slate-500 uppercase mb-2">Admin Name</label>
                            <input type="text" name="name" value="{{ $admin->name ?? '' }}" 
                                   class="form-input w-full px-4 py-3 rounded-xl" placeholder="Admin Full Name">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-medium text-slate-500 uppercase mb-2">Phone Number</label>
                            <input type="text" name="phone" value="{{ $admin->phone ?? '' }}" 
                                   class="form-input w-full px-4 py-3 rounded-xl" placeholder="+91 00000 00000">
                        </div>
                        
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-medium text-slate-500 uppercase mb-2">Email Address (Login ID)</label>
                            <input type="email" value="{{ $admin->email ?? '' }}" readonly
                                   class="form-input w-full px-4 py-3 rounded-xl opacity-50 cursor-not-allowed">
                        </div>

                        <div class="sm:col-span-1">
                            <label class="block text-xs font-medium text-slate-500 uppercase mb-2">Theatre Name</label>
                            <input type="text" name="theatre_name" value="{{ $admin->theatre_name ?? '' }}" 
                                   class="form-input w-full px-4 py-3 rounded-xl" placeholder="e.g. Galaxy Cinemas">
                        </div>

                        <div class="sm:col-span-1">
                            <label class="block text-xs font-medium text-slate-500 uppercase mb-2">Theatre Type</label>
                            <select name="theatre_type" class="form-input w-full px-4 py-3 rounded-xl">
                                <option value="" disabled {{ !isset($admin->theatre_type) ? 'selected' : '' }}>Select Type</option>
                                <option value="Single Screen" {{ ($admin->theatre_type ?? '') == 'Single Screen' ? 'selected' : '' }}>Single Screen</option>
                                <option value="Multiplex" {{ ($admin->theatre_type ?? '') == 'Multiplex' ? 'selected' : '' }}>Multiplex</option>
                            </select>
                        </div>

                        <div class="sm:col-span-1">
                            <label class="block text-xs font-medium text-slate-500 uppercase mb-2">Seating Capacity</label>
                            <input type="number" name="capacity" value="{{ $admin->capacity ?? '' }}" 
                                   class="form-input w-full px-4 py-3 rounded-xl" placeholder="Total seats">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-medium text-slate-500 uppercase mb-2">Theatre Address</label>
                            <textarea name="address" rows="3" class="form-input w-full px-4 py-3 rounded-xl" placeholder="Full address of the theatre...">{{ $admin->address ?? '' }}</textarea>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-medium text-slate-500 uppercase mb-2">Theatre Profile Picture</label>
                            <input type="file" name="theatre_pic" id="theatre_pic_input"
                                   class="form-input w-full px-4 py-3 rounded-xl file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500">
                        </div>

                        <div class="sm:col-span-2 pt-4">
                            <button type="submit" id="saveBtn" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3 px-8 rounded-xl transition-all shadow-lg shadow-indigo-500/20">
                                Save Profile Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // CSRF setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Preview Theatre Pic
            $('#theatre_pic_input').change(function() {
                const file = this.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        $('#theatrePlaceholder').addClass('hidden');
                        $('#theatrePreview').attr('src', event.target.result).removeClass('hidden');
                    }
                    reader.readAsDataURL(file);
                }
            });

            $('#profileForm').on('submit', function(e) {
                e.preventDefault();
                
                let formData = new FormData(this);

                // Show loading
                $('#saveBtn').prop('disabled', true).html('<i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Saving...');

                $.ajax({
                    url: "{{ route('admin.profile.update') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: res.message,
                                background: '#0f172a',
                                color: '#fff'
                            }).then(() => {
                                // Update dynamic elements
                                $('#display-name').text($('input[name="name"]').val());
                                $('#avatarImg').attr('src', 'https://ui-avatars.com/api/?name=' + encodeURIComponent($('input[name="name"]').val()) + '&background=0f172a&color=fff&size=128');
                            });
                        }
                    },
                    error: function(xhr) {
                        let msg = xhr.responseJSON?.message || 'Something went wrong';
                        if (xhr.responseJSON?.errors) {
                            msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: msg,
                            background: '#0f172a',
                            color: '#fff'
                        });
                    },
                    complete: function() {
                        $('#saveBtn').prop('disabled', false).text('Save Profile Changes');
                    }
                });
            });
        });
    </script>

</body>

</html>
