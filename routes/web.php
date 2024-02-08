<?php

use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TKelasController;
use App\Http\Controllers\TSiswaController;
use Illuminate\Support\Facades\Route;

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
    // return view('welcome');
    // return view('home.index');
    return redirect(route('dashboard'));
})->name('home.index');

Route::get('/dashboard', function () {
    // return view('dashboard');
    return redirect(route('users.index'));
})->middleware(['auth', 'verified', 'notSuspended'])->name('dashboard');

Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

Route::post('register', [RegisteredUserController::class, 'store']);

require __DIR__.'/auth.php';

Route::post('/preference', [PreferenceController::class, 'store'])->name('preference.store');

Route::get('/account-suspended', function () {
    return view('account-suspended.index', [
        'backURL' => route('home.index')
    ]);
})->middleware('auth')->name('account-suspended');

Route::middleware(['auth', 'notSuspended'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('folders/{folder}/delete', [FolderController::class, 'delete'])->name('folders.delete');
    Route::get('folders/preferences', [FolderController::class, 'preferences'])->name('folders.preferences');
    Route::post('folders/preferences', [FolderController::class, 'applyPreferences'])->name('folders.applyPreferences');
    Route::resource('folders', FolderController::class)->except(['index', 'show']);

    Route::get('tasks/{task}/delete', [TaskController::class, 'delete'])->name('tasks.delete');
    Route::get('tasks/search', [TaskController::class, 'search'])->name('tasks.search');
    Route::get('tasks/preferences', [TaskController::class, 'preferences'])->name('tasks.preferences');
    Route::post('tasks/preferences', [TaskController::class, 'applyPreferences'])->name('tasks.applyPreferences');
    Route::resource('tasks', TaskController::class)->except('edit');
    Route::get('tasks/{task}/edit', [TaskController::class, 'index'])->name('tasks.edit');

    Route::middleware('admin')->group(function() {

        Route::get('users/{user}/deleteAvatar', [RegisteredUserController::class, 'deleteAvatar'])->name('users.deleteAvatar');
        Route::post('users/{user}/destroyAvatar', [RegisteredUserController::class, 'destroyAvatar'])->name('users.destroyAvatar');
        Route::get('users/{user}/delete', [RegisteredUserController::class, 'delete'])->name('users.delete');
        Route::get('users/search', [RegisteredUserController::class, 'search'])->name('users.search');
        Route::get('users/preferences', [RegisteredUserController::class, 'preferences'])->name('users.preferences');
        Route::post('users/preferences', [RegisteredUserController::class, 'applyPreferences'])->name('users.applyPreferences');
        Route::resource('users', RegisteredUserController::class)->except('show');

        Route::get('t_kelas/', [TKelasController::class, 'index'])->name('t_kelas.index');
        Route::get('t_kelas/create', [TKelasController::class, 'create'])->name('t_kelas.create');
        Route::post('t_kelas/store', [TKelasController::class, 'store'])->name('t_kelas.store');
        Route::get('t_kelas/{id}/edit', [TKelasController::class, 'edit'])->name('t_kelas.edit');
        Route::patch('t_kelas/{id}/update', [TKelasController::class, 'update'])->name('t_kelas.update');
        Route::get('t_kelas/{id}/delete', [TKelasController::class, 'delete'])->name('t_kelas.delete');
        Route::delete('t_kelas/{id}/destroy', [TKelasController::class, 'destroy'])->name('t_kelas.destroy');
        Route::get('t_kelas/search', [TKelasController::class, 'search'])->name('t_kelas.search');
        Route::get('t_kelas/preferences', [TKelasController::class, 'preferences'])->name('t_kelas.preferences');

        Route::get('t_siswa/', [TSiswaController::class, 'index'])->name('t_siswa.index');
        Route::get('t_siswa/create', [TSiswaController::class, 'create'])->name('t_siswa.create');
        Route::post('t_siswa/store', [TSiswaController::class, 'store'])->name('t_siswa.store');
        Route::get('t_siswa/{id}/edit', [TSiswaController::class, 'edit'])->name('t_siswa.edit');
        Route::patch('t_siswa/{id}/update', [TSiswaController::class, 'update'])->name('t_siswa.update');
        Route::get('t_siswa/{id}/delete', [TSiswaController::class, 'delete'])->name('t_siswa.delete');
        Route::delete('t_siswa/{id}/destroy', [TSiswaController::class, 'destroy'])->name('t_siswa.destroy');
        Route::get('t_siswa/search', [TSiswaController::class, 'search'])->name('t_siswa.search');
        Route::get('t_siswa/preferences', [TSiswaController::class, 'preferences'])->name('t_siswa.preferences');
        Route::post('t_siswa/preferences', [TSiswaController::class, 'applyPreferences'])->name('t_siswa.applyPreferences');
        Route::post('t_siswa/preferences', [TSiswaController::class, 'applyPreferences'])->name('t_siswa.applyPreferences');
    });
});
