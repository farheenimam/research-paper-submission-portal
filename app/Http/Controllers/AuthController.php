<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    function login(){
        return view('auth.login');
    }

    function registration(Request $request){
        $roles = \App\Models\Role::all();
        // Find the 'user' role to set as default
        $defaultRole = $roles->where('name', 'user')->first();
        
        // Check if role is specified in URL parameter
        $selectedRole = null;
        if ($request->has('role')) {
            $roleName = strtolower(trim($request->role));
            
            // Try to find by name (case-insensitive)
            $selectedRole = $roles->first(function($role) use ($roleName) {
                return strtolower($role->name) === $roleName;
            });
            
            // If not found by name, try to find by ID
            if (!$selectedRole) {
                if ($roleName === 'reviewer') {
                    $selectedRole = \App\Models\Role::find(3);
                } elseif ($roleName === 'reader') {
                    $selectedRole = \App\Models\Role::find(4);
                } elseif ($roleName === 'researcher') {
                    // Try to find researcher by name or common ID
                    $selectedRole = $roles->first(function($role) {
                        $selectedRole = \App\Models\Role::find(2);
                    });
                }
            }
        }
        
        return view('auth.register', compact('roles', 'defaultRole', 'selectedRole'));
    }

    function registrationPost(Request $request){
       // Check if role is pre-selected (from URL parameter)
       $preSelectedRole = null;
       if ($request->has('role_id') && !empty($request->role_id)) {
           $preSelectedRole = \App\Models\Role::find($request->role_id);
       }
       
       $validationRules = [
          'name' => 'required|string|max:150',
          'email' => 'required|email|max:150|unique:users',
          'password' => 'required|min:6',
          'affiliation' => 'nullable|string|max:255',
          'bio' => 'nullable|string',
          'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
       ];
       
       // Only validate role_id if it's not pre-selected
       if (!$preSelectedRole) {
           $validationRules['role_id'] = 'nullable|exists:roles,id';
       }
       
       $request->validate($validationRules);

       // Set role ID - use pre-selected role or determine default
       $roleId = null;
       if ($preSelectedRole) {
           // Force use the pre-selected role ID (cannot be changed)
           $roleId = $preSelectedRole->id;
       } else {
           // Set default role to 'user' if not provided
           $defaultRole = \App\Models\Role::where('name', 'user')->first();
           $roleId = $request->role_id ?: ($defaultRole ? $defaultRole->id : 2);
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
            
            // Set user session data
            Session::put('user_id', $user->id);
            Session::put('user_name', $user->name);
            Session::put('user_email', $user->email);
            Session::put('user_role_id', $user->role_id);
            Session::put('login_time', now());
            
            // Regenerate session ID for security
            $request->session()->regenerate();
            
            Session::flash('success', 'Welcome back, ' . $user->name . '!');
            
            // Hardcoded admin email redirect
            if ($user->email === 'farheenimam@gmail.com') {
                return redirect()->route('admin.dashboard');
            }
            
            // Redirect based on role
            if ($user->role_id == 3) {
                // Reviewers go to articles page
                return redirect()->route('reviewer.articles');
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
        // Get user name before clearing session
        $userName = Session::get('user_name', 'User');
        
        // Clear all session data
        Session::flush();
        
        // Logout the user
        Auth::logout();
        
        // Invalidate the session
        request()->session()->invalidate();
        
        // Regenerate CSRF token
        request()->session()->regenerateToken();
        
        Session::flash('success', 'Goodbye ' . $userName . '! You have been logged out successfully.');
        return redirect(route('login'));
    }
}