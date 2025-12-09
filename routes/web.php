<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CustomFieldController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth')->group(function () {

    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/list', [ContactController::class, 'list'])->name('contacts.list');
    Route::post('/contacts/store', [ContactController::class, 'store'])->name('contacts.store');
    Route::get('/contacts/{contact}/edit', [ContactController::class, 'edit'])->name('contacts.edit');
    Route::post('/contacts/update/{contact}', [ContactController::class, 'update'])->name('contacts.update');
    Route::get('/contacts/search', [ContactController::class, 'search'])->name('contacts.search');
    Route::delete('/contacts/delete/{contact}', [ContactController::class, 'delete'])->name('contacts.delete');

    Route::post('/contacts/merge', [ContactController::class, 'mergePerform'])->name('contacts.merge');

    // Custom Fields
    Route::get('/custom-fields', [CustomFieldController::class, 'index'])->name('custom-fields.index');
    Route::post('/custom-fields/store', [CustomFieldController::class, 'store'])
        ->name('custom-fields.store');
    Route::get('/custom-fields/list', [CustomFieldController::class, 'list'])
        ->name('custom-fields.list');
    Route::get('/custom-fields/{customField}/edit', [CustomFieldController::class, 'edit'])
        ->name('custom-fields.edit');
    Route::post('/custom-fields/update/{customField}', [CustomFieldController::class, 'update'])
        ->name('custom-fields.update');
    Route::delete('/custom-fields/delete/{customField}', [CustomFieldController::class, 'destroy'])
        ->name('custom-fields.delete');

});

require __DIR__ . '/auth.php';
