<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\AdminController;

// Routes publiques (Étudiants) 
Route::post('/demandes', [DemandeController::class, 'store']);
Route::post('/reclamations', [DemandeController::class, 'createReclamation']);
Route::post('/suivi-demandes', [DemandeController::class, 'suiviDemandes']);
Route::post('/validate-student', [DemandeController::class, 'validateStudent']);

// Routes d'authentification admin
Route::post('/admin/login', [AdminController::class, 'login']);

// Routes protégées (Admin)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/admin/logout', [AdminController::class, 'logout']);
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    
    // Historique page - finalized requests (accepted + refused)
    Route::get('/admin/historique', [AdminController::class, 'getHistorique']);
    Route::put('/admin/historique/{id}/reverser', [AdminController::class, 'reverserDemande']);
    
    // Demande page - pending requests only
    Route::get('/admin/demandes-attente', [AdminController::class, 'getDemandesAttente']);
    
    // Legacy routes (keep for backward compatibility)
    Route::get('/admin/demandes', [AdminController::class, 'getDemandes']);
    Route::get('/admin/demandes/{id}', [AdminController::class, 'getDemandeDetails']);
    Route::get('/admin/demandes/{id}/preview', [AdminController::class, 'previewPDF']);
    Route::put('/admin/demandes/{id}/valider', [AdminController::class, 'validerDemande']);
    Route::put('/admin/demandes/{id}/refuser', [AdminController::class, 'refuserDemande']);
    
    // Routes pour les réclamations
    Route::get('/admin/reclamations', [AdminController::class, 'getReclamations']);
    Route::put('/admin/reclamations/{id}/repondre', [AdminController::class, 'repondreReclamation']);
});
