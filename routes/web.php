<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

use App\Models\Program;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\GuestPaymentController;
use App\Http\Controllers\PaymentNotificationController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MuzakkiController;
use App\Http\Controllers\MustahikController;
use App\Http\Controllers\DistributionController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\OTPController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\RecurringDonationController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\AccountClaimController;

/*
|--------------------------------------------------------------------------
| Web Routes - SIPZIS LAZISMU BANTEN
|--------------------------------------------------------------------------
| Standardized & Cleaned RESTful Routes Architecture
|
*/

// ============================================================================
// PUBLIC ROUTES
// ============================================================================
// ----------------------------------------------------------------------------
// CACHED PUBLIC ROUTES (Frontend Caching)
// ----------------------------------------------------------------------------
Route::middleware('cache.headers:private;max_age=300;etag')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/tentang', [HomeController::class, 'tentang'])->name('tentang');
    
    // Program & Category Routes
    Route::get('/program', [HomeController::class, 'program'])->name('program');
    Route::get('/program/zakat', fn() => app(HomeController::class)->programByCategory('zakat'))->name('program.zakat');
    Route::get('/program/infaq', fn() => app(HomeController::class)->programByCategory('infaq'))->name('program.infaq');
    Route::get('/program/shadaqah', fn() => app(HomeController::class)->programByCategory('shadaqah'))->name('program.shadaqah');
    Route::get('/program/pilar', fn() => app(HomeController::class)->programByCategory('pilar'))->name('program.pilar');
    Route::get('/program/{slug}', [ProgramController::class, 'show'])->name('program.show');
    Route::get('/program/{id}/completed', [ProgramController::class, 'completed'])->name('program.completed');

    // Campaign Public Routes
    Route::get('/campaigns/all', [CampaignController::class, 'all'])->name('campaigns.all');
    Route::get('/campaigns/{category}', [CampaignController::class, 'index'])->name('campaigns.index');
    Route::get('/campaigns/{category}/{campaign}', [CampaignController::class, 'show'])->name('campaigns.show');
    Route::get('/campaigner/{email}', [CampaignController::class, 'showPersonalCampaign'])->name('campaigner.personal');

    // News & Articles
    Route::get('/berita', [HomeController::class, 'berita'])->name('berita.index');
    Route::get('/berita/{slug}', [NewsController::class, 'show'])->name('news.show');
    Route::get('/artikel', [HomeController::class, 'artikel'])->name('artikel.index');
    Route::get('/artikel/{slug}', [HomeController::class, 'artikelShow'])->name('artikel.show');

    // Public Zakat Calculator
    Route::get('/calculator', [CalculatorController::class, 'index'])->name('calculator.index');
    Route::get('/calculator/guide', [CalculatorController::class, 'guide'])->name('calculator.guide');
});

// Non-cached POST route
Route::post('/chatbot', [ChatbotController::class, 'ask'])->name('chatbot.ask')->middleware('throttle:10,1');
Route::post('/calculator/calculate', [CalculatorController::class, 'calculate'])->name('calculator.calculate');

// Gold Price API (Cache aggressively publicly)
Route::get('/calculator/gold-price', [CalculatorController::class, 'getGoldPrice'])->name('calculator.gold-price')->middleware('cache.headers:public;max_age=86400;etag');

// Admin Entrance Redirect
Route::get('/admin', function () {
    return redirect()->route('admin.login');
});


// Public Guest Donation & Payment Routes
Route::get('/payment/{paymentCode}/failed', [GuestPaymentController::class, 'guestFailure'])->name('guest.payment.failed');
Route::post('/account/claim', [AccountClaimController::class, 'claim'])->name('guest.account.claim');

