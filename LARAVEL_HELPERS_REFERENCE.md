# Laravel Helper Functions Reference

Yeh document mein sabhi commonly used Laravel helper functions ki definitions hain jo project mein use ho rahi hain.

## View/Blade Helpers

### `asset($path)`
**Definition:** Public folder se file ka URL generate karta hai.

**Example:**
```php
asset('css/dashboard.css')
// Returns: http://yoursite.com/css/dashboard.css

asset('uploads/profiles/photo.jpg')
// Returns: http://yoursite.com/uploads/profiles/photo.jpg
```

**Use Case:** CSS, JS, images ke liye URLs banane ke liye.

---

### `route($name, $parameters = [])`
**Definition:** Named route se URL generate karta hai.

**Example:**
```php
route('dashboard')
// Returns: /dashboard

route('dashboard.view-paper', 5)
// Returns: /dashboard/paper/5
```

**Use Case:** Links aur redirects ke liye.

---

### `old($key, $default = null)`
**Definition:** Previous form submission ka value return karta hai (validation fail hone par).

**Example:**
```php
<input value="{{ old('name', $user->name) }}">
// Agar form submit fail hua, to old value show karega
// Warna $user->name show karega
```

**Use Case:** Form validation fail hone par user ko dobara data type nahi karna pade.

---

### `compact($var1, $var2, ...)`
**Definition:** Variables ko array mein convert karta hai.

**Example:**
```php
$user = Auth::user();
$papers = Paper::all();
return view('dashboard', compact('user', 'papers'));
// Same as: ['user' => $user, 'papers' => $papers]
```

**Use Case:** View ko data pass karne ke liye.

---

## Database/Model Helpers

### `Model::where($column, $value)`
**Definition:** Database query filter karta hai.

**Example:**
```php
Paper::where('status', 'approved')
// Only approved papers

Paper::where('uploaded_by', $user->id)
// Only papers uploaded by this user
```

**Use Case:** Specific records dhoondhne ke liye.

---

### `->get()`
**Definition:** Query execute karke results return karta hai (Collection).

**Example:**
```php
Paper::where('status', 'approved')->get()
// Returns: Collection of Paper models
```

**Use Case:** Multiple records fetch karne ke liye.

---

### `->first()`
**Definition:** Pehla matching record return karta hai ya null.

**Example:**
```php
User::where('email', 'test@example.com')->first()
// Returns: User model or null
```

**Use Case:** Single record dhoondhne ke liye.

---

### `->firstOrFail()`
**Definition:** Pehla matching record return karta hai, warna 404 error.

**Example:**
```php
Paper::find(5)->firstOrFail()
// Returns: Paper model or throws 404
```

**Use Case:** Record must exist hona chahiye.

---

### `->find($id)`
**Definition:** Primary key se record dhoondhta hai.

**Example:**
```php
User::find(5)
// Returns: User with ID 5 or null
```

**Use Case:** ID se direct record fetch karne ke liye.

---

### `->create($data)`
**Definition:** Database mein naya record create karta hai.

**Example:**
```php
User::create([
    'name' => 'John',
    'email' => 'john@example.com',
    'password' => bcrypt('password')
])
// Creates new user and returns User model
```

**Use Case:** Naya record save karne ke liye.

---

### `->with($relation)`
**Definition:** Eager loading - related data ko ek saath load karta hai.

**Example:**
```php
Paper::with('authors')->get()
// Loads papers AND their authors in one query
// Avoids N+1 query problem
```

**Use Case:** Performance improve karne ke liye.

---

### `->orderBy($column, $direction)`
**Definition:** Results ko sort karta hai.

**Example:**
```php
Paper::orderBy('created_at', 'desc')->get()
// Newest papers first

Paper::orderBy('title', 'asc')->get()
// Alphabetically sorted
```

**Use Case:** Data ko specific order mein fetch karne ke liye.

---

## Authentication Helpers

### `Auth::user()`
**Definition:** Currently logged-in user return karta hai.

**Example:**
```php
$user = Auth::user();
echo $user->name; // User's name
```

**Use Case:** Current user ki information chahiye ho.

---

### `Auth::id()`
**Definition:** Currently logged-in user ki ID return karta hai.

**Example:**
```php
$userId = Auth::id();
// Returns: 5 (user ID)
```

**Use Case:** User ID chahiye ho (shorter than Auth::user()->id).

---

### `Auth::attempt($credentials)`
**Definition:** User ko login karne ki koshish karta hai.

