<?php

namespace App\Http\Controllers;

use App\Models\AdminSignupModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class AdminSignupController extends Controller
{

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

    public function logout(Request $request)
    {
        // Remove admin data from session
        $request->session()->forget(['admin_id', 'admin_name', 'admin_email']);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}
