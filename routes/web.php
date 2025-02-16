<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PlanningController;
use App\Http\Controllers\PrestationController;
use App\Http\Controllers\GarageController;
use App\Http\Controllers\RendezVousController;
use App\Livewire\WizardRendezVous;
use Livewire\Livewire;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/events', [EventController::class, 'index']);
Route::post('/events', [EventController::class, 'store']);
Route::delete('/events/{id}', [EventController::class, 'destroy']);


Route::resource('garages', GarageController::class);

Route::resource('prestations', PrestationController::class);

Route::get('/planning', [PlanningController::class, 'index'])->name('planning.index');

Route::get('/rendezvous/create', WizardRendezVous::class)->middleware('auth')->name('rendezvous.create');
Route::delete('/rendezvous/{id}', [RendezVousController::class, 'destroy'])->middleware('auth')->name('rendezvous.destroy');
Route::middleware(['auth'])->group(function () {
    Route::get('/rendezvous/{rendezVous}/edit', [RendezVousController::class, 'edit'])->name('rendezvous.edit');
    Route::put('/rendezvous/{rendezVous}', [RendezVousController::class, 'update'])->name('rendezvous.update');
});

Route::resource('rendezvous', RendezVousController::class);

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users');
    Route::patch('/admin/users/{user}/role', [AdminController::class, 'updateRole'])->name('admin.updateRole');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroy'])->name('admin.destroyUser');
    Route::get('/admin/users/create', [AdminController::class, 'create'])->name('admin.createUser');
    Route::post('/admin/users/store', [AdminController::class, 'store'])->name('admin.storeUser');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
