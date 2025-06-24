<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VacationHomeController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('test', function () {
    return Inertia::render('Test');
})->middleware(['auth', 'verified'])->name('test');

Route::post('create-vacation-home', [VacationHomeController::class, 'createVacationHome']);

Route::get('api/vacation-homes', [VacationHomeController::class, 'index']);


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';