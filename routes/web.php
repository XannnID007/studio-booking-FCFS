<?php
// routes/web.php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\StudioController as AdminStudio;
use App\Http\Controllers\Admin\BookingController as AdminBooking;
use App\Http\Controllers\Admin\PaymentController as AdminPayment;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboard;
use App\Http\Controllers\Customer\StudioController as CustomerStudio;
use App\Http\Controllers\Customer\BookingController as CustomerBooking;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function () {
    return redirect('/login');
});

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Studio Management
    Route::resource('studios', AdminStudio::class);

    // Booking Management
    Route::get('/bookings', [AdminBooking::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminBooking::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/status', [AdminBooking::class, 'updateStatus'])->name('bookings.status');
    Route::post('/bookings/{booking}/complete', [AdminBooking::class, 'markCompleted'])->name('bookings.complete');
    Route::post('/bookings/{booking}/cancel', [AdminBooking::class, 'cancel'])->name('bookings.cancel');

    // Payment Management
    Route::get('/payments', [AdminPayment::class, 'index'])->name('payments.index');
    Route::patch('/payments/{payment}/verify', [AdminPayment::class, 'verify'])->name('payments.verify');
    Route::patch('/payments/{payment}/reject', [AdminPayment::class, 'reject'])->name('payments.reject');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.excel');
    Route::get('/reports/export/pdf', [ReportController::class, 'exportPDF'])->name('reports.pdf');
});

// Customer Routes
Route::middleware(['auth', 'customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerDashboard::class, 'index'])->name('dashboard');

    // Studio Browsing
    Route::get('/studios', [CustomerStudio::class, 'index'])->name('studios.index');
    Route::get('/studios/{studio}', [CustomerStudio::class, 'show'])->name('studios.show');
    Route::get('/studios/{studio}/availability', [CustomerStudio::class, 'availability'])->name('studios.availability');

    // Booking Management
    Route::get('/bookings', [CustomerBooking::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create/{studio}', [CustomerBooking::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [CustomerBooking::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [CustomerBooking::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/payment', [CustomerBooking::class, 'uploadPayment'])->name('bookings.payment');
    Route::patch('/bookings/{booking}/cancel', [CustomerBooking::class, 'cancel'])->name('bookings.cancel');
});

// API Routes untuk React Components
Route::middleware('auth')->prefix('api')->group(function () {
    Route::get('/studios/{studio}/slots/{date}', [CustomerStudio::class, 'getAvailableSlots']);
    Route::get('/bookings/{booking}/qr', [CustomerBooking::class, 'getQRCode']);
});

// Redirect based on role after login
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('customer.dashboard');
        }
    })->name('dashboard');
});
