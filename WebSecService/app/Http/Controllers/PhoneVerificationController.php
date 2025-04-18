<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SMSService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PhoneVerificationController extends Controller
{
    protected $smsService;
    
    public function __construct(SMSService $smsService)
    {
        $this->smsService = $smsService;
        $this->middleware('auth');
    }
    
    /**
     * Show the phone verification form
     */
    public function show()
    {
        $user = Auth::user();
        
        // If phone already verified, redirect to home
        if ($user->hasVerifiedPhone()) {
            return redirect('/')->with('success', 'Your phone number is already verified.');
        }
        
        return view('phone.verify', [
            'phoneNumber' => $user->phone,
            // Always show verification form
            'hasCode' => true,
        ]);
    }
    
    /**
     * Send a verification code to the user's phone
     */
    public function send(Request $request)
    {
        $user = Auth::user();
        Log::info('Phone verification code requested', ['user_id' => $user->id, 'phone' => $user->phone]);
        
        // If phone already verified, redirect to home
        if ($user->hasVerifiedPhone()) {
            Log::info('Phone already verified, redirecting', ['user_id' => $user->id]);
            return redirect('/')->with('success', 'Your phone number is already verified.');
        }
        
        // Validate phone number exists in profile
        if (empty($user->phone)) {
            Log::warning('Phone number missing, redirecting to profile', ['user_id' => $user->id]);
            return redirect()->route('profile')->with('error', 'Please add a phone number to your profile first.');
        }
        
        // Invalidate any existing code
        if (!is_null($user->phone_verification_code)) {
            Log::info('Invalidating existing verification code', ['user_id' => $user->id]);
            $user->phone_verification_code = null;
            $user->phone_verification_code_expires_at = null;
            $user->save();
        }
        
        // Generate a verification code
        $code = $this->smsService->generateVerificationCode();
        Log::info('New verification code generated', ['user_id' => $user->id, 'code' => $code]);
        
        // Save the code and set expiry time (10 minutes)
        $user->phone_verification_code = $code;
        $user->phone_verification_code_expires_at = Carbon::now()->addMinutes(10);
        $user->save();
        
        // Send the code via SMS
        $success = $this->smsService->sendVerificationCode($user->phone, $code, $user->name);
        
        if ($success) {
            Log::info('SMS sent successfully', ['user_id' => $user->id]);
            return redirect()->route('phone.verify')
                ->with('success', 'A new verification code has been sent to your phone number.');
        } else {
            Log::warning('SMS sending failed, showing code in UI', ['user_id' => $user->id]);
            // If SMS failed, still allow verification for this exercise (in real world, you might want to retry)
            return redirect()->route('phone.verify')
                ->with('warning', 'We were unable to send an SMS at this time. For testing purposes, your WebSecService verification code is: ' . $code . '. This code will expire in 10 minutes.');
        }
    }
    
    /**
     * Verify the code entered by the user
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);
        
        $user = Auth::user();
        
        // If phone already verified, redirect to home
        if ($user->hasVerifiedPhone()) {
            return redirect('/')->with('success', 'Your phone number is already verified.');
        }
        
        // Check if there's a verification code set and not expired
        if (is_null($user->phone_verification_code) || 
            is_null($user->phone_verification_code_expires_at) || 
            Carbon::now()->isAfter($user->phone_verification_code_expires_at)) {
            
            return redirect()->route('phone.verify')
                ->with('error', 'Verification code is invalid or has expired. Please request a new code.');
        }
        
        // Verify the code
        if ($request->code == $user->phone_verification_code) {
            $user->markPhoneAsVerified();
            return redirect('/')->with('success', 'Phone number verified successfully!');
        }
        
        return back()->with('error', 'The verification code you entered is incorrect.');
    }
    
    /**
     * Update the user's phone number
     */
    public function updatePhone(Request $request)
    {
        Log::info('Phone update requested', [
            'user_id' => Auth::id(),
            'old_phone' => Auth::user()->phone,
            'new_phone' => $request->phone,
            'request_data' => $request->all()
        ]);
        
        $request->validate([
            'phone' => 'required|string|min:10|unique:users,phone,' . Auth::id(),
        ]);
        
        $user = Auth::user();
        
        // If phone number is changing, remove verification
        if ($user->phone != $request->phone) {
            Log::info('Updating phone number', [
                'user_id' => $user->id,
                'old_phone' => $user->phone,
                'new_phone' => $request->phone
            ]);
            
            $user->phone = $request->phone;
            $user->phone_verified_at = null;
            $user->phone_verification_code = null;
            $user->phone_verification_code_expires_at = null;
            $user->save();
            
            return redirect()->route('phone.verify')
                ->with('success', 'Phone number updated. Please verify your new number.');
        }
        
        Log::info('Phone number unchanged', ['user_id' => $user->id, 'phone' => $user->phone]);
        return back()->with('info', 'Phone number unchanged.');
    }
} 