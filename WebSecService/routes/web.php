<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\TranscriptController;
use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\PhoneVerificationController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

// Certificate-based login - modified to avoid OAuth conflicts
Route::get('/', function (Request $request) {
    // Skip certificate login for certain referrers (OAuth flows)
    $referer = $request->header('referer');
    $skipCertLogin = false;
    
    // Skip if coming from auth routes or OAuth providers
    if (!empty($referer)) {
        $skipCertLogin = 
            str_contains($referer, '/auth/') || 
            str_contains($referer, 'google') || 
            str_contains($referer, 'facebook') ||
            str_contains($referer, 'login');
    }
    
    // Also skip if there is an active OAuth session or just after OAuth login
    if (session()->has('oauth_login') || session()->has('socialite_provider')) {
        $skipCertLogin = true;
    }
    
    // Check if user is explicitly requesting certificate login via query parameter
    $useCertLogin = $request->has('cert_login');
    $isDirectAccess = empty($referer);
    
    // Get email from certificate
    $email = emailFromLoginCertificate();
    
    // Only attempt certificate login if:
    // 1. Explicitly requested via cert_login parameter, OR
    // 2. This is a direct access to the site (no referer) AND no session yet
    // 3. Not coming from OAuth flows
    if ($email && !$skipCertLogin && ($useCertLogin || ($isDirectAccess && !session()->has('visited_before')))) {
        $user = User::where('email', $email)->first();
        
        if ($user) {
            // Log the user in explicitly
            Auth::login($user);
            
            // Store a flag in the session indicating certificate-based login
            session(['cert_login' => true]);
            
            // Redirect to clear the cert_login parameter if it was set
            if ($useCertLogin) {
                return redirect('/');
            }
        }
    }
    
    // Mark that the user has visited the site
    session(['visited_before' => true]);
    
    return view('welcome', [
        'show_cert_login' => isset($email) && !auth()->check()
    ]);
});

// Explicit certificate logout route
Route::get('/cert-logout', function () {
    // Clear the session
    Auth::logout();
    session()->flush();
    
    // Redirect to a page that doesn't perform certificate auth
    return redirect('/logout-success');
})->name('cert.logout');

// A page that doesn't try to log in with certificate
Route::get('/logout-success', function (Request $request) {
    $email = emailFromLoginCertificate();
    return view('welcome', [
        'show_cert_login' => isset($email),
        'logout_success' => true
    ]);
})->name('logout.success');

Route::get('multable/{id?}', function ($id = 1) {
    return view('multable', [
        'number' => $id,
    ]);
});

Route::get('/prime', function () {
    return view('prime');
});

Route::get('/even', function () {
    return view('even');
});

Route::get('/minitest', function () {
    // Sample bill information object
    $bill = [
        'items' => [
            ['name' => 'Milk', 'quantity' => 2, 'price' => 2.50, 'total' => 5.00],
            ['name' => 'Bread', 'quantity' => 1, 'price' => 1.20, 'total' => 1.20],
            ['name' => 'Eggs', 'quantity' => 12, 'price' => 0.20, 'total' => 2.40],
            ['name' => 'Butter', 'quantity' => 1, 'price' => 3.00, 'total' => 3.00],
        ],
        'subtotal' => 11.60,
        'tax' => 1.16, // Assuming 10% tax
        'total' => 12.76,
    ];

    return view('minitest', ['bill' => $bill]);
})->name('minitest');

Route::get('/transcript', [TranscriptController::class, 'index'])->name('transcript');

Route::get('/calculator', [CalculatorController::class, 'index'])->name('calculator');

Route::resource('users', UserController::class);

Route::resource('grades', GradeController::class);

Route::resource('questions', QuestionController::class);
Route::get('/exam', [QuestionController::class, 'startExam'])->name('questions.startExam');
Route::post('/exam/submit', [QuestionController::class, 'submitExam'])->name('questions.submitExam');

Route::get('products', [ProductsController::class, 'list'])->name('products_list');
Route::get('products/edit/{product?}', [ProductsController::class, 'edit'])->name('products_edit');
Route::post('products/save/{product?}', [ProductsController::class, 'save'])->name('products_save');
Route::post('products/{product}/update-stock', [ProductsController::class, 'updateStock'])->name('products_update_stock');
Route::get('products/delete/{product}', [ProductsController::class, 'delete'])->name('products_delete');
Route::get('products/hold/{product}', [ProductsController::class, 'hold'])->name('products_hold');
Route::get('products/unhold/{product}', [ProductsController::class, 'unhold'])->name('products_unhold');

