<?php

namespace App\Http\Controllers;

use App\Models\AdminSignupModel;
use App\Models\Booking;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class AdminSignupController extends Controller
{

    public function dashboard()
    {
        $todayStart = now('Asia/Kolkata')->startOfDay()->toDateTimeString();
        $todayEnd = now('Asia/Kolkata')->endOfDay()->toDateTimeString();

        // All-time stats
        $totalRevenue = Booking::sum('total_price') ?? 0;
        $totalTickets = Booking::sum('ticket_count') ?? 0;
        $totalMovies = Movie::count();
        $totalBookings = Booking::count();

        // Today's stats
        $todayBookingsQuery = Booking::where('booking_date', '>=', $todayStart)
            ->where('booking_date', '<=', $todayEnd);
        $todayRevenue = $todayBookingsQuery->sum('total_price') ?? 0;
        $todayTickets = $todayBookingsQuery->sum('ticket_count') ?? 0;

        // Recent 5 bookings with movie info
        $recentBookings = Booking::with('movie')
            ->orderBy('booking_date', 'desc')
            ->limit(5)
            ->get();

        // Top 5 movies by revenue
        $topMoviesAgg = Booking::raw(function($collection) {
            return $collection->aggregate([
                ['$group' => [
                    '_id' => '$movie_id',
                    'revenue' => ['$sum' => '$total_price'],
                    'tickets' => ['$sum' => '$ticket_count']
                ]],
                ['$sort' => ['revenue' => -1]],
                ['$limit' => 5]
            ]);
        });

        $topMovies = collect($topMoviesAgg)->map(function ($group) {
            $groupId = is_object($group) ? $group->_id : $group['_id'];
            $revenue = is_object($group) ? $group->revenue : $group['revenue'];
            $tickets = is_object($group) ? $group->tickets : $group['tickets'];
            return [
                'movie' => Movie::find($groupId),
                'revenue' => $revenue,
                'tickets' => $tickets,
            ];
        });

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalTickets',
            'totalMovies',
            'totalBookings',
            'todayRevenue',
            'todayTickets',
            'recentBookings',
            'topMovies'
        ));
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        Log::info('Login attempt for: ' . $request->email);

        // Find admin by email
        $admin = AdminSignupModel::where('email', $request->email)->first();

        // Verify password manually
        if ($admin && Hash::check($request->password, $admin->password)) {
            // Store admin data in session
            session([
                'admin_id' => (string) $admin->_id,
                'admin_name' => $admin->name,
                'admin_email' => $admin->email,
            ]);

            Log::info('Login successful for: ' . $request->email);
            return response()->json([
                'status' => true,
                'message' => 'Login successful! Redirecting...'
            ]);
        }

        Log::warning('Login failed for: ' . $request->email);
        return response()->json([
            'status' => false,
            'message' => 'Invalid email or password'
        ], 401);
    }

    public function updateProfile(Request $request)
    {
        $adminId = session('admin_id');
        if (!$adminId) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'theatre_name' => 'nullable|string|max:255',
            'theatre_pic' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'capacity' => 'nullable|integer|min:1',
            'address' => 'nullable|string|max:500',
            'theatre_type' => 'nullable|string|in:Single Screen,Multiplex',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            Log::info('Attempting to update profile for admin ID: ' . $adminId);

            $admin = AdminSignupModel::where('_id', $adminId)->first();

            if (!$admin) {
                Log::error('Admin not found for ID: ' . $adminId);
                return response()->json(['status' => false, 'message' => 'Admin not found'], 404);
            }

            $data = $request->only('name', 'phone', 'theatre_name', 'capacity', 'address', 'theatre_type');

            if ($request->hasFile('theatre_pic')) {
                Log::info('Theatre pic detected in request. Using manual Cloudinary Engine...');

                $file = $request->file('theatre_pic');

                try {
                    // Manually initialize Cloudinary Engine to bypass Facade issues in Laravel 12
                    $cloudinary = new \Cloudinary\Cloudinary([
                        'cloud' => [
                            'cloud_name' => config('cloudinary.cloud_name'),
                            'api_key' => config('cloudinary.api_key'),
                            'api_secret' => config('cloudinary.api_secret'),
                        ],
                        'url' => [
                            'secure' => true
                        ]
                    ]);

                    $upload = $cloudinary->uploadApi()->upload($file->getRealPath(), [
                        'folder' => 'theatres'
                    ]);

                    if ($upload && isset($upload['secure_url'])) {
                        $data['theatre_pic'] = $upload['secure_url'];
                        Log::info('Manual Cloudinary upload successful: ' . $upload['secure_url']);
                    } else {
                        Log::error('Cloudinary upload failed or secure_url not found');
                    }
                } catch (\Exception $uploadError) {
                    Log::error('Cloudinary Engine Error: ' . $uploadError->getMessage());
                    return response()->json([
                        'status' => false,
                        'message' => 'Cloudinary Upload Error: ' . $uploadError->getMessage()
                    ], 500);
                }
            }

            $admin->update($data);
            Log::info('Database update successful');

            // Update session data
            session(['admin_name' => $admin->name]);

            return response()->json([
                'status' => true,
                'message' => 'Profile updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error updating profile: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showSeatingLayout()
    {
        $adminId = session('admin_id');
        $admin = AdminSignupModel::find($adminId);
        return view('admin.screens.seating', compact('admin'));
    }

    public function saveSeatingLayout(Request $request)
    {
        $adminId = session('admin_id');
        if (!$adminId) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'layout_data' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $admin = AdminSignupModel::where('_id', $adminId)->first();
            if (!$admin) {
                return response()->json(['status' => false, 'message' => 'Admin not found'], 404);
            }

            // Save the layout JSON to the model
            $admin->update([
                'seating_layout' => $request->layout_data
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Seating layout saved successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error saving layout: ' . $e->getMessage()
            ], 500);
        }
    }

    public function adminBookings(Request $request)
    {
        $date = $request->input('date', now('Asia/Kolkata')->toDateString());

        // MongoDB: booking_date stored as string "Y-m-d H:i:s" — use range filter
        $start = $date . ' 00:00:00';
        $end = $date . ' 23:59:59';

        $bookings = Booking::with('movie')
            ->where('booking_date', '>=', $start)
            ->where('booking_date', '<=', $end)
            ->get();

        // Group by movie_id and get movie details
        $moviesWithStats = [];
        foreach ($bookings->groupBy('movie_id') as $movieId => $movieBookings) {
            $movie = $movieBookings->first()->movie;
            if (!$movie)
                continue;
            $moviesWithStats[] = [
                'movie' => $movie,
                'total_bookings' => $movieBookings->count(),
                'total_seats' => $movieBookings->sum('ticket_count'),
                'total_revenue' => $movieBookings->sum('total_price'),
            ];
        }

        $totalRevenue = $bookings->sum('total_price');
        $totalTickets = $bookings->sum('ticket_count');

        return view('admin.bookings.index', compact('moviesWithStats', 'totalRevenue', 'totalTickets', 'date'));
    }

    public function movieBookingDetails(Request $request, $movieId)
    {
        $movie = Movie::find($movieId);

        $bookings = Booking::where('movie_id', $movieId)
            ->orderBy('booking_date', 'desc')
            ->get();

        $totalRevenue = $bookings->sum('total_price');
        $totalSeats = $bookings->sum('ticket_count');

        return view('admin.bookings.details', compact('movie', 'bookings', 'totalRevenue', 'totalSeats'));
    }

    public function todayMovies(Request $request)
    {
        $today = $request->input('date', now('Asia/Kolkata')->toDateString());

        $todayStart = $today . 'T00:00';
        $todayEnd = $today . 'T23:59';

        $todayMovies = Movie::where('start_time', '>=', $todayStart)
            ->where('start_time', '<=', $todayEnd)
            ->orderBy('start_time', 'asc')
            ->get();

        return view('admin.today-movies', compact('todayMovies', 'today'));
    }

    public function logout(Request $request)
    {
        // Remove admin data from session
        $request->session()->forget(['admin_id', 'admin_name', 'admin_email']);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}
