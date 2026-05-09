<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Signup | MovieTicket</title>

    <!-- CSRF TOKEN (IMPORTANT) -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .glass {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>

<body class="h-full flex items-center justify-center p-6 overflow-hidden relative">

    <!-- Background -->
    <div class="absolute inset-0 z-0">
        <div
            class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1440404653325-ab127d49abc1')] bg-cover bg-center opacity-30">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/80 to-transparent"></div>
    </div>

    <div class="w-full max-w-md z-10">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-extrabold text-white">
                Movie<span class="text-indigo-500">Ticket</span>
            </h1>
            <p class="text-slate-400 text-sm">Create Admin Account</p>
        </div>

        <div class="glass rounded-3xl p-8 shadow-2xl">
            <form id="registerForm" class="space-y-5">

                <input type="text" id="name" placeholder="Full Name"
                    class="w-full bg-slate-800 border border-slate-700 text-white px-4 py-3 rounded-xl">

                <input type="email" id="email" placeholder="Email"
                    class="w-full bg-slate-800 border border-slate-700 text-white px-4 py-3 rounded-xl">

                <div class="grid grid-cols-2 gap-4">
                    <input type="password" id="password" placeholder="Password"
                        class="bg-slate-800 border border-slate-700 text-white px-4 py-3 rounded-xl">

                    <input type="password" id="password_confirmation" placeholder="Confirm Password"
                        class="bg-slate-800 border border-slate-700 text-white px-4 py-3 rounded-xl">
                </div>

                <button type="button" id="registerBtn" class="w-full bg-indigo-600 text-white py-3 rounded-xl">
                    Register Admin
                </button>

            </form>

            <div class="mt-6 text-center">
                <p class="text-slate-400 text-sm">
                    Already have an account?
                    <a href="/admin/login" class="text-indigo-400 font-semibold">Login here</a>
                </p>
            </div>
        </div>
    </div>

</body>

<script>
    $(document).ready(function () {

        // CSRF setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#registerBtn').click(function () {

            let name = $('#name').val();
            let email = $('#email').val();
            let password = $('#password').val();
            let confirmPassword = $('#password_confirmation').val();

            // Basic validation
            if (!name || !email || !password || !confirmPassword) {
                Swal.fire('Error', 'All fields are required', 'error');
                return;
            }

            if (password !== confirmPassword) {
                Swal.fire('Error', 'Passwords do not match', 'error');
                return;
            }

            $.ajax({
                url: "/admin/register",
                type: "POST",
                data: {
                    name: name,
                    email: email,
                    password: password,
                    password_confirmation: confirmPassword
                },

                success: function (res) {
                    if (res.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success 🎉',
                            text: res.message
                        }).then(() => {
                            window.location.href = "/admin/login";
                        });
                    }
                },

                error: function (xhr) {

                    let errors = xhr.responseJSON?.errors;

                    if (errors) {
                        let errorMsg = '';

                        Object.keys(errors).forEach(function (key) {
                            errorMsg += errors[key][0] + '\n';
                        });

                        Swal.fire('Validation Error', errorMsg, 'error');
                    } else {
                        Swal.fire('Error', 'Something went wrong', 'error');
                    }
                }
            });
        });
    });
</script>

</html>