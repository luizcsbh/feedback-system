<?php

use App\Http\Controllers\CandidateController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Rotas de Autenticação
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Rotas Protegidas (requerem autenticação)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    
    

    /*
    |----------------------------------------------------------------------
    | Perfil do Usuário
    |----------------------------------------------------------------------
    */
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    /*
    |----------------------------------------------------------------------
    | Candidatos
    |----------------------------------------------------------------------
    */
    Route::prefix('candidates')->group(function () {
        // Rotas Resource padrão
        Route::resource('/', CandidateController::class)
            ->parameters(['' => 'candidate'])
            ->names('candidates');
        
        // Rotas adicionais para Candidatos
        Route::patch('/{candidate}/status', [CandidateController::class, 'updateStatus'])
            ->name('candidates.status');
        
        Route::get('/candidates/export', [CandidateController::class, 'export'])
            ->name('candidates.export');
    });

    /*
    |----------------------------------------------------------------------
    | Feedbacks
    |----------------------------------------------------------------------
    */
    Route::prefix('feedbacks')->group(function () {
    // Rotas de criação e armazenamento
    Route::get('/{candidate}/create', [FeedbackController::class, 'create'])->name('feedbacks.create');
    Route::post('/{candidate}/store', [FeedbackController::class, 'store'])->name('feedbacks.store');

    // Rotas para feedbacks específicos
    Route::prefix('{feedback}')->group(function () {
        Route::get('/', [FeedbackController::class, 'show'])->name('feedbacks.show');
        Route::get('/edit', [FeedbackController::class, 'edit'])->name('feedbacks.edit');
        Route::put('/', [FeedbackController::class, 'update'])->name('feedbacks.update');
        Route::delete('/', [FeedbackController::class, 'destroy'])->name('feedbacks.destroy');
        Route::post('/send', [FeedbackController::class, 'send'])->name('feedbacks.send');
    });
});

});