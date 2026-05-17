<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\AdminSignupModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query();

        if ($request->has('date') && !empty($request->date)) {
            $startOfDay = \Carbon\Carbon::parse($request->date)->startOfDay()->format('Y-m-d\TH:i');
            $endOfDay = \Carbon\Carbon::parse($request->date)->endOfDay()->format('Y-m-d\TH:i');
            
            $query->where('start_time', '<=', $endOfDay)
                  ->where('end_time', '>=', $startOfDay);
        }

        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'date_desc':
                    $query->orderBy('event_date', 'desc');
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

        $events = $query->get();
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        $adminId = session('admin_id');
        $admin = AdminSignupModel::find($adminId);
        $masterLayout = $admin->event_seating_layout ?? null;
        
        return view('admin.events.add', compact('masterLayout'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'category' => 'required|array',
            'venue' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'duration' => 'required|numeric',
            'event_date' => 'required|date',
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
                    'folder' => 'event_posters'
                ]);

                if ($upload && isset($upload['secure_url'])) {
                    $data['poster'] = $upload['secure_url'];
                } else {
                    throw new \Exception('Cloudinary upload failed: secure_url not returned.');
                }
            }

            if ($request->has('seating_layout') && !empty($request->seating_layout)) {
                $layout = is_string($request->seating_layout) ? json_decode($request->seating_layout, true) : $request->seating_layout;
                
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
                $layout = $admin->event_seating_layout;
                
                if (isset($layout['layout'])) {
                    foreach ($layout['layout'] as &$row) {
                        foreach ($row as &$seat) {
                            $seat['status'] = 'available';
                        }
                    }
                }
                $data['seating_layout'] = $layout;
            }

            Event::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Event added successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Event Add Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while adding the event.'
            ], 500);
        }
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        
        $event->category = is_string($event->category) ? json_decode($event->category, true) ?? [$event->category] : (is_array($event->category) ? $event->category : []);
        
        $adminId = session('admin_id');
        $admin = AdminSignupModel::find($adminId);
        $masterLayout = $admin->event_seating_layout ?? null;

        return view('admin.events.edit', compact('event', 'masterLayout'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'category' => 'required|array',
            'venue' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'duration' => 'required|numeric',
            'event_date' => 'required|date',
            'description' => 'required|string',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,webp',
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
            $event = Event::findOrFail($id);
            $data = $request->except(['poster']);

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
                    'folder' => 'event_posters'
                ]);

                if ($upload && isset($upload['secure_url'])) {
                    $data['poster'] = $upload['secure_url'];
                } else {
                    throw new \Exception('Cloudinary upload failed: secure_url not returned.');
                }
            }

            if ($request->has('seating_layout') && !empty($request->seating_layout)) {
                $layout = is_string($request->seating_layout) ? json_decode($request->seating_layout, true) : $request->seating_layout;
                
                if (isset($layout['layout'])) {
                    foreach ($layout['layout'] as &$row) {
                        foreach ($row as &$seat) {
                            $seat['status'] = 'available';
                        }
                    }
                }
                $data['seating_layout'] = $layout;
            }

            $event->update($data);

            return response()->json([
                'status' => true,
                'message' => 'Event updated successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Event Update Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while updating the event.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $event = Event::findOrFail($id);
            $event->delete();

            return response()->json([
                'status' => true,
                'message' => 'Event deleted successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Event Delete Error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete the event.'
            ], 500);
        }
    }
}
