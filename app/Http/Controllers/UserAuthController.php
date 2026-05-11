<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Movie;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class UserAuthController extends Controller
{
    public function index()
    {
        $movies = $this->movieQuery();
        return view('user.dashboard', compact('movies'));
    }

    public function fetchMovies(Request $request)
    {
        $movies = $this->movieQuery($request);
        $showTime = $request->get('showTime', 'true') === 'true';
        $showDate = $request->get('showDate', 'true') === 'true';

        return response()->json([
            'status' => true,
            'count' => count($movies),
            'html' => view('user.partials.movie_grid', compact('movies', 'showTime', 'showDate'))->render()
        ]);
    }

    public function movies(Request $request)
    {
        $movies = $this->movieQuery($request);

        // Get unique values for filters
        $allMovies = Movie::all();
        $categories = $allMovies->pluck('category')->flatten()->unique()->filter()->values();
        $genres = $allMovies->pluck('genre')->flatten()->unique()->filter()->values();

        return view('user.movies', compact('movies', 'categories', 'genres'));
    }

    public function movieDetails($id)
    {
        $movie = Movie::find($id);
        if (!$movie) {
            return redirect()->route('dashboard')->with('error', 'Movie not found.');
        }

        // Fetch theatre details from the admin profile
        $admin = \App\Models\AdminSignupModel::first();
        $theatreDetails = [
            'location' => $admin->address ?? 'Main Road, City Center',
            'theatre_name' => $admin->theatre_name ?? 'Grand Cinema',
            'screen_type' => 'Single Screen' // As requested by user
        ];

        return view('user.movie_details', compact('movie', 'theatreDetails'));
    }

    private function movieQuery(Request $request = null)
    {
        $now = now('Asia/Kolkata');
        $query = Movie::query();

        // 1. Initial Database Filtering (Basic)
        if ($request) {
            if ($request->filled('search')) {
                $query->where('title', 'LIKE', '%' . $request->search . '%');
            }

            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }

            if ($request->filled('genre')) {
                $query->where('genre', $request->genre);
            }
        }

        // 2. Fetch and apply time-based filtering in PHP for reliability
        return $query->get()->filter(function ($movie) use ($now) {
            try {
                $startTime = \Carbon\Carbon::parse($movie->start_time, 'Asia/Kolkata');
                return $startTime->greaterThanOrEqualTo($now);
            } catch (\Exception $e) {
                return false;
            }
        })->sortBy('start_time')->values();
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:mongodb.users,email',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'required|accepted',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'terms_accepted' => true,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Registration successful! Welcome to MovieTicket.'
            ]);

        } catch (\Exception $e) {
            Log::error('User Registration Error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
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
                'message' => 'Please provide email and password.'
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Authentication successful
            session([
                'user_id' => (string) $user->_id,
                'user_name' => $user->name,
                'user_email' => $user->email,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Login successful! Redirecting...'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid email or password.'
        ], 401);
    }

    public function myBookings()
    {
        $userId = session('user_id');
        $bookings = Booking::where('user_id', $userId)
                           ->orderBy('booking_date', 'desc')
                           ->get();

        // Attach movie details to each booking
        $bookings = $bookings->map(function ($booking) {
            $movie = Movie::find($booking->movie_id);
            $booking->movie = $movie;
            return $booking;
        });

        return view('user.bookings', compact('bookings'));
    }

    public function cancelBooking($id)
    {
        $userId = session('user_id');
        $booking = Booking::where('_id', $id)->where('user_id', $userId)->first();

        if (!$booking) {
            return response()->json(['status' => false, 'message' => 'Booking not found.'], 404);
        }

        // Find the movie and free up the seats
        $movie = Movie::find($booking->movie_id);
        if ($movie) {
            $layout = $movie->seating_layout;
            foreach ($layout['layout'] as &$row) {
                foreach ($row as &$seat) {
                    if (in_array($seat['id'], $booking->seats)) {
                        $seat['status'] = 'available';
                        unset($seat['booked_at']);
                        unset($seat['user_id']);
                    }
                }
            }
            $movie->seating_layout = $layout;
            $movie->save();
        }

        $booking->delete();

        return response()->json(['status' => true, 'message' => 'Booking cancelled successfully.']);
    }

    public function fetchSeats($id)
    {
        $movie = Movie::find($id);
        if (!$movie) return response()->json(['status' => false], 404);

        return response()->json([
            'status' => true,
            'layout' => $movie->seating_layout
        ]);
    }

    public function bookTickets(Request $request, $id)
    {
        $selectedSeatIds = $request->input('seats', []); 
        $attendees = $request->input('attendees', []); // Array of objects {name, email, phone}

        if (empty($selectedSeatIds)) {
            return response()->json(['status' => false, 'message' => 'No seats selected.'], 400);
        }

        if (count($selectedSeatIds) !== count($attendees)) {
            return response()->json(['status' => false, 'message' => 'Please provide details for every seat selected.'], 400);
        }

        $movie = Movie::find($id);
        if (!$movie) return response()->json(['status' => false, 'message' => 'Movie not found.'], 404);
        
        $layout = $movie->seating_layout;
        $alreadyBooked = [];
        $totalPrice = 0;

        // Pricing map
        $prices = [
            'standard' => (float)$movie->price_normal,
            'premium' => (float)$movie->price_premium,
            'vip' => (float)$movie->price_vip
        ];

        // Iterate through the layout and update selected seats
        foreach ($layout['layout'] as &$row) {
            foreach ($row as &$seat) {
                if (in_array($seat['id'], $selectedSeatIds)) {
                    if ($seat['status'] !== 'available') {
                        $alreadyBooked[] = $seat['id'];
                        continue;
                    }
                    $seat['status'] = 'booked';
                    $seat['booked_at'] = now('Asia/Kolkata')->toDateTimeString();
                    $seat['user_id'] = session('user_id');
                    
                    // Add to total price
                    $totalPrice += $prices[$seat['type']] ?? 0;
                }
            }
        }

        if (!empty($alreadyBooked)) {
            return response()->json([
                'status' => false, 
                'message' => 'Some seats were already booked: ' . implode(', ', $alreadyBooked)
            ], 400);
        }

        // 1. Update Movie Layout
        $movie->seating_layout = $layout;
        $movie->save();

        // 2. Create Separate Booking Record
        Booking::create([
            'user_id' => session('user_id'),
            'movie_id' => $id,
            'seats' => $selectedSeatIds,
            'attendees' => $attendees,
            'customer_email' => $attendees[0]['email'] ?? '',
            'customer_phone' => $attendees[0]['phone'] ?? '',
            'total_price' => $totalPrice,
            'booking_date' => now('Asia/Kolkata')->toDateTimeString(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Congratulations! Your group tickets have been booked.',
            'seats' => $selectedSeatIds,
            'total_price' => $totalPrice
        ]);
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['user_id', 'user_name', 'user_email']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'You have been logged out.');
    }
}
