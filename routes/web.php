<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\ElementSalaireController;
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\RetenueController;
use App\Http\Controllers\BulletinSalaireController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('entreprises', EntrepriseController::class);
});

Route::middleware(['auth', 'responsable_entreprise'])->group(function () {
    Route::resource('employes', EmployeController::class);
    Route::resource('element-salaires', ElementSalaireController::class);
    Route::resource('absences', AbsenceController::class);
    Route::resource('retenues', RetenueController::class);
});

Route::middleware(['auth'])->group(function () {

    Route::get('bulletins/create', [BulletinSalaireController::class, 'create'])->name('bulletins.create');
    Route::get('bulletins/bulk-pdf', [BulletinSalaireController::class, 'bulkPdf'])->name('bulletins.bulk-pdf');
    Route::get('bulletins', [BulletinSalaireController::class, 'index'])->name('bulletins.index');
    Route::post('bulletins', [BulletinSalaireController::class, 'store'])->name('bulletins.store');
    Route::get('bulletins/{bulletin}', [BulletinSalaireController::class, 'show'])->name('bulletins.show');
    Route::get('bulletins/{bulletin}/pdf', [BulletinSalaireController::class, 'pdf'])->name('bulletins.pdf');
    Route::delete('bulletins/{bulletin}', [BulletinSalaireController::class, 'destroy'])->name('bulletins.destroy');
});

require __DIR__.'/auth.php';
