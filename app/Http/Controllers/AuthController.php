<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    function login(){
        return view('auth.login');
    }

    function registration(Request $request){
        $roles = Role::all();
        // Find the 'user' role to set as default
        $defaultRole = Role::where('name', 'reader')->first();
        
        // Check if role is specified in URL parameter
        $selectedRole = null;
        if ($request->has('role')) {
            $roleName = strtolower(trim($request->role));
            
            if ($roleName === 'reader') {
                $selectedRole = Role::find(4);
            } elseif ($roleName === 'researcher') {
                // Find researcher by ID 2
                $selectedRole = Role::find(2);
            }
        }
        
        return view('auth.register', compact('roles', 'defaultRole', 'selectedRole'));
    }

    function registrationPost(Request $request){
       // Check if role is pre-selected (from URL parameter)
       $preSelectedRole = null;
       if ($request->has('role_id') && !empty($request->role_id)) {
           $preSelectedRole = Role::find($request->role_id);
       }
       
       $validationRules = [
          'name' => 'required|string|max:150',
          'email' => 'required|email|max:150|unique:users',
          'password' => 'required|min:6',
          'affiliation' => 'nullable|string|max:255',
          'bio' => 'nullable|string',
          'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
       ];
       
       // Set role ID - use pre-selected role or determine default
       $roleId = null;
       if ($preSelectedRole) {
           // Force use the pre-selected role ID (cannot be changed)
           $roleId = $preSelectedRole->id;
       } else {
           // Use role_id from form if provided and valid, otherwise default to 4 (Reader)
           if ($request->has('role_id') && $request->role_id) {
               // Validate that the role exists
               $requestedRole = Role::find($request->role_id);
               $roleId = $requestedRole ? $requestedRole->id : 4; // Default to 4 if invalid
           } else {
               // No role_id provided, explicitly use 4 (Reader) as default
               $roleId = 4;
           }
       }

       $data = [
           'name' => $request->name,
           'email' => $request->email,
           'password' => bcrypt($request->password),
           'role_id' => $roleId,
           'affiliation' => $request->affiliation,
           'bio' => $request->bio,
       ];

       // Handle profile photo upload
       if ($request->hasFile('profile_photo')) {
           $profilePhoto = $request->file('profile_photo');
           $filename = time() . '_' . $profilePhoto->getClientOriginalName();
           $profilePhoto->move(public_path('uploads/profiles'), $filename);
           $data['profile_photo'] = 'uploads/profiles/' . $filename;
       }

       $user = User::create($data);
       if (!$user) {
           return redirect(route('registration'))->with("error","Registration failed");
       }

       // Set success message in session
       Session::flash('success', 'Registration successful! Please login.');
       return redirect(route('login'));
    }

    function loginPost(Request $request){
        $request->validate([
           'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Regenerate session ID for security
            $request->session()->regenerate();
            
            Session::flash('success', 'Welcome back, ' . $user->name . '!');
            
            // Hardcoded admin email redirect
            if ($user->email === 'farheenimam@gmail.com') {
                return redirect()->route('admin.dashboard');
            }
            
            // Redirect based on role
            if ($user->role_id == 2) {
                // Researchers go to dashboard
                return redirect()->route('dashboard');
            } elseif ($user->role_id == 4) {
                // Readers go to search page
                return redirect()->route('search');
            }
            
            return redirect()->route('welcome');
        }
        
        Session::flash('error', 'Invalid email or password. Please try again.');
        return redirect(route('login'));
    }

    function logout(){
        try {
            // Logout the user (this also clears auth session)
            Auth::logout();
            
            // Invalidate the session
            request()->session()->invalidate();
            
            // Regenerate CSRF token
            request()->session()->regenerateToken();
            
            return redirect(route('login'))->with('success', 'You have been logged out successfully.');
        } catch (\Exception $e) {
            // If session is already expired, just redirect to login
            return redirect(route('login'))->with('info', 'You have been logged out.');
        }
    }
}