<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CipherController;
use App\Http\Controllers\AesController;
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


Route::get('/enkripsi/vigenere', [CipherController::class, 'vigenereEncrypt'])->name('vigenere.encrypt');
// proses enkripsi (POST)
Route::post('/enkripsi/vigenere', [CipherController::class, 'vigenereProcess'])->name('vigenere.process');
// enkripsi aes
Route::get('/enkripsi/aes', [AesController::class, 'aesEncrypt'])->name('aes.encrypt');
Route::post('/enkripsi/aes', [AesController::class, 'aesProcess'])->name('aes.process');

// dekripsi aes
Route::get('/dekripsi/aes', [AesController::class, 'aesDecrypt'])->name('aes.decrypt');
Route::post('/dekripsi/aes', [AesController::class, 'aesDecryptProcess'])->name('aes.decrypt.process');