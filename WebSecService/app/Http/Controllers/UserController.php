<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Http\Controllers\Controller;
use App\Mail\TempPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use App\Mail\VerificationEmail;
use Carbon\Carbon;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    use ValidatesRequests;

    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $users = $query->paginate(10);
        return view('Users.index', compact('users'));
    }

    public function create()
    {
        $roles = \Spatie\Permission\Models\Role::all();
        $permissions = \Spatie\Permission\Models\Permission::all();

        // Get permissions for each role
        $rolePermissions = [];
        foreach ($roles as $role) {
            $rolePermissions[$role->id] = $role->permissions->pluck('id')->toArray();
        }

        return view('Users.create', compact('roles', 'permissions', 'rolePermissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|exists:roles,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
        ]);

        // Assign role
        $role = \Spatie\Permission\Models\Role::findById($request->role);
        $user->assignRole($role);

        // Get the permissions that already come with the role
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        // Assign additional permissions (exclude role permissions to avoid duplication)
        if ($request->has('permissions')) {
            $additionalPermissions = array_diff($request->permissions, $rolePermissions);

            foreach ($additionalPermissions as $permissionId) {
                $permission = \Spatie\Permission\Models\Permission::findById($permissionId);
                $user->givePermissionTo($permission);
            }
        }

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|exists:roles,id',
        ]);

        $data = $request->only(['name', 'email', 'phone']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Update role
        $role = \Spatie\Permission\Models\Role::findById($request->role);
        $user->syncRoles([$role]);

        // Get the permissions that already come with the role
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        // Determine additional permissions (only those not included in the role)
        $permissionsToSync = [];
        if ($request->has('permissions')) {
            $permissionsToSync = array_diff($request->permissions, $rolePermissions);
        }

        // Sync only the additional permissions
        $user->syncPermissions($permissionsToSync);

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }

    public function register(Request $request)
    {
        return view('Users.register');
    }

    public function doRegister(Request $request)
    {
        // \Log::info('doRegister called with data: ', $request->all());

        $validated = $this->validate($request, [
            'name' => ['required', 'string', 'min:5'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            'security_question' => ['required', 'string', 'max:255'],
            'security_answer' => ['required', 'string', 'max:255'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['security_answer'] = Hash::make($validated['security_answer']);

        $user = User::create($validated);

        // \Log::info('User saved with ID: ' . $user->id);
        // \Log::info('Security Question after save: ' . $user->security_question);
        // \Log::info('Security Answer after save: ' . $user->security_answer);

        // Send verification email
        $title = "Verification Link";
        $token = Crypt::encryptString(json_encode(['id' => $user->id, 'email' => $user->email]));
        $link = route("verify", ['token' => $token]);
        Mail::to($user->email)->send(new VerificationEmail($link, $user->name));



        return redirect("/")->with('success', 'Registration successful. Please log in.');
    }

    public function login(Request $request)
    {
        return view('Users.login');
    }

    public function doLogin(Request $request)
    {
        $request->validate([
            'login_id' => 'required|string', // Changed from 'email' to 'login_id' for both email and phone login
            'password' => 'required|string',
        ]);

        // First, try to find a user by email
        $user = User::where('email', $request->login_id)->first();

        // If no user found by email, try to find by phone number
        if (!$user) {
            $phone = $request->login_id;
            // Format phone number if needed
            if (substr($phone, 0, 1) !== '+') {
                $phone = '+' . $phone;
            }
            $user = User::where('phone', $phone)->first();
        }

        if (!$user) {
            return redirect()->back()
                ->withInput($request->only('login_id'))
                ->withErrors('Invalid login information.');
        }

        // First check if email is verified for non-temporary password login
        if (!$user->email_verified_at && Hash::check($request->password, $user->password)) {
            // Generate verification token
            $token = Crypt::encryptString(json_encode(['id' => $user->id, 'email' => $user->email]));

            // Send a new verification email
            $link = route("verify", ['token' => $token]);
            Mail::to($user->email)->send(new VerificationEmail($link, $user->name));

            // Redirect with a helpful message
            return redirect()->route('login')
                ->withInput($request->only('login_id'))
                ->with('success', 'A new verification email has been sent to your email address. Please check your inbox and verify your email before logging in.');
        }

        // Check if the provided password matches the temporary password
        if ($user->temp_password && !$user->temp_password_used && $user->temp_password_expires_at >= now() && Hash::check($request->password, $user->temp_password)) {
            Auth::login($user);
            // Mark the temporary password as used
            $user->temp_password_used = true;
            $user->temp_password = null;
            $user->temp_password_expires_at = null;
            $user->save();

            // Check if the email is verified
            if (!$user->email_verified_at) {
                // Generate verification token
                $token = Crypt::encryptString(json_encode(['id' => $user->id, 'email' => $user->email]));

                // Send verification email
                $link = route("verify", ['token' => $token]);
                Mail::to($user->email)->send(new VerificationEmail($link, $user->name));

                return redirect()->route('verify', ['token' => $token]);
            }

            // Redirect to change password page
            return redirect()->route('password.reset')->with('status', 'Please set a new password.');
        } elseif ($user->temp_password && $user->temp_password_expires_at < now()) {
            return redirect()->back()->withInput($request->input())->withErrors('The temporary password has expired. Please request a new one.');
        }

        // Regular login with permanent password
        if (Auth::attempt(['email' => $user->email, 'password' => $request->password])) {
            // Check if email is verified before allowing login
            if (!Auth::user()->email_verified_at) {
                Auth::logout(); // Log them out
                // This shouldn't actually happen since we check earlier, but just in case
                // Generate verification token
                $token = Crypt::encryptString(json_encode(['id' => $user->id, 'email' => $user->email]));
                return redirect()->route('verify', ['token' => $token]);
            }

            // Check if phone verification is needed (only for phone login)
            if ($request->login_id == $user->phone && !$user->hasVerifiedPhone()) {
                return redirect()->route('phone.verify')
                    ->with('warning', 'Please verify your phone number to complete the login process.');
            }

            return redirect("/");
        }

        return redirect()->back()->withInput($request->input())->withErrors('Invalid login information.');
    }

    public function verify(Request $request)
    {
        if (!$request->has('token')) {
            // If no token is provided, send a new verification email
            if (Auth::check()) {
                $user = Auth::user();
                $token = Crypt::encryptString(json_encode(['id' => $user->id, 'email' => $user->email]));
                $link = route("verify", ['token' => $token]);
                Mail::to($user->email)->send(new VerificationEmail($link, $user->name));

                return redirect()->route('login')
                    ->with('success', 'A verification email has been sent to your email address. Please check your inbox (and spam folder) and click on the verification link.');
            } else {
                return redirect()->route('login')
                    ->withErrors('Please log in to verify your email.');
            }
        }

        $decryptedData = json_decode(Crypt::decryptString($request->token), true);
        $user = User::find($decryptedData['id']);
        if (!$user)
            abort(401);
        $user->email_verified_at = Carbon::now();
        $user->save();
        return view('Users.verified', compact('user'));
    }

    public function doLogout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }

    public function profile(User $user = null)
    {
        $user = $user ?? Auth::user();
        return view('Users.profile', compact('user'));
    }

    public function updatePassword(Request $request, User $user = null)
    {
        $user = $user ?? Auth::user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password updated successfully');
    }

    public function edit(User $user)
    {
        $roles = \Spatie\Permission\Models\Role::all();
        $permissions = \Spatie\Permission\Models\Permission::all();
        $userPermissions = $user->permissions->pluck('id')->toArray();

        // Get permissions for each role
        $rolePermissions = [];
        foreach ($roles as $role) {
            $rolePermissions[$role->id] = $role->permissions->pluck('id')->toArray();
        }

        // Get the user's current role
        $currentRoleId = $user->roles->first()->id ?? null;

        // Determine direct permissions (those not inherited from the role)
        $directPermissions = $userPermissions;
        if ($currentRoleId) {
            $rolePerms = $rolePermissions[$currentRoleId] ?? [];
            $directPermissions = array_diff($userPermissions, $rolePerms);
        }

        return view('Users.edit', compact('user', 'roles', 'permissions', 'userPermissions', 'rolePermissions', 'directPermissions', 'currentRoleId'));
    }

    public function showForgotPasswordForm()
    {
        return view('Users.forgot_password');
    }


    public function verifySecurityQuestion(Request $request)
    {
        Log::info('verifySecurityQuestion started', ['email' => $request->email]);

        $request->validate([
            'email' => 'required|email',
        ]);
        Log::info('Validation passed');

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            Log::info('User not found', ['email' => $request->email]);
            return back()->withErrors(['email' => 'No user found with this email.']);
        }
        Log::info('User found', ['user_id' => $user->id]);

        // Generate a temporary password
        $tempPassword = Str::random(12);
        $user->temp_password = Hash::make($tempPassword);
        $user->temp_password_used = false;
        $user->temp_password_expires_at = now()->addMinutes(15);
        $user->save();
        Log::info('Temporary password generated and saved', ['temp_password' => $tempPassword]);

        // Send the temporary password via email
        Mail::to($user->email)->send(new TempPasswordMail($tempPassword));
        Log::info('Email sent', ['to' => $user->email]);

        // Log the session data before redirect
        session(['status' => 'A temporary password has been sent to your email. It will expire in 15 minutes. Please check your inbox (or spam folder) and log in with the temporary password.']);
        Log::info('Session data set', ['status' => session('status')]);

        Log::info('Redirecting to login page');
        return redirect()->route('login');
    }

    public function showResetPasswordForm()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['error' => 'Please log in to reset your password.']);
        }
        return view('Users.reset_password');
    }

    public function resetPassword(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['error' => 'Please log in to reset your password.']);
        }

        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
        ]);

        try {
            $user = Auth::user();
            $user->password = Hash::make($request->password);
            $user->save();

            Auth::logout();
            return redirect()->route('login')
                ->with('success', 'Your password has been set successfully. Please log in with your new password.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while setting your password. Please try again.']);
        }
    }

    /**
     * Add credit to user account
     */
    public function addCredit(Request $request)
    {
        // Check if current user has permission to add credit
        if (!auth()->user()->can('manage_customer_credit')) {
            return back()->with('error', 'You do not have permission to add credit');
        }

        // Validate request
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        // Find the user
        $user = User::findOrFail($validated['user_id']);

        // Add credit to user account
        $user->credit += $validated['amount'];
        $user->save();

        // Log the credit addition using Laravel's built-in logging
        Log::info('Credit added', [
            'user_id' => auth()->id(),
            'action' => 'add_credit',
            'description' => 'Added $' . number_format($validated['amount'], 2) . ' credit to ' . $user->name,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('users.index')->with('success', 'Credit added successfully');
    }

    public function redirectToGoogle()
    {
        // Log the redirect URI that Socialite will use
        $redirectUrl = config('services.google.redirect');
        Log::info('Google redirect URL', ['url' => $redirectUrl]);
        
        
        // Redirect user to Google OAuth page
        // Note: Using websecservice.localhost.com instead of 127.0.0.1 for redirect URL
        return Socialite::driver('google')
            ->redirectUrl('http://websecservice.localhost.com/auth/google/callback')
            ->redirect();
        }

    public function handleGoogleCallback()
    {
        try {
            /**
             * Retrieves user information from Google OAuth service.
             * 
             * Uses Laravel Socialite with the configured redirect URL for Google authentication.
             * Note: Updated redirect URL from 127.0.0.1 to websecservice.localhost.com
             */
            $googleUser = Socialite::driver('google')
                ->redirectUrl('http://websecservice.localhost.com/auth/google/callback')
                ->user();
            
            // First check if a user with this email already exists
            $existingUser = User::where('email', $googleUser->email)->first();
            
            if ($existingUser) {
                // For existing users, check if their email is verified
                if (!$existingUser->email_verified_at) {
                    $existingUser->email_verified_at = now();
                }
                
                // If the user exists but doesn't have a google_id yet, update it
                if (empty($existingUser->google_id)) {
                    $existingUser->google_id = $googleUser->id;
                    $existingUser->google_token = $googleUser->token;
                    $existingUser->google_refresh_token = $googleUser->refreshToken;
                }
                
                $existingUser->save();
                $user = $existingUser;
            } else {
                // If no existing user, create a new one
                $user = new User();
                $user->name = $googleUser->name;
                $user->email = $googleUser->email;
                $user->google_id = $googleUser->id;
                $user->google_token = $googleUser->token;
                $user->google_refresh_token = $googleUser->refreshToken;
                $user->password = Hash::make(Str::random(16)); // Generate random password
                $user->email_verified_at = now(); // Consider new Google users' emails verified
                $user->save();
            }
            
            // Ensure user has Customer role
            if (!$user->roles()->exists()) {
                $customerRole = Role::firstOrCreate(['name' => 'Customer']);
                $user->assignRole($customerRole);
            }
            
            // Set session flag to prevent certificate login
            session(['oauth_login' => true]);
            session(['socialite_provider' => 'google']);
            
            Auth::login($user);
            return redirect('/');
        } catch (\Exception $e) {
            Log::error('Google login failed', [
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine()
            ]);
            
            return redirect('/login')->with('error', 'Google login failed: ' . $e->getMessage());
        }
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')
            ->redirectUrl(config('services.facebook.redirect'))
            ->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')
                ->redirectUrl(config('services.facebook.redirect'))
                ->stateless()
                ->user();
            
            // Check if user exists
            $user = User::where('facebook_id', $facebookUser->id)->first();
            
            if (!$user) {
                // Check if user exists with same email
                $user = User::where('email', $facebookUser->email)->first();
                
                if (!$user) {
                    // Create new user with temporary password
                    $tempPassword = Str::random(24);
                    $user = User::create([
                        'name' => $facebookUser->name,
                        'email' => $facebookUser->email,
                        'facebook_id' => $facebookUser->id,
                        'password' => Hash::make($tempPassword),
                        'email_verified_at' => now(),
                        'temp_password' => Hash::make($tempPassword),
                        'temp_password_used' => false,
                        'temp_password_expires_at' => now()->addMinutes(15),
                    ]);

                    // Ensure the user has a role
                    if (!$user->roles()->exists()) {
                        $customerRole = Role::firstOrCreate(['name' => 'Customer']);
                        $user->assignRole($customerRole);
                    }

                    // Send email with temporary password
                    Mail::to($user->email)->send(new TempPasswordMail($tempPassword));
                    
                    // Log the user in
                    Auth::login($user);
                    
                    // Redirect to password reset page
                    return redirect()->route('password.reset')
                        ->with('status', 'Please set up your password to complete the registration process.');
                } else {
                    // Update existing user with Facebook ID
                    $user->facebook_id = $facebookUser->id;
                    $user->save();

                    // Ensure the user has a role
                    if (!$user->roles()->exists()) {
                        $customerRole = Role::firstOrCreate(['name' => 'Customer']);
                        $user->assignRole($customerRole);
                    }
                }
            }
            
            // Set session flag to prevent certificate login
            session(['oauth_login' => true]);
            session(['socialite_provider' => 'facebook']);
            
            // Log the user in
            Auth::login($user);
            
            return redirect()->intended('/');
            
        } catch (\Exception $e) {
            Log::error('Facebook login error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Failed to login with Facebook. Please try again.');
        }
    }

}