Route::get('my-purchases', [PurchaseController::class, 'list'])->name('purchases_list');
Route::get('products/{product}/purchase', [PurchaseController::class, 'showPurchaseForm'])->name('purchase_form');
Route::post('products/{product}/purchase', [PurchaseController::class, 'purchase'])->name('do_purchase');

Route::get('register', [UserController::class, 'register'])->name('register');
Route::post('register', [UserController::class, 'doRegister'])->name('doRegister');
Route::get('login', [UserController::class, 'login'])->name('login');
Route::post('login', [UserController::class, 'doLogin'])->name('doLogin');
Route::get('logout', [UserController::class, 'doLogout'])->name('doLogout');

Route::get('profile/{user?}', [UserController::class, 'profile'])->name('profile')->middleware('auth');
Route::post('profile/update-password/{user?}', [UserController::class, 'updatePassword'])->name('updatePassword')->middleware('auth');
Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('auth');
Route::put('users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('auth');

Route::get('forgot-password', [UserController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('forgot-password', [UserController::class, 'verifySecurityQuestion'])->name('password.email');
// Route::get('verify-answer', [UserController::class, 'showVerifyAnswerForm'])->name('password.verify');
// Route::post('verify-answer', [UserController::class, 'checkSecurityAnswer'])->name('password.check');
Route::get('reset-password', [UserController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('reset-password', [UserController::class, 'resetPassword'])->name('password.update');

// Database cleanup route - temporary
Route::get('/fix-image-paths', function () {
    if (!auth()->check() || !auth()->user()->hasRole('Admin')) {
        abort(403, 'Unauthorized');
    }

    $fixed = 0;
    $products = \App\Models\Product::all();

    foreach ($products as $product) {
        if ($product->photo && !is_file(public_path('images/' . $product->photo))) {
            $product->photo = null;
            $product->save();
            $fixed++;
        }
    }

    return redirect()->route('products_list')
        ->with('success', "Fixed $fixed products with missing images. You can now re-upload proper images.");
})->middleware('auth');

Route::get('/test-email', function () {
    try {
        Mail::raw('This is a test email from WebSecService.', function ($message) {
            $message->to('mohamedamrr666@gmail.com')
                ->subject('Test Email');
        });
        return 'Test email sent successfully!';
    } catch (\Exception $e) {
        return 'Failed to send test email: ' . $e->getMessage();
    }
});

Route::get('verify', [UserController::class, 'verify'])->name('verify');

// Setup roles and permissions route
Route::get('/setup-roles', function () {
    try {
        // Create roles
        $adminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $employeeRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Employee', 'guard_name' => 'web']);
        $customerRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Customer', 'guard_name' => 'web']);

        $output = "Created roles: Admin, Employee, Customer<br>";

        // Create permissions
        $permissions = [
            'edit_products',
            'delete_products',
            'hold_products',
            'manage_stock',
            'purchase_products',
            'manage_users',
            'view_reports',
            'access_admin_panel',
        ];

        foreach ($permissions as $permissionName) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }
        $output .= "Created permissions: " . implode(', ', $permissions) . "<br>";

        // Get admin user or create if not exists
        $adminUser = \App\Models\User::where('email', 'mohamedamrr666@gmail.com')->first();
        if (!$adminUser) {
            $adminUser = \App\Models\User::create([
                'name' => 'Admin',
                'email' => 'mohamedamrr666@gmail.com',
                'password' => bcrypt('Qwe!2345'),
                'admin' => true,
                'email_verified_at' => now(),
            ]);
            $output .= "Created admin user: mohamedamrr666@gmail.com with password Qwe!2345<br>";
        } else {
            $output .= "Admin user already exists: " . $adminUser->email . "<br>";
        }

        // Assign admin role to user
        $adminUser->assignRole($adminRole);
        $output .= "Assigned Admin role to: " . $adminUser->email . "<br>";

        // Give all permissions to admin
        foreach ($permissions as $permissionName) {
            $permission = \Spatie\Permission\Models\Permission::where('name', $permissionName)->first();
            if ($permission) {
                $adminRole->givePermissionTo($permission);
            }
        }
        $output .= "Gave all permissions to Admin role<br>";

        return $output . "Roles and permissions setup completed successfully!";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage() . "<br>Line: " . $e->getLine() . "<br>File: " . $e->getFile();
    }
});

// User credit management
Route::post('users/{user}/add-credit', [UserController::class, 'addCredit'])->name('users.add_credit')->middleware('auth');
// Phone verification routes
Route::get('verify-phone', [PhoneVerificationController::class, 'show'])->name('phone.verify')->middleware('auth');
Route::post('verify-phone', [PhoneVerificationController::class, 'verify'])->name('phone.verify')->middleware('auth');
Route::post('verify-phone/send', [PhoneVerificationController::class, 'send'])->name('phone.send')->middleware('auth');
Route::post('verify-phone/update', [PhoneVerificationController::class, 'updatePhone'])->name('phone.update')->middleware('auth');

//Google OAuth routes
Route::get('/auth/google', [UserController::class, 'redirectToGoogle'])->name('login_with_google');
Route::get('/auth/google/callback', [UserController::class, 'handleGoogleCallback']);

// Facebook OAuth routes
Route::get('/auth/facebook', [UserController::class, 'redirectToFacebook'])->name('login_with_facebook');
Route::get('/auth/facebook/callback', [UserController::class, 'handleFacebookCallback']);

// To Drop A Table (Vulerability - SQL Injection)
// Route::get('/sqli', function(Request $request){
// $table = $request->query('table');
// DB::unprepared("Drop Table $table");
// return redirect('/');
// });

// to send data to another domain
// Route::get('/collect', function(Request $request){
//     $name = $request->query('name');
//     $credits = $request->query('credits');

//     return response("Data Collected",200)
//     ->header('Access-Control-Allow-Origin', '*')
//     ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE')
//     ->header('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With');
// });

// No additional routes below this line

Route::get('/cryptography', function (Request $request) {
 $data = $request->data??"Welcome to Cryptography";
 $action = $request->action??"Encrypt";
 $result = $request->result??"";
 $status = "Failed";

 if($request->action=="Encrypt") {
     $temp = openssl_encrypt($request->data, 'aes-128-ecb', 'thisisasecretkey', OPENSSL_RAW_DATA, '');
     if($temp) {
         $status = 'Encrypted Successfully';
         $result = base64_encode($temp);
        }
    }else if($request->action=="Decrypt") {
        $temp = base64_decode($request->data);
        $result = openssl_decrypt($temp, 'aes-128-ecb', 'thisisasecretkey', OPENSSL_RAW_DATA, '');
        if($result) $status = 'Decrypted Successfully';
    }else if($request->action=="Hash") {
        $temp = hash('sha256', $request->data);
        $result = base64_encode($temp);
        $status = 'Hashed Successfully';
    }else if($request->action=="Sign") {
            $path = storage_path('app\private\useremail@domain.com.pfx');
            $password = '12345678';
            $certificates = [];
            $pfx = file_get_contents($path);
            openssl_pkcs12_read($pfx, $certificates, $password);
            $privateKey = $certificates['pkey'];
            $signature = '';
        if(openssl_sign($request->data, $signature, $privateKey, 'sha256')) {
            $result = base64_encode($signature);
            $status = 'Signed Successfully';
        }
    }else if($request->action=="Verify") {
        $signature = base64_decode($request->result);
        $path = storage_path('app\public\useremail@domain.com.crt');
        $publicKey = file_get_contents($path);
        if(openssl_verify($request->data, $signature, $publicKey, 'sha256')) {
            $status = 'Verified Successfully';
        }
    }else if($request->action=="KeySend") {
        $path = storage_path('app\public\useremail@domain.com.crt');
        $publicKey = file_get_contents($path);
        $temp = '';
        if(openssl_public_encrypt($request->data, $temp, $publicKey)) {
            $result = base64_encode($temp);
            $status = 'Key is Encrypted Successfully';
        }
    }else if($request->action=="KeyRecive") {
        $path = storage_path('app\private\useremail@domain.com.pfx');
        $password = '12345678';
        $certificates = [];
        $pfx = file_get_contents($path);
        openssl_pkcs12_read($pfx, $certificates, $password);
        $privateKey = $certificates['pkey'];
        $encryptedKey = base64_decode($request->data);
        $result = '';
        if(openssl_private_decrypt($encryptedKey, $result, $privateKey)) {
            $status = 'Key is Decrypted Successfully';
        }
    }
    
    return view('cryptography', compact('data', 'result', 'action', 'status'));
})->name('cryptography');
