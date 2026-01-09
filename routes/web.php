<?php

use Illuminate\Support\Facades\Route;
use App\Models\Field;

use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\FieldController as AdminFieldController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentWebhookController;

Route::get('/', function () {
    $fields = Field::latest()->take(6)->get();
    return view('welcome_bootstrap', compact('fields'));
})->name('home');

Route::get('/fields', function () {
    $fields = Field::latest()->paginate(10);
    return view('fields.index', compact('fields'));
})->name('fields.index');

Route::middleware('auth')->group(function () {
    // BOOKINGS (user)
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');

    // ✅ untuk cek jam yang sudah dibooking (AJAX)
    Route::get('/bookings/availability', [BookingController::class, 'availability'])
        ->name('bookings.availability');

    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');

    // PAYMENTS (user)
    Route::get('/payments/{booking}/checkout', [PaymentController::class, 'checkout'])->name('payments.checkout');
    Route::get('/payments/finish', [PaymentController::class, 'finish'])->name('payments.finish');

    // DASHBOARD
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// WEBHOOK Midtrans (JANGAN pakai auth)
Route::post('/payments/midtrans/notification', [PaymentWebhookController::class, 'notification'])
    ->name('payments.midtrans.notification');

// ADMIN
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::resource('fields', AdminFieldController::class)->except(['show']);
    Route::get('bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::patch('bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.status');
});

require __DIR__ . '/auth.php';
    