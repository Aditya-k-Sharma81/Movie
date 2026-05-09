<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Movie | Admin</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- jQuery & Select2 -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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

    </style>
</head>

<body class="min-h-screen text-slate-200 p-4 md:p-8">

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
</body>

</html>
