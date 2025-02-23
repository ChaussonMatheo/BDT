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
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\DashboardController;
use App\Models\RendezVous;
use Livewire\Livewire;
use App\Http\Controllers\GitHubController;
use App\Http\Controllers\HomeController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 🔹 Page d'accueil
route::get("/", [HomeController::class, "index"])->name("home");
// 🔹 Gestion des événements (publique)
Route::prefix('events')->group(function () {
    Route::get('/', [EventController::class, 'index']);
    Route::post('/', [EventController::class, 'store']);
    Route::delete('/{id}', [EventController::class, 'destroy']);
});

// 🔹 Gestion des garages et prestations
Route::resource('garages', GarageController::class);
Route::resource('prestations', PrestationController::class);

// 🔹 Routes publiques pour la gestion des rendez-vous
Route::get('/rendezvous/create', WizardRendezVous::class)->name('rendezvous.create');
Route::get('/rendezvous/{id}', [RendezVousController::class, 'showwithid'])->name('rendezvous.show')->middleware('auth');

// 🔹 Route statique : Page de confirmation après prise de rendez-vous
Route::get('/rendezvous/confirmation', function () {
    return view('rendezvous.confirmation');
})->name('rendezvous.confirmation');

// 🔹 Route dynamique : Gestion des rendez-vous via un token
Route::get('/rendezvous/{token}/info', function ($token) {
    $rendezVous = RendezVous::where('token', $token)->firstOrFail();
    return view('rendezvous.manage', compact('rendezVous'));
})->name('rendezvous.manage');

// 🔹 Route pour télécharger un fichier ICS du rendez-vous
Route::get('/rendezvous/{token}/download-ics', [RendezVousController::class, 'downloadICS'])
    ->name('rendezvous.download.ics');


Route::get('/rendezvous/{token}', function ($token) {
    $rendezVous = RendezVous::where('token', $token)->firstOrFail();
    return view('rendezvous.manage', compact('rendezVous'));
})->name('rendezvous.manage');

// 🔹 Routes protégées nécessitant une authentification
Route::middleware(['auth'])->group(function () {
    Route::delete('/rendezvous/{id}', [RendezVousController::class, 'destroy'])->name('rendezvous.destroy');
    Route::get('/rendezvous/{rendezVous}/edit', [RendezVousController::class, 'edit'])->name('rendezvous.edit');
    Route::put('/rendezvous/{rendezVous}', [RendezVousController::class, 'update'])->name('rendezvous.update');
    Route::post('/rendezvous/{id}/update-status', [RendezVousController::class, 'updateStatus'])->name('rendezvous.updateStatus');

    // Ressource complète des rendez-vous
    Route::resource('rendezvous', RendezVousController::class)->except('create');

    // 🔹 Dashboard et planning
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/planning', [PlanningController::class, 'index'])->name('planning.index');
});

// 🔹 Footer Info
Route::get('/footer-info', [GitHubController::class, 'footerInfo']);

// 🔹 Routes pour l'administration (auth + admin requis)
Route::middleware(['auth', 'admin'])->group(function () {
    // Disponibilités
    Route::prefix('availabilities')->group(function () {
        Route::get('/', [AvailabilityController::class, 'index'])->name('availabilities.index');
        Route::get('/create', [AvailabilityController::class, 'create'])->name('availabilities.create');
        Route::post('/', [AvailabilityController::class, 'store'])->name('availabilities.store');
        Route::get('/{availability}/edit', [AvailabilityController::class, 'edit'])->name('availabilities.edit');
        Route::put('/{availability}', [AvailabilityController::class, 'update'])->name('availabilities.update');
        Route::delete('/{availability}', [AvailabilityController::class, 'destroy'])->name('availabilities.destroy');
    });

    // Gestion des utilisateurs admin
    Route::prefix('admin/users')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.users');
        Route::patch('/{user}/role', [AdminController::class, 'updateRole'])->name('admin.updateRole');
        Route::delete('/{user}', [AdminController::class, 'destroy'])->name('admin.destroyUser');
        Route::get('/create', [AdminController::class, 'create'])->name('admin.createUser');
        Route::post('/store', [AdminController::class, 'store'])->name('admin.storeUser');
    });
});

// 🔹 Gestion du profil utilisateur (auth requis)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 🔹 Authentification Laravel (register, login, logout)
require __DIR__.'/auth.php';
