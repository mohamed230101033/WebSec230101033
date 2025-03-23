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
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string|max:20',
        ]);

        $data = $request->only(['name', 'email', 'phone']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }

    public function register(Request $request)
    {
        return view('users.register');
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

        return redirect("/")->with('success', 'Registration successful. Please log in.');
    }

    public function login(Request $request)
    {
        return view('users.login');
    }

    public function doLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->withInput($request->input())->withErrors('Invalid login information.');
        }

        // Check if the provided password matches the temporary password
        if ($user->temp_password && !$user->temp_password_used && $user->temp_password_expires_at >= now() && Hash::check($request->password, $user->temp_password)) {
            Auth::login($user);
            // Mark the temporary password as used
            $user->temp_password_used = true;
            $user->temp_password = null;
            $user->temp_password_expires_at = null;
            $user->save();
            // Redirect to change password page
            return redirect()->route('password.reset')->with('status', 'Please set a new password.');
        } elseif ($user->temp_password && $user->temp_password_expires_at < now()) {
            return redirect()->back()->withInput($request->input())->withErrors('The temporary password has expired. Please request a new one.');
        }

        // Regular login with permanent password
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect("/");
        }

        return redirect()->back()->withInput($request->input())->withErrors('Invalid login information.');
    }

    public function doLogout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }

    public function profile(User $user = null)
    {
        $user = $user ?? Auth::user();
        return view('users.profile', compact('user'));
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
        return view('users.edit', compact('user'));
    }

    public function showForgotPasswordForm()
    {
        return view('users.forgot_password');
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
            return redirect()->route('login')->withErrors(['session' => 'Please log in with your temporary password first.']);
        }
        return view('users.reset_password', ['email' => Auth::user()->email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
        ]);

        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['session' => 'Please log in with your temporary password first.']);
        }

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')->with('status', 'Password reset successfully. Please log in with your new password.');
    }
}
