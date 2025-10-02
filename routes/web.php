<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CifraController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DevocionalController;
use App\Http\Middleware\AdminMiddleware;

Route::middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::resource('song', SongController::class);
    Route::resource('groups', GroupController::class);
    Route::resource('schedules', ScheduleController::class);
    Route::get('/songs/suggest', [SongController::class, 'suggest'])->name('songs.suggest');
    Route::resource('user', UserController::class)->middleware(AdminMiddleware::class);
});

Auth::routes();

Route::resource('songs', SongController::class);
Route::get('/songs-all', [SongController::class, 'allSongs'])->name('songs.all');
Route::middleware(['auth'])->group(function () {
    Route::resource('group', GroupController::class);
    Route::get('/songs/create/suggestion', [SongController::class, 'createSuggestion'])->name('songs.create.suggestion');
});
Route::middleware(['auth'])->group(function () {
    Route::resource('schedule', ScheduleController::class);
    Route::get('/minhas-escalas', [ScheduleController::class, 'userNextSchedule'])->name('schedule.user');
    Route::post('/schedule/{schedule}/update-order', [ScheduleController::class, 'updateSongsOrder'])->name('schedule.update-order');
});

Auth::routes();

// Remova ou comente esta linha duplicada
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/songs/search', [SongController::class, 'search'])->name('songs.search');
Route::middleware(['auth'])->group(function () {
    Route::get('/user/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::resource('categories', CategoryController::class)->except(['edit', 'update', 'show']);
});
Route::post('/songs/{song}/update-lyrics', [SongController::class, 'updateLyrics'])->name('songs.update-lyrics');
Route::post('/songs/{song}/update-chords', [SongController::class, 'updateChords'])->name('songs.update-chords');
Route::get('/schedule/{schedule}/setlist', [ScheduleController::class, 'setlist'])->name('schedule.setlist');
Route::get('/api/songs/{song}/lyrics', [SongController::class, 'getLyrics']);
Route::get('/api/songs/{song}/chords', [SongController::class, 'getChords']);

Route::resource('cifras', CifraController::class);

// Rotas de Devocionais - Apenas para usuários autenticados
Route::middleware(['auth'])->group(function () {
    Route::get('/devocionais', [DevocionalController::class, 'index'])->name('devocionais.index');
    Route::get('/devocionais/create', [DevocionalController::class, 'create'])->name('devocionais.create');
    Route::post('/devocionais', [DevocionalController::class, 'store'])->name('devocionais.store');
    Route::get('/devocionais/{devocional}', [DevocionalController::class, 'show'])->name('devocionais.show');
    Route::get('/devocionais/{devocional}/edit', [DevocionalController::class, 'edit'])->name('devocionais.edit');
    Route::put('/devocionais/{devocional}', [DevocionalController::class, 'update'])->name('devocionais.update');
    Route::delete('/devocionais/{devocional}', [DevocionalController::class, 'destroy'])->name('devocionais.destroy');
});

// Rotas públicas dos devocionais
Route::get('/blog', [DevocionalController::class, 'publicIndex'])->name('devocionais.public.index');
Route::get('/blog/{devocional}', [DevocionalController::class, 'publicShow'])->name('devocionais.public.show');

// Rota para teste de e-mail
Route::get('/test-email', function () {
    try {
        \Illuminate\Support\Facades\Mail::raw('Este é um e-mail de teste enviado pelo sistema Louvor.', function ($message) {
            $message->to('wellington.freitas@totaltargets.com.br')
                    ->subject('Teste de E-mail - Sistema Louvor');
        });

        return response()->json([
            'success' => true,
            'message' => 'E-mail enviado com sucesso!'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erro ao enviar e-mail: ' . $e->getMessage()
        ]);
    }
})->name('test.email');
