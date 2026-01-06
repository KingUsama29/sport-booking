<?php

use Illuminate\Support\Facades\Route;
use App\Models\Field;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\FieldController as AdminFieldController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;

Route::get('/', function () {
    $fields = Field::latest()->take(6)->get();
    return view('welcome_bootstrap', compact('fields'));
})->name('home');

Route::get('/fields', function () {
    $fields = Field::latest()->paginate(10);
    return view('fields.index', compact('fields'));
})->name('fields.index');

Route::middleware('auth')->group(function () {
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::resource('fields', AdminFieldController::class)->except(['show']);
    Route::get('bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::patch('bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.status');
});

Route::middleware('auth')->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');


require __DIR__.'/auth.php';
