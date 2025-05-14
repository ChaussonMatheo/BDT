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
use App\Http\Controllers\GarageReservationController;
use App\Models\RendezVous;
use Livewire\Livewire;
use App\Http\Controllers\GitHubController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\Admin\UploadController as AdminUploadController;
use App\Http\Controllers\HomeController;
use Laravel\Socialite\Facades\Socialite;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::get('/rendezvous/confirmation', function () {
    return view('rendezvous.confirmation');
})->name('rendezvous.confirm');

Route::get('auth/{provider}', function ($provider) {
    return Socialite::driver($provider)->redirect();
});

Route::get('auth/{provider}/callback', function ($provider) {
    try {
        $socialUser = Socialite::driver($provider)->user();

        // Vérifier si l'utilisateur existe déjà
        $user = User::where('provider_id', $socialUser->getId())
            ->orWhere('email', $socialUser->getEmail())
            ->first();

        if (!$user) {
            // Créer un nouvel utilisateur
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
                'password' => bcrypt(uniqid()), // Générer un mot de passe aléatoire
            ]);
        }

        // Connecter l'utilisateur
        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Connexion réussie !');

    } catch (\Exception $e) {
        return redirect('/login')->with('error', 'Erreur lors de l’authentification : ' . $e->getMessage());
    }
});


Route::prefix('up')->group(function () {
    Route::get('/uploads', [AdminUploadController::class, 'index'])->name('admin.uploads.index');
    Route::get('/uploads/create', [AdminUploadController::class, 'create'])->name('admin.uploads.create');
    Route::post('/uploads', [AdminUploadController::class, 'store'])->name('admin.uploads.store');
    Route::get('/uploads/{upload}', [AdminUploadController::class, 'show'])->name('admin.uploads.show');
    Route::delete('/uploads/{upload}', [AdminUploadController::class, 'destroy'])->name('admin.uploads.destroy');
});

// 🔹 Page d'accueil
route::get("/", [HomeController::class, "index"])->name("home");
// 🔹 Gestion des événements (publique)
Route::prefix('events')->group(function () {
    Route::get('/', [EventController::class, 'index']);
    Route::post('/', [EventController::class, 'store']);
    Route::delete('/{id}', [EventController::class, 'destroy']);
});
Route::get('/upload/{uuid}', [UploadController::class, 'showForm']);
Route::post('/upload/{uuid}', [UploadController::class, 'storeImages']);

Route::get('/admin/uploads', [AdminController::class, 'index']); // liste uploads
Route::get('/admin/uploads/{id}', [AdminController::class, 'show']); // voir les images

// 🔹 Gestion des garages et prestations
Route::resource('garages', GarageController::class);
Route::resource('prestations', PrestationController::class);

// 🔹 Routes publiques pour la gestion des rendez-vous
Route::get('/rendezvous/create', WizardRendezVous::class)->name('rendezvous.create');
Route::get('/rendezvous/{id}', [RendezVousController::class, 'showwithid'])->name('rendezvous.show')->middleware('auth');



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

Route::get('/reservations-garage/create', [GarageReservationController::class, 'create'])->name('garage-reservations.create');
Route::post('/reservations-garage', [GarageReservationController::class, 'store'])->name('garage-reservations.store');
Route::get('/reservations-garage/{id}/edit', [GarageReservationController::class, 'edit'])->name('garage-reservations.edit');
Route::put('/reservations-garage/{id}', [GarageReservationController::class, 'update'])->name('garage-reservations.update');
Route::get('/reservations-garage/{id}/facture', [GarageReservationController::class, 'facture'])->name('garage-reservations.facture');


// 🔹 Authentification Laravel (register, login, logout)
require __DIR__.'/auth.php';