Route::prefix('donasi')->name('guest.payment.')->group(function () {
    Route::get('/', fn() => redirect()->route('program'));
    Route::get('/create', [GuestPaymentController::class, 'guestCreate'])->name('create');
    Route::post('/store', [GuestPaymentController::class, 'guestStore'])->name('store');
    Route::get('/summary/{paymentCode}', [GuestPaymentController::class, 'guestSummary'])->name('summary')->middleware('throttle:60,1');
    Route::get('/success/{paymentCode}', [GuestPaymentController::class, 'guestSuccess'])->name('success')->middleware('throttle:60,1');
    Route::get('/check-status/{paymentCode}', [GuestPaymentController::class, 'guestCheckStatus'])->name('checkStatus')->middleware('throttle:60,1');

    Route::post('/get-token/{paymentCode}', [GuestPaymentController::class, 'getSnapToken'])->name('getToken')->middleware('throttle:30,1');
    Route::post('/{paymentCode}/get-token-custom', [GuestPaymentController::class, 'getTokenCustom'])->name('getTokenCustom')->middleware('throttle:30,1');
    Route::get('/{paymentCode}/receipt', [GuestPaymentController::class, 'guestReceiptByCode'])->name('receipt')->middleware('throttle:60,1');
    Route::get('/{paymentCode}/receipt/download', [GuestPaymentController::class, 'downloadGuestReceipt'])->name('receipt.download')->middleware('throttle:60,1');
    Route::get('/{slug}', [DonationController::class, 'show'])->name('show');
});

// Secure Storage Image Serving Route
Route::get('/images/{path}', function ($path) {
    if (str_contains($path, '..')) {
        abort(404);
    }
    $storagePath = storage_path('app/public/' . $path);
    if (!file_exists($storagePath)) {
        abort(404);
    }
    return response()->file($storagePath, [
        'Content-Type' => mime_content_type($storagePath),
        'Cache-Control' => 'public, max-age=31536000'
    ]);
})->where('path', '.*')->name('image.serve');

// ============================================================================
// AUTHENTICATION & GUEST ACCESS ROUTES
// ============================================================================
Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->middleware('throttle:5,1');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('/firebase-login', [AuthController::class, 'firebaseLogin'])->name('firebase.login')->middleware('throttle:5,1');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/password/reset', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/password/email', [AuthController::class, 'sendPasswordResetEmail'])->name('password.email')->middleware('throttle:3,1');
Route::get('/password/reset/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.update')->middleware('throttle:5,1');

// 2FA Verification during Login
Route::get('/two-factor/verify', [TwoFactorController::class, 'showVerify'])->name('two-factor.verify');
Route::post('/two-factor/verify', [TwoFactorController::class, 'verify'])->name('two-factor.verify.post')->middleware('throttle:5,1');

// Payment Callbacks & Notifications
Route::get('/payment/finish', [PaymentController::class, 'finish']);
Route::get('/payment/unfinish', [PaymentController::class, 'unfinish']);
Route::get('/payment/error', [PaymentController::class, 'error']);
Route::post('/midtrans/notification', [PaymentNotificationController::class, 'handleNotification'])->middleware('throttle:30,1');


// Region & OTP API Routes
Route::prefix('regions')->name('regions.')->group(function () {
    Route::get('/countries', [RegionController::class, 'countries'])->name('countries');
    Route::get('/provinces/{country}', [RegionController::class, 'provinces'])->name('provinces');
    Route::get('/cities/{provinceId}', [RegionController::class, 'cities'])->name('cities');
    Route::get('/districts/{cityId}', [RegionController::class, 'districts'])->name('districts');
    Route::get('/villages/{districtId}', [RegionController::class, 'villages'])->name('villages');
    Route::post('/validate-postal-code', [RegionController::class, 'validatePostalCode'])->name('validate.postal.code');
    Route::post('/get-postal-code', [RegionController::class, 'getPostalCodeByVillage'])->name('get.postal.code');
});

Route::post('/send-otp', [OTPController::class, 'sendOTP'])->name('otp.send')->middleware('throttle:3,1');
Route::post('/verify-otp', [OTPController::class, 'verifyOTP'])->name('otp.verify')->middleware('throttle:5,1');
Route::post('/resend-otp', [OTPController::class, 'resendOTP'])->name('otp.resend')->middleware('throttle:3,1');

// Email Verification (Auth Middleware)
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', fn() => view('auth.verify-email'))->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/dashboard')->with('success', 'Email berhasil diverifikasi!');
    })->middleware(['signed'])->name('verification.verify');
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success', 'Link verifikasi telah dikirim ulang ke email Anda!');
    })->middleware(['throttle:6,1'])->name('verification.send');
});

