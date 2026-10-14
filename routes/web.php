<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\SwapRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/browse', [SkillController::class, 'index'])->name('skills.index');
    Route::post('/profile/skills', [SkillController::class, 'store'])->name('skills.store');
    Route::delete('/profile/skills/{userSkill}', [SkillController::class, 'destroy'])->name('skills.destroy');

    Route::get('/students/{user}', [ProfileController::class, 'show'])->name('students.show');

    Route::get('/swaps', [SwapRequestController::class, 'index'])->name('swaps.index');
    Route::get('/swaps/create/{user}', [SwapRequestController::class, 'create'])->name('swaps.create');
    Route::post('/swaps/{user}', [SwapRequestController::class, 'store'])->name('swaps.store');
    Route::patch('/swaps/{swapRequest}', [SwapRequestController::class, 'update'])->name('swaps.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
