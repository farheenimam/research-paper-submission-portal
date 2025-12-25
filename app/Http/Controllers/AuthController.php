<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

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
            $selectedRole = $roles->where('name', $request->role)->first();
        }
        
        return view('auth.register', compact('roles', 'defaultRole', 'selectedRole'));
    }

    function adminView(){
        return view('Admin.admin_home');
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

        // Predefined admin credentials
        $adminCredentials = [
            'email' => 'admin@gmail.com',
            'password' => 'admin.123'
        ];

        // Check for admin credentials
        if ($request->email === $adminCredentials['email'] && $request->password === $adminCredentials['password']) {
            Auth::loginUsingId(1); // Assuming the admin user ID is 1
            
            // Set admin session data
            Session::put('user_role', 'admin');
            Session::put('user_name', 'Administrator');
            Session::flash('success', 'Welcome back, Administrator!');
            
            return redirect()->route('admin.home');
        }

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
            
            // Redirect reviewers (role_id 3) to articles page, others to welcome
            if ($user->role_id == 3) {
                return redirect()->route('reviewer.articles');
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

    /**
     * Check if user is logged in via session
     */
    public function checkSession()
    {
        if (!Session::has('user_id') && !Auth::check()) {
            return redirect(route('login'))->with('error', 'Please login to continue.');
        }
        return true;
    }

    /**
     * Get current user session data
     */
    public function getSessionData()
    {
        return [
            'user_id' => Session::get('user_id'),
            'user_name' => Session::get('user_name'),
            'user_email' => Session::get('user_email'),
            'user_role_id' => Session::get('user_role_id'),
            'login_time' => Session::get('login_time'),
        ];
    }


}