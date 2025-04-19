<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class SMSService
{
    protected $twilioClient;
    protected $twilioFrom;
    
    public function __construct()
    {
        // Initialize Twilio client with credentials from .env
        $accountSid = config('services.twilio.sid');
        $authToken = config('services.twilio.token');
        $this->twilioFrom = config('services.twilio.from');
        
        if ($accountSid && $authToken) {
            try {
                $this->twilioClient = new Client($accountSid, $authToken);
            } catch (\Exception $e) {
                Log::error('Twilio initialization error: ' . $e->getMessage());
                $this->twilioClient = null;
            }
        }
    }
    
    /**
     * Send an SMS with a verification code
     *
     * @param string $to Phone number to send to
     * @param string $code Verification code
     * @param string|null $name User's name for personalization
     * @return bool Whether the message was sent successfully
     */
    public function sendVerificationCode(string $to, string $code, ?string $name = null): bool
    {
        if (!$this->twilioClient) {
            Log::warning('Attempted to send SMS but Twilio client is not initialized');
            return false;
        }
        
        try {
            // Format the phone number if it doesn't have a plus sign
            if (substr($to, 0, 1) !== '+') {
                $to = '+' . $to;
            }
            
            // Personalize greeting if name is provided
            $greeting = $name ? "Hello $name!" : "Hello!";
            
            // Send the SMS
            $message = $this->twilioClient->messages->create(
                $to,
                [
                    'from' => $this->twilioFrom,
                    'body' => "$greeting 👋 Your WebSecService verification code is: $code. This code will expire in 10 minutes."
                ]
            );
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Failed to send SMS: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Generate a random verification code
     *
     * @param int $length Length of the code (default: 6)
     * @return string The generated code
     */
    public function generateVerificationCode(int $length = 6): string
    {
        return (string) random_int(pow(10, $length - 1), pow(10, $length) - 1);
    }
    
    /**
     * Check if Twilio is properly configured
     * 
     * @return bool
     */
    public function isConfigured(): bool
    {
        return $this->twilioClient !== null && !empty($this->twilioFrom);
    }
} 
 