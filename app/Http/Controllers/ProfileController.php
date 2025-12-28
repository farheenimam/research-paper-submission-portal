<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function update(Request $request, $id = null)
    {
        $currentUser = Auth::user();
        
        // If ID is provided and current user is admin, update that user
        if ($id && $currentUser->role->name === 'admin') {
            $user = User::findOrFail($id);
        } else {
            // Otherwise, user updating own profile
            $user = $currentUser;
        }
        $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users,email,' . $user->id,
            'affiliation' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'affiliation' => $request->affiliation,
            'bio' => $request->bio,
        ];

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old profile photo if exists
            if ($user->profile_photo && file_exists(public_path($user->profile_photo))) {
                unlink(public_path($user->profile_photo));
            }

            $profilePhoto = $request->file('profile_photo');
            $filename = time() . '_' . $profilePhoto->getClientOriginalName();
            
            // Ensure the directory exists
            if (!file_exists(public_path('uploads/profiles'))) { // 0755 is the permission for the directory
                mkdir(public_path('uploads/profiles'), 0755, true);
            }
//             0 → octal number (permission format)
// 7 (Owner) → read + write + execute
// 5 (Group) → read + execute
// 5 (Others) → read + execute
            $profilePhoto->move(public_path('uploads/profiles'), $filename);
            $updateData['profile_photo'] = 'uploads/profiles/' . $filename;
        }

        // Update user using Eloquent update method
        User::where('id', $user->id)->update($updateData);

        Session::flash('success', 'Profile updated successfully!');
        
        // Redirect based on context
        if ($id && $currentUser->role->name === 'admin') {
            return redirect()->route('admin.view-user', $user->id);
        } else {
            return redirect()->route('profile');
        }
    }

    public function removePhoto()
    {
        $user = Auth::user();
        
        // Check if user has a profile photo and if the file exists on server
        // $user->profile_photo - Contains relative path like "uploads/profiles/photo.jpg"
        // public_path($user->profile_photo) - Converts relative path to full server path
        // Example: "uploads/profiles/photo.jpg" => "C:\...\public\uploads\profiles\photo.jpg"
        // file_exists() - Checks if the file actually exists on the server
        if ($user->profile_photo && file_exists(public_path($user->profile_photo))) {
            // unlink() - Deletes the file from server
            // public_path($user->profile_photo) - Full path to the file that needs to be deleted
            unlink(public_path($user->profile_photo));
        }

        // Update user using Eloquent update method
        User::where('id', $user->id)->update(['profile_photo' => null]);

        Session::flash('success', 'Profile photo removed successfully!');
        return redirect()->route('profile');
    }
}