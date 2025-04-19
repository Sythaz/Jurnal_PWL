<?php

use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
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

Route::get('/', [WelcomeController::class, 'index']);

Route::group(['prefix' => 'user'], function () {
    Route::get('/', [UserController::class, 'index']);          // <enampilkan halaman awal user
    Route::post('/list', [UserController::class, 'list']);      // Menampilkan data user dalam bentuk json untuk datatables
    Route::post('/', [UserController::class, 'store']);         // menyimpan data user baru
    Route::get('/create_ajax', [UserController::class, 'create_ajax']);     // Menampilkan halaman form tambah user Ajax
    Route::post('/ajax', [UserController::class, 'store_ajax']);            // Menyimpan data user baru Ajax
    Route::put('/{id}', [UserController::class, 'update']);     // menyimpan perubahan data user
    Route::get('/{id}/show_ajax', [UserController::class, 'show_ajax']);       // menampilkan detail user
    Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']);        // Menampilkan halaman form edit user Ajax
    Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']);   // Menyimpan perubahan data user Ajax
    Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']);  // Untuk tampilkan form confirm delete user Ajax
    Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']); // Untuk hapus data user Ajax
    Route::delete('/{id}', [UserController::class, 'destroy']); // menghapus data user

});

Route::group(['prefix' => 'level'], function () {
    Route::get('/', [LevelController::class, 'index']);          // <enampilkan halaman awal level
    Route::post('/list', [LevelController::class, 'list']);      // Menampilkan data level dalam bentuk json untuk datatables
    Route::post('/', [LevelController::class, 'store']);         // menyimpan data level baru
    Route::get('/create_ajax', [LevelController::class, 'create_ajax']);     // Menampilkan halaman form tambah level Ajax
    Route::post('/ajax', [LevelController::class, 'store_ajax']);            // Menyimpan data level baru Ajax
    Route::put('/{id}', [LevelController::class, 'update']);     // menyimpan perubahan data level
    Route::get('/{id}/show_ajax', [LevelController::class, 'show_ajax']);       // menampilkan detail level
    Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']);        // Menampilkan halaman form edit level Ajax
    Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax']);   // Menyimpan perubahan data level Ajax
    Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']);  // Untuk tampilkan form confirm delete level Ajax
    Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax']); // Untuk hapus data level Ajax
    Route::delete('/{id}', [LevelController::class, 'destroy']); // menghapus data level
});

Route::group(['prefix' => 'kegiatan'], function () {
    Route::get('/', [KegiatanController::class, 'index']);          // <enampilkan halaman awal kegiatan
    Route::post('/list', [KegiatanController::class, 'list']);      // Menampilkan data kegiatan dalam bentuk json untuk datatables
    Route::post('/', [KegiatanController::class, 'store']);         // menyimpan data kegiatan baru
    Route::get('/create_ajax', [KegiatanController::class, 'create_ajax']);     // Menampilkan halaman form tambah kegiatan Ajax
    Route::post('/ajax', [KegiatanController::class, 'store_ajax']);            // Menyimpan data kegiatan baru Ajax
    Route::put('/{id}', [KegiatanController::class, 'update']);     // menyimpan perubahan data kegiatan
    Route::get('/{id}/show_ajax', [KegiatanController::class, 'show_ajax']);       // menampilkan detail kegiatan
    Route::get('/{id}/edit_ajax', [KegiatanController::class, 'edit_ajax']);        // Menampilkan halaman form edit kegiatan Ajax
    Route::put('/{id}/update_ajax', [KegiatanController::class, 'update_ajax']);   // Menyimpan perubahan data kegiatan Ajax
    Route::get('/{id}/delete_ajax', [KegiatanController::class, 'confirm_ajax']);  // Untuk tampilkan form confirm delete kegiatan Ajax
    Route::delete('/{id}/delete_ajax', [KegiatanController::class, 'delete_ajax']); // Untuk hapus data kegiatan Ajax
    Route::delete('/{id}', [KegiatanController::class, 'destroy']); // menghapus data kegiatan
});