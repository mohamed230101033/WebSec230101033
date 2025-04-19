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
        
        // Check if user has a verification code already
        $hasCode = !is_null($user->phone_verification_code) && 
                   !is_null($user->phone_verification_code_expires_at) && 
                   now()->lessThan($user->phone_verification_code_expires_at);
        
        return view('phone.verify', [
            'phoneNumber' => $user->phone,
            'hasCode' => $hasCode,
        ]);
    }
    
    /**
     * Send a verification code to the user's phone
     */
    public function send(Request $request)
    {
        $user = Auth::user();
        
        // If phone already verified, redirect to home
        if ($user->hasVerifiedPhone()) {
            return redirect('/')->with('success', 'Your phone number is already verified.');
        }
        
        // Validate phone number exists in profile
        if (empty($user->phone)) {
            return redirect()->route('profile')->with('error', 'Please add a phone number to your profile first.');
        }
        
        // Invalidate any existing code
        if (!is_null($user->phone_verification_code)) {
            $user->phone_verification_code = null;
            $user->phone_verification_code_expires_at = null;
            $user->save();
        }
        
        // Generate a verification code
        $code = $this->smsService->generateVerificationCode();
        
        // Save the code and set expiry time (10 minutes)
        $user->phone_verification_code = $code;
        $user->phone_verification_code_expires_at = Carbon::now()->addMinutes(10);
        $user->save();
        
        // Send the code via SMS
        $success = $this->smsService->sendVerificationCode($user->phone, $code, $user->name);
        
        if ($success) {
            return redirect()->route('phone.verify')
                ->with('success', 'A verification code has been sent to your phone number.');
        } else {
            // If SMS failed, still show code for testing purposes
            return redirect()->route('phone.verify')
                ->with('warning', 'We were unable to send an SMS at this time. For testing purposes, your verification code is: ' . $code . '. This code will expire in 10 minutes.');
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
        $request->validate([
            'phone' => 'required|string|min:10|unique:users,phone,' . Auth::id(),
        ]);
        
        $user = Auth::user();
        
        // Format phone number to include + if not present
        $phone = $request->phone;
        if (substr($phone, 0, 1) !== '+') {
            $phone = '+' . $phone;
        }
        
        // If phone number is changing, remove verification
        if ($user->phone != $phone) {
            $user->phone = $phone;
            $user->phone_verified_at = null;
            $user->phone_verification_code = null;
            $user->phone_verification_code_expires_at = null;
            $user->save();
            
            // Generate a verification code immediately
            $code = $this->smsService->generateVerificationCode();
            $user->phone_verification_code = $code;
            $user->phone_verification_code_expires_at = now()->addMinutes(10);
            $user->save();
            
            // Try to send the code
            $success = $this->smsService->sendVerificationCode($user->phone, $code, $user->name);
            
            if ($success) {
                return redirect()->route('phone.verify')
                    ->with('success', 'Phone number updated. A verification code has been sent to your new number.');
            } else {
                return redirect()->route('phone.verify')
                    ->with('warning', 'Phone number updated. We were unable to send an SMS at this time. For testing purposes, your verification code is: ' . $code);
            }
        }
        
        return redirect()->route('phone.verify')->with('info', 'Phone number unchanged.');
    }
} 
 