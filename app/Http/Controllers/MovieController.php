<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\AdminSignupModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $query = Movie::query();

        // Filter by specific date (check if date falls between start_time and end_time)
        if ($request->has('date') && !empty($request->date)) {
            $startOfDay = \Carbon\Carbon::parse($request->date)->startOfDay()->format('Y-m-d\TH:i');
            $endOfDay = \Carbon\Carbon::parse($request->date)->endOfDay()->format('Y-m-d\TH:i');
            
            $query->where('start_time', '<=', $endOfDay)
                  ->where('end_time', '>=', $startOfDay);
        }

        // Sort options
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'release_desc':
                    $query->orderBy('release_date', 'desc');
                    break;
                case 'shows_asc':
                    $query->orderBy('start_time', 'asc');
                    break;
                case 'latest':
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $movies = $query->get();
        return view('admin.movies.index', compact('movies'));
    }

    public function create()
    {
        $adminId = session('admin_id');
        $admin = AdminSignupModel::find($adminId);
        $masterLayout = $admin->seating_layout ?? null;
        
        return view('admin.movies.add', compact('masterLayout'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'category' => 'required|array',
            'genre' => 'required|array',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'duration' => 'required|numeric',
            'release_date' => 'required|date',
            'language' => 'required|string',
            'description' => 'required|string',
            'poster' => 'required|image|mimes:jpeg,png,jpg,webp',
            'price_normal' => 'required|numeric|min:0',
            'price_premium' => 'required|numeric|min:0',
            'price_vip' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        try {
            $data = $request->except(['poster']);

            // Handle Poster Upload to Cloudinary using Manual Engine (Laravel 12 workaround)
            if ($request->hasFile('poster')) {
                $file = $request->file('poster');

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
                    'folder' => 'movie_posters'
                ]);

                if ($upload && isset($upload['secure_url'])) {
                    $data['poster'] = $upload['secure_url'];
                } else {
                    throw new \Exception('Cloudinary upload failed: secure_url not returned.');
                }
            }

            // Add seating layout if provided, otherwise use master layout
            if ($request->has('seating_layout') && !empty($request->seating_layout)) {
                $layout = is_string($request->seating_layout) ? json_decode($request->seating_layout, true) : $request->seating_layout;
                
                // Ensure every seat has status 'available'
                if (isset($layout['layout'])) {
                    foreach ($layout['layout'] as &$row) {
                        foreach ($row as &$seat) {
                            $seat['status'] = 'available';
                        }
                    }
                }
                $data['seating_layout'] = $layout;
            } else {
                $admin = AdminSignupModel::find(session('admin_id'));
                $layout = $admin->seating_layout;
                
                // Ensure every seat has status 'available'
                if (isset($layout['layout'])) {
                    foreach ($layout['layout'] as &$row) {
                        foreach ($row as &$seat) {
                            $seat['status'] = 'available';
                        }
                    }
                }
                $data['seating_layout'] = $layout;
            }

            // Save Movie to Database
            Movie::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Movie added successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Movie Add Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while adding the movie.'
            ], 500);
        }
    }

    public function edit($id)
    {
        $movie = Movie::findOrFail($id);
        
        // Ensure category and genre are arrays for Select2
        $movie->category = is_string($movie->category) ? json_decode($movie->category, true) ?? [$movie->category] : (is_array($movie->category) ? $movie->category : []);
        $movie->genre = is_string($movie->genre) ? json_decode($movie->genre, true) ?? [$movie->genre] : (is_array($movie->genre) ? $movie->genre : []);
        
        $adminId = session('admin_id');
        $admin = AdminSignupModel::find($adminId);
        $masterLayout = $admin->seating_layout ?? null;

        return view('admin.movies.edit', compact('movie', 'masterLayout'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'category' => 'required|array',
            'genre' => 'required|array',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'duration' => 'required|numeric',
            'release_date' => 'required|date',
            'language' => 'required|string',
            'description' => 'required|string',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,webp', // Poster is optional on edit
            'price_normal' => 'required|numeric|min:0',
            'price_premium' => 'required|numeric|min:0',
            'price_vip' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        try {
            $movie = Movie::findOrFail($id);
            $data = $request->except(['poster']);

            // Handle Poster Upload if a new file is provided
            if ($request->hasFile('poster')) {
                $file = $request->file('poster');

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
                    'folder' => 'movie_posters'
                ]);

                if ($upload && isset($upload['secure_url'])) {
                    $data['poster'] = $upload['secure_url'];
                } else {
                    throw new \Exception('Cloudinary upload failed: secure_url not returned.');
                }
            }

            // Handle seating_layout update
            if ($request->has('seating_layout') && !empty($request->seating_layout)) {
                $layout = is_string($request->seating_layout) ? json_decode($request->seating_layout, true) : $request->seating_layout;
                
                // Ensure every seat has status 'available'
                if (isset($layout['layout'])) {
                    foreach ($layout['layout'] as &$row) {
                        foreach ($row as &$seat) {
                            $seat['status'] = 'available';
                        }
                    }
                }
                $data['seating_layout'] = $layout;
            }

            // Update Movie
            $movie->update($data);

            return response()->json([
                'status' => true,
                'message' => 'Movie updated successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Movie Update Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while updating the movie.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $movie = Movie::findOrFail($id);
            $movie->delete();

            return response()->json([
                'status' => true,
                'message' => 'Movie deleted successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Movie Delete Error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete the movie.'
            ], 500);
        }
    }
}
