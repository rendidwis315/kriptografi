<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CipherController;
use App\Http\Controllers\AtbashController;

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

// Vigenere
Route::get('/enkripsi/vigenere', [CipherController::class, 'vigenereEncrypt'])->name('vigenere.encrypt');
// proses enkripsi (POST)
Route::post('/enkripsi/vigenere', [CipherController::class, 'vigenereProcess'])->name('vigenere.process');

// Atbash
Route::get('/enkripsi/atbash', [AtbashController::class, 'atbashEncrypt'])->name('atbash.encrypt');
Route::post('/enkripsi/atbash', [AtbashController::class, 'atbashProcess'])->name('atbash.process');

Route::get('/dekripsi/atbash', [AtbashController::class, 'atbashDecrypt'])->name('atbash.decrypt');
Route::post('/dekripsi/atbash', [AtbashController::class, 'atbashProcess'])->name('atbash.process');