**Example:**
```php
if (Auth::attempt(['email' => $email, 'password' => $password])) {
    // Login successful
}
```

**Use Case:** Login functionality ke liye.

---

### `Auth::logout()`
**Definition:** User ko logout karta hai.

**Example:**
```php
Auth::logout();
```

**Use Case:** Logout functionality ke liye.

---

## Request Helpers

### `$request->validate($rules)`
**Definition:** Form data ko validate karta hai.

**Example:**
```php
$request->validate([
    'email' => 'required|email',
    'password' => 'required|min:6'
]);
// If validation fails, automatically redirects back with errors
```

**Use Case:** Form validation ke liye.

---

### `$request->has($key)`
**Definition:** Check karta hai ke request mein key exist karti hai ya nahi.

**Example:**
```php
if ($request->has('role')) {
    // 'role' parameter exists
}
```

**Use Case:** Optional parameters check karne ke liye.

---

### `$request->file($key)`
**Definition:** Uploaded file return karta hai.

**Example:**
```php
$file = $request->file('profile_photo');
$file->move(public_path('uploads'), $filename);
```

**Use Case:** File uploads handle karne ke liye.

---

### `$request->only($keys)`
**Definition:** Request se sirf specified keys return karta hai.

**Example:**
```php
$credentials = $request->only('email', 'password');
// Returns: ['email' => '...', 'password' => '...']
```

**Use Case:** Specific fields extract karne ke liye.

---

## Path Helpers

### `public_path($path)`
**Definition:** Public folder ka full path return karta hai.

**Example:**
```php
public_path('uploads/profiles')
// Returns: C:\...\public\uploads\profiles
```

**Use Case:** Files save karne ke liye.

---

## String Helpers

### `Str::limit($string, $length)`
**Definition:** String ko specified length tak truncate karta hai.

**Example:**
```php
Str::limit('This is a long text', 10)
// Returns: "This is a..."
```

**Use Case:** Long text ko shorten karne ke liye.

---

## Date Helpers

### `->format($format)`
**Definition:** Carbon date object ko format karta hai.

**Example:**
```php
$paper->created_at->format('M d, Y')
// Returns: "Jan 15, 2024"

$paper->created_at->format('Y-m-d')
// Returns: "2024-01-15"
```

**Use Case:** Dates ko readable format mein show karne ke liye.

---

## Session Helpers

### `Session::flash($key, $value)`
**Definition:** Session mein message store karta hai (ek baar use hone ke baad auto-delete).

**Example:**
```php
Session::flash('success', 'Paper uploaded!');
// Message will show on next page, then disappear
```

**Use Case:** Success/error messages ke liye.

---

### `redirect()->with($key, $value)`
**Definition:** Redirect karte waqt message store karta hai.

**Example:**
```php
return redirect()->route('dashboard')->with('success', 'Saved!');
```

**Use Case:** Redirect ke saath message bhejne ke liye.

---

## Collection Methods

### `->count()`
**Definition:** Collection mein items ki count return karta hai.

**Example:**
```php
$papers->count()
// Returns: 10 (number of papers)
```

---

### `->where($key, $value)`
**Definition:** Collection ko filter karta hai.

**Example:**
```php
$papers->where('status', 'approved')
// Returns: Only approved papers
```

**Use Case:** Collection ko filter karne ke liye.

---

## Relationship Methods

### `->attach($id)`
**Definition:** Many-to-many relationship mein link create karta hai.

**Example:**
```php
$paper->categories()->attach(3);
// Links paper to category ID 3
```

**Use Case:** Pivot table mein entry create karne ke liye.

---

### `->sync($ids)`
**Definition:** Many-to-many relationship ko replace karta hai.

**Example:**
```php
$paper->categories()->sync([3, 5]);
// Removes old categories, adds categories 3 and 5
```

**Use Case:** Relationships update karne ke liye.

---

## Security Helpers

### `bcrypt($password)`
**Definition:** Password ko hash karta hai (one-way encryption).

**Example:**
```php
$hashed = bcrypt('mypassword');
// Returns: $2y$10$... (hashed password)
```

**Use Case:** Passwords ko securely store karne ke liye.

---

### `@csrf`
**Definition:** CSRF token generate karta hai (Blade directive).

**Example:**
```blade
<form method="POST">
    @csrf
    <!-- Hidden CSRF token field -->
</form>
```

**Use Case:** Form security ke liye.

---

## Summary

Yeh sabhi helpers Laravel mein built-in hain aur commonly use hote hain. Inhe yaad rakhne se code likhna aur samajhna asaan ho jata hai!

