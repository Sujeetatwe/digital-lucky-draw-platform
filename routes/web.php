<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ParticipantController;
use Illuminate\Support\Facades\Route;

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'mr'])) {
        session(['locale' => $locale]);
    }
    return back();
})->name('lang.switch');

Route::get('/', function () {
    return redirect()->route('login');
});

// Public Registration
use App\Http\Controllers\PublicRegistrationController;
use App\Http\Controllers\WinningGiftController;
use App\Http\Controllers\WinnerController;

Route::get('/register-participant', [PublicRegistrationController::class, 'create'])->name('public.register');
Route::post('/register-participant', [PublicRegistrationController::class, 'store'])->name('public.store');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [ParticipantController::class, 'dashboard'])->name('dashboard');

    // Participant Management
    Route::get('/participants', [ParticipantController::class, 'index'])->name('participants.index');
    Route::get('/participants/pending', [ParticipantController::class, 'pending'])->name('participants.pending');
    Route::post('/participants', [ParticipantController::class, 'store'])->name('participants.store');
    Route::post('/participants/{id}/approve', [ParticipantController::class, 'approve'])->name('participants.approve');
    Route::put('/participants/{id}', [ParticipantController::class, 'update'])->name('participants.update');
    Route::delete('/participants/{id}', [ParticipantController::class, 'destroy'])->name('participants.destroy');

    // Trashed participants
    Route::get('/participants/trashed', [ParticipantController::class, 'trashed'])->name('participants.trashed');
    Route::post('/participants/{id}/restore', [ParticipantController::class, 'restore'])->name('participants.restore');
    Route::delete('/participants/{id}/force-delete', [ParticipantController::class, 'forceDelete'])->name('participants.forceDelete');

    // Export
    Route::get('/participants/export', [ParticipantController::class, 'export'])->name('participants.export');
    Route::get('/participants/export-pending', [ParticipantController::class, 'exportPending'])->name('participants.export_pending');

    // User Management (Admin Only)
    Route::get('/admin/users', [\App\Http\Controllers\AdminUserManagementController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/users', [\App\Http\Controllers\AdminUserManagementController::class, 'store'])->name('admin.users.store');
    Route::put('/admin/users/{user}', [\App\Http\Controllers\AdminUserManagementController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [\App\Http\Controllers\AdminUserManagementController::class, 'destroy'])->name('admin.users.destroy');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');



    // Main Page (Master, Assignment, List)
    Route::get('/winning-gifts', [WinningGiftController::class, 'index'])->name('winning-gifts.index');

    // Gift Master
    Route::post('/winning-gifts', [WinningGiftController::class, 'store'])->name('winning-gifts.store');
    Route::put('/winning-gifts/{winningGift}', [WinningGiftController::class, 'update'])->name('winning-gifts.update');
    Route::delete('/winning-gifts/{winningGift}', [WinningGiftController::class, 'destroy'])->name('winning-gifts.destroy');

    // Winner Assignment API
    Route::get('/api/winners/search', [WinnerController::class, 'search'])->name('winners.search');
    Route::post('/api/winners/assign', [WinnerController::class, 'store'])->name('winners.assign');
    Route::get('/api/winners', [WinnerController::class, 'index'])->name('winners.index'); // For loading table
    Route::get('/winners/export', [WinnerController::class, 'export'])->name('winners.export');
    Route::get('/api/check-token', [ParticipantController::class, 'checkToken'])->name('api.check-token');
});

Route::get('/api/check-mobile', [ParticipantController::class, 'checkMobile'])->name('api.check-mobile');

require __DIR__ . '/auth.php';
