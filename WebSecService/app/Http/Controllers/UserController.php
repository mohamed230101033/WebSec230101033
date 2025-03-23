<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Validation\ValidatesRequests;

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
            'password' => 'required|string|min:8', // Require password
            'phone' => 'nullable|string|max:20',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash the password
            'phone' => $request->phone,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8', // Optional for update
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
        $this->validate($request, [
            'name' => ['required', 'string', 'min:5'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            'security_question' => ['required', 'string', 'max:255'],
            'security_answer' => ['required', 'string', 'max:255'],
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password); // Secure
        $user->security_question = $request->security_question;
        $user->security_answer = Hash::make($request->security_answer);
        $user->save();

        return redirect("/")->with('success', 'Registration successful. Please log in.');
    }

    public function login(Request $request)
    {
        return view('users.login');
    }

    public function doLogin(Request $request)
    {
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->back()->withInput($request->input())->withErrors('Invalid login information.');
        }
        $user = User::where('email', $request->email)->first();
        Auth::setUser($user);
        return redirect("/");
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
        $request->validate(['email' => 'required|email|exists:users,email']);
        $user = User::where('email', $request->email)->first();
        if (!$user->security_question || !$user->security_answer) {
            return back()->withErrors(['email' => 'No security question set for this user.']);
        }
        session(['forgot_password_email' => $user->email]);
        return redirect()->route('password.verify');
    }

    public function showVerifyAnswerForm()
    {
        $email = session('forgot_password_email');
        if (!$email)
            return redirect()->route('password.request')->withErrors(['session' => 'Session expired.']);
        $user = User::where('email', $email)->first();
        return view('users.verify_security_answer', ['security_question' => $user->security_question, 'email' => $email]);
    }

    public function checkSecurityAnswer(Request $request)
    {
        $request->validate(['security_answer' => 'required|string']);
        $email = session('forgot_password_email');
        if (!$email)
            return redirect()->route('password.request')->withErrors(['session' => 'Session expired.']);
        $user = User::where('email', $email)->first();
        if (!Hash::check($request->security_answer, $user->security_answer))
            return back()->withErrors(['security_answer' => 'Incorrect answer.']);
        session(['can_reset_password' => true]);
        return redirect()->route('password.reset');
    }

    public function showResetPasswordForm()
    {
        if (!session('can_reset_password'))
            return redirect()->route('password.request')->withErrors(['session' => 'Invalid session.']);
        $email = session('forgot_password_email');
        return view('users.reset_password', ['email' => $email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate(['password' => 'required|string|min:8|confirmed']);
        if (!session('can_reset_password'))
            return redirect()->route('password.request')->withErrors(['session' => 'Invalid session.']);
        $email = session('forgot_password_email');
        $user = User::where('email', $email)->first();
        $user->password = Hash::make($request->password);
        $user->save();
        session()->forget(['forgot_password_email', 'can_reset_password']);
        return redirect()->route('login')->with('status', 'Password reset successfully.');
    }
}
