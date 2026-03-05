<?php

use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home');
Route::view('/contact', 'contact')->name('contact');
Route::post('/payments/callback', [PaymentController::class, 'callback'])->name('payments.callback');

Route::middleware(['auth', 'admin'])->group(function (): void {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/dashboard/bookings', [AdminBookingController::class, 'index'])->name('admin.bookings.index');
    Route::patch('/dashboard/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('admin.bookings.status');
});

Route::middleware(['auth', 'user'])->group(function (): void {
    Route::get('/userdashboard', [BookingController::class, 'userDashboard'])->name('userdashboard');

    Route::get('/booking', [BookingController::class, 'index'])->name('booking');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::post('/booking/{booking}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
    Route::get('/booking/calendar', [BookingController::class, 'calendar'])->name('booking.calendar');

    Route::post('/booking/{booking}/review', [ReviewController::class, 'store'])->name('booking.review');

    Route::post('/payments/{booking}/initiate', [PaymentController::class, 'initiate'])->name('payments.initiate');

    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot');
    Route::post('/chatbot', [ChatbotController::class, 'proses'])->name('chatbot.proses');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
