<?php

use App\Http\Controllers\LaboratoryController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::redirect('/', '/dashboard');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::get('/laboratories', [LaboratoryController::class, 'index'])->name('laboratories.index');
