<?php

use Inertia\Inertia;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VacationHomeController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('my-vacation-homes', function () {
    return Inertia::render('MyVacationHomes');
})->middleware(['auth', 'verified'])->name('my-vacation-homes');


Route::get('vacation-home-create', function () {
    return Inertia::render('CreateVacationHome');
})->middleware(['auth', 'verified'])->name('vacation-home-create');

Route::get('vacation-homes', function () {
    return Inertia::render('VacationHomes');
})->middleware(['auth', 'verified'])->name('vacation-homes');

Route::get('users', function () {
    return Inertia::render('Users');
})->middleware(['auth', 'verified'])->name('users');

Route::get('vacation-home/{id}', function ($id) {
    return Inertia::render('VacationHomePage', [
        'id' => $id,
    ]);
})->middleware(['auth', 'verified'])->name('vacation-home');

Route::post('create-vacation-home', [VacationHomeController::class, 'createVacationHome']);

Route::get('api/vacation-homes', [VacationHomeController::class, 'index']);

Route::get('api/my-vacation-homes', [VacationHomeController::class, 'myVacationHomes']);

Route::get('api/vacation-home/{id}', [VacationHomeController::class, 'show']);


Route::post('api/vacation-home/rent', [VacationHomeController::class, 'rent']);

Route::get('api/users', [UserController::class, 'index']);

Route::delete('api/vacation-home/{id}', [VacationHomeController::class, 'destroy']);


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';