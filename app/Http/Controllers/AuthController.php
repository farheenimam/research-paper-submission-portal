<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Show login page
     * 
     * @return \Illuminate\View\View
     */
    function login(){
        // view() - Returns Blade view file
        // 'auth.login' - Path: resources/views/auth/login.blade.php
        return view('auth.login');
    }

    /**
     * Show registration form
     * 
     * @param Request $request - May contain 'role' parameter from URL (?role=reader)
     * @return \Illuminate\View\View
     */
    function registration(Request $request){
        // Role::all() - Get all roles from database
        // Returns Collection of Role models
        $roles = Role::all();
        
        // Role::where('name', 'reader')->first() - Find role where name is 'reader'
        // ->first() - Get first matching result or null
        $defaultRole = Role::where('name', 'reader')->first();
        
        // Check if role is specified in URL parameter (e.g., ?role=reader)
        $selectedRole = null;
        // $request->has('role') - Check if 'role' parameter exists in request
        if ($request->has('role')) {
            // strtolower() - Convert to lowercase
            // trim() - Remove whitespace from start/end
            $roleName = strtolower(trim($request->role));
            
            // Role::find(4) - Find role by ID (faster than where())
            if ($roleName === 'reader') {
                $selectedRole = Role::find(4);
            } elseif ($roleName === 'researcher') {
                $selectedRole = Role::find(2);
            }
        }
        
        // compact() - Creates array from variable names
        // ['roles' => $roles, 'defaultRole' => $defaultRole, 'selectedRole' => $selectedRole]
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

       // Prepare data array for user creation
       // bcrypt() - Laravel helper: Hashes password securely (one-way encryption)
       // Never store plain passwords in database!
       $data = [
           'name' => $request->name,                    // From form
           'email' => $request->email,                 // From form
           'password' => bcrypt($request->password),  // Hash password before saving
           'role_id' => $roleId,                       // Determined above
           'affiliation' => $request->affiliation,     // Optional field
           'bio' => $request->bio,                     // Optional field
       ];

       // Handle profile photo upload
       // $request->hasFile('profile_photo') - Check if file was uploaded
       if ($request->hasFile('profile_photo')) {
           // $request->file('profile_photo') - Get uploaded file
           $profilePhoto = $request->file('profile_photo');
           
           // time() - Current timestamp for unique filename
           // getClientOriginalName() - Original filename from user
           $filename = time() . '_' . $profilePhoto->getClientOriginalName();
           
           // public_path('uploads/profiles') - Full path to public/uploads/profiles
           // move() - Move uploaded file to destination folder
           $profilePhoto->move(public_path('uploads/profiles'), $filename);
           
           // Store relative path in database (not full path)
           $data['profile_photo'] = 'uploads/profiles/' . $filename;
       }

       // User::create($data) - Create new user in database
       // Returns User model instance if successful, false if fails
       $user = User::create($data);
       
       // Check if user creation failed
       if (!$user) {
           // redirect()->with() - Redirect with flash message
           // Message stored in session, shown on next page
           return redirect(route('registration'))->with("error","Registration failed");
       }

       // Session::flash() - Store success message in session
       // Message will be shown on login page
       Session::flash('success', 'Registration successful! Please login.');
       
       // redirect(route('login')) - Redirect to login page
       return redirect(route('login'));
    }

    function loginPost(Request $request){
        $request->validate([
           'email' => 'required|email',
            'password' => 'required',
        ]);

        // $request->only('email', 'password') - Get only these fields from request
        // Returns array: ['email' => 'user@example.com', 'password' => 'password123']
        $credentials = $request->only('email', 'password');

        // Auth::attempt($credentials) - Try to login user
        // Checks email/password against database
        // Returns true if successful, false if failed
        if (Auth::attempt($credentials)) {
            // Auth::user() - Get currently logged-in user object
            $user = Auth::user();
            
            // Regenerate session ID for security (prevents session hijacking)
            $request->session()->regenerate();
            
            // Store welcome message in session
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