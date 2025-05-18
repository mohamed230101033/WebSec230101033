<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class FileEncryptionController extends Controller
{
    private $encryptedPath;
    private $decryptedPath;

    public function __construct()
    {
        // Define storage paths
        $this->encryptedPath = storage_path('app/encrypted');
        $this->decryptedPath = storage_path('app/decrypted');

        // Create directories if they don't exist
        if (!File::exists($this->encryptedPath)) {
            File::makeDirectory($this->encryptedPath, 0755, true);
        }
        if (!File::exists($this->decryptedPath)) {
            File::makeDirectory($this->decryptedPath, 0755, true);
        }
    }

    public function index()
    {
        return view('file-encryption.index');
    }

    public function encrypt(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // Max 10MB
            'password' => 'required|min:8'
        ]);

        try {
            $file = $request->file('file');
            $password = $request->password;
            
            // Read file contents
            $contents = file_get_contents($file->getRealPath());
            
            // Generate IV
            $iv = openssl_random_pseudo_bytes(16);
            
            // Encrypt the file contents
            $encrypted = openssl_encrypt(
                $contents,
                'AES-256-CBC',
                $password,
                OPENSSL_RAW_DATA,
                $iv
            );
            
            if ($encrypted === false) {
                return back()->with('error', 'Encryption failed. Please try again.');
            }
            
            // Combine IV and encrypted data
            $combined = $iv . $encrypted;
            
            // Generate a unique filename
            $encryptedFilename = 'encrypted_' . time() . '_' . Str::random(10) . '.enc';
            $fullPath = $this->encryptedPath . DIRECTORY_SEPARATOR . $encryptedFilename;
            
            // Store the encrypted file
            if (!File::put($fullPath, $combined)) {
                return back()->with('error', 'Failed to save encrypted file. Please try again.');
            }
            
            // Return the download response
            return response()->download(
                $fullPath,
                $encryptedFilename,
                ['Content-Type' => 'application/octet-stream']
            )->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function decrypt(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // Max 10MB
            'password' => 'required|min:8'
        ]);

        try {
            $file = $request->file('file');
            $password = $request->password;
            
            // Read file contents
            $contents = file_get_contents($file->getRealPath());
            
            if (strlen($contents) < 16) {
                return back()->with('error', 'Invalid encrypted file format.');
            }
            
            // Extract IV (first 16 bytes)
            $iv = substr($contents, 0, 16);
            $encrypted = substr($contents, 16);
            
            // Decrypt the file contents
            $decrypted = openssl_decrypt(
                $encrypted,
                'AES-256-CBC',
                $password,
                OPENSSL_RAW_DATA,
                $iv
            );
            
            if ($decrypted === false) {
                return back()->with('error', 'Decryption failed. Please check your password.');
            }
            
            // Generate a unique filename
            $decryptedFilename = 'decrypted_' . time() . '_' . Str::random(10) . '.txt';
            $fullPath = $this->decryptedPath . DIRECTORY_SEPARATOR . $decryptedFilename;
            
            // Store the decrypted file
            if (!File::put($fullPath, $decrypted)) {
                return back()->with('error', 'Failed to save decrypted file. Please try again.');
            }
            
            // Return the download response
            return response()->download(
                $fullPath,
                $decryptedFilename,
                ['Content-Type' => 'application/octet-stream']
            )->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
} 