// ============================================================================
// PROTECTED ROUTES (Requires Authentication)
// ============================================================================
Route::middleware('auth')->group(function () {

    // ------------------------------------------------------------------------
    // SECTION 1: MUZAKKI-ONLY ROUTES
    // ------------------------------------------------------------------------
    Route::middleware('role:muzakki')->group(function () {
        Route::get('/donation', [DashboardController::class, 'donation'])->name('donation');
        Route::get('/fundraising', [DashboardController::class, 'fundraising'])->name('fundraising');
        Route::get('/amalanku', [DashboardController::class, 'amalanku'])->name('amalanku');

        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            Route::get('/transactions', [DashboardController::class, 'transactions'])->name('transactions');
            Route::get('/recurring', [DashboardController::class, 'recurringDonations'])->name('recurring');
            Route::get('/recurring/create', [RecurringDonationController::class, 'create'])->name('recurring.create');
            Route::post('/recurring-donations', [RecurringDonationController::class, 'store'])->name('recurring-donations.store');
            Route::patch('/recurring-donations/{recurringDonation}/toggle', [RecurringDonationController::class, 'toggle'])->name('recurring-donations.toggle');
            Route::delete('/recurring-donations/{recurringDonation}', [RecurringDonationController::class, 'destroy'])->name('recurring-donations.destroy');

            Route::get('/bank-accounts', [DashboardController::class, 'bankAccounts'])->name('bank-accounts');
            Route::get('/bank-accounts/add', [BankAccountController::class, 'create'])->name('bank-accounts.create');
            Route::post('/bank-accounts', [BankAccountController::class, 'store'])->name('bank-accounts.store');
            Route::delete('/bank-accounts/{bankAccount}', [BankAccountController::class, 'destroy'])->name('bank-accounts.destroy');
            Route::post('/bank-accounts/{bankAccount}/set-primary', [BankAccountController::class, 'setPrimary'])->name('bank-accounts.set-primary');

            Route::get('/management', [DashboardController::class, 'accountManagement'])->name('management');
            Route::get('/management/account/transfer-account', [DashboardController::class, 'transferAccount'])->name('management.transfer-account');
            Route::get('/management/account/delete-account', [DashboardController::class, 'deleteAccount'])->name('management.delete-account');
        });

        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/ajax', [PaymentNotificationController::class, 'ajaxNotifications'])->name('ajax');
        });
    });

    // ------------------------------------------------------------------------
    // SECTION 2: ADMIN-ONLY ROUTES
    // ------------------------------------------------------------------------
    Route::middleware('role:admin')->group(function () {
        Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');

        // Muzakki Management
        Route::resource('muzakki', MuzakkiController::class)->except(['show']);
        Route::get('/muzakki/{muzakki}', [MuzakkiController::class, 'show'])->name('muzakki.show')->where('muzakki', '[0-9]+');
        Route::patch('/muzakki/{muzakki}/toggle-status', [MuzakkiController::class, 'toggleStatus'])->name('muzakki.toggle-status')->where('muzakki', '[0-9]+');
        Route::get('/api/muzakki/search', [MuzakkiController::class, 'search'])->name('api.muzakki.search');

        // Mustahik Management
        Route::resource('mustahik', MustahikController::class);
        Route::patch('/mustahik/{mustahik}/toggle-status', [MustahikController::class, 'toggleStatus'])->name('mustahik.toggle-status');
        Route::get('/api/mustahik/by-category', [MustahikController::class, 'getByCategory'])->name('api.mustahik.by-category');
        Route::get('/api/mustahik/search', [MustahikController::class, 'search'])->name('api.mustahik.search');

        // Zakat Distribution Management
        Route::resource('distributions', DistributionController::class);
        Route::patch('/distributions/{distribution}/mark-received', [DistributionController::class, 'markAsReceived'])->name('distributions.mark-received');
        Route::get('/distributions-report/category', [DistributionController::class, 'reportByCategory'])->name('distributions.report.category');
        Route::get('/api/distributions/mustahik-by-category', [DistributionController::class, 'getMustahikByCategory'])->name('api.distributions.mustahik-by-category');
        Route::get('/api/distributions/search', [DistributionController::class, 'search'])->name('api.distributions.search');
        Route::get('/distributions/{distribution}/receipt', [DistributionController::class, 'receipt'])->name('distributions.receipt');

        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/incoming', [ReportsController::class, 'incoming'])->name('incoming');
            Route::get('/outgoing', [ReportsController::class, 'outgoing'])->name('outgoing');
        });

        // Admin Content Management
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::resource('news', NewsController::class)->except(['index', 'show']);
            Route::get('/news', [NewsController::class, 'adminIndex'])->name('news.index');
            Route::get('/news/{news}', [NewsController::class, 'adminShow'])->name('news.show');
            Route::patch('/news/{news}/toggle-publish', [NewsController::class, 'togglePublish'])->name('news.toggle-publish');

            Route::resource('artikel', ArtikelController::class);
            Route::patch('/artikel/{artikel}/toggle-publish', [ArtikelController::class, 'togglePublish'])->name('artikel.toggle-publish');

            Route::get('/campaigns', [CampaignController::class, 'adminIndex'])->name('campaigns.index');
            Route::get('/campaigns/create', [CampaignController::class, 'adminCreate'])->name('campaigns.create');
            Route::post('/campaigns', [CampaignController::class, 'adminStore'])->name('campaigns.store');
            Route::get('/campaigns/{campaign}/edit', [CampaignController::class, 'adminEdit'])->name('campaigns.edit');
            Route::put('/campaigns/{campaign}', [CampaignController::class, 'adminUpdate'])->name('campaigns.update');
            Route::delete('/campaigns/{campaign}', [CampaignController::class, 'adminDestroy'])->name('campaigns.destroy');

            Route::get('/programs', [ProgramController::class, 'adminIndex'])->name('programs.index');
            Route::get('/programs/create', [ProgramController::class, 'adminCreate'])->name('programs.create');
            Route::get('/programs/bulk-create', [ProgramController::class, 'adminBulkCreate'])->name('programs.bulk-create');
            Route::post('/programs', [ProgramController::class, 'adminStore'])->name('programs.store');
            Route::post('/programs/bulk', [ProgramController::class, 'adminStoreBulk'])->name('programs.store.bulk');
            Route::get('/programs/{program}/edit', [ProgramController::class, 'adminEdit'])->name('programs.edit');
            Route::put('/programs/{program}', [ProgramController::class, 'adminUpdate'])->name('programs.update');
            Route::delete('/programs/{program}', [ProgramController::class, 'adminDestroy'])->name('programs.destroy');
        });
    });

    // ------------------------------------------------------------------------
    // SECTION 3: SHARED AUTH ROUTES (Admin + Muzakki)
    // ------------------------------------------------------------------------
    Route::middleware('role:admin,muzakki')->group(function () {
        
        // Main Overview Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Shared 2FA Security Settings
        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            Route::get('/two-factor/setup', [TwoFactorController::class, 'showSetup'])->name('two-factor.setup');
            Route::post('/two-factor/enable', [TwoFactorController::class, 'enable'])->name('two-factor.enable');
            Route::post('/two-factor/disable', [TwoFactorController::class, 'disable'])->name('two-factor.disable');
        });

        // Primary RESTful Profile URL: /profiles
        Route::get('/profiles', [MuzakkiController::class, 'edit'])->name('profiles');
        Route::put('/profiles', [MuzakkiController::class, 'update'])->name('profiles.update');

        // Automatic redirects & legacy aliases to /profiles
        Route::get('/profiles/edit', fn() => redirect()->route('profiles'))->name('profiles.edit');
        Route::get('/profile', fn() => redirect()->route('profiles'))->name('profile.show');
        Route::get('/profile/edit', fn() => redirect()->route('profiles'))->name('profile.edit');
        Route::put('/profile', [MuzakkiController::class, 'update'])->name('profile.update');

        // Calculator & Payments
        Route::get('/my-calculator', [CalculatorController::class, 'index'])->name('my-calculator');

        Route::prefix('payments')->name('payments.')->group(function () {
            Route::get('/', [PaymentController::class, 'index'])->name('index');
            Route::get('/create', [PaymentController::class, 'create'])->name('create');
            Route::post('/', [PaymentController::class, 'store'])->name('store');
            Route::get('/search', [PaymentController::class, 'search'])->name('api.payments.search');
            Route::get('/{payment}', [PaymentController::class, 'show'])->name('show');
            Route::put('/{payment}', [PaymentController::class, 'update'])->name('update');
            Route::get('/{payment}/receipt', [PaymentController::class, 'receipt'])->name('receipt');
        });

        // Notifications
        Route::get('/notifications', [PaymentNotificationController::class, 'notifications'])->name('notifications.index');
        Route::post('/notifications/mark-as-read', [PaymentNotificationController::class, 'markNotificationsAsRead'])->name('notifications.markAsRead');
    });
});
