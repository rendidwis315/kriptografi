<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CipherController;
use App\Http\Controllers\AesController;
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

//enkripsi vigenere
Route::get('/enkripsi/vigenere', [CipherController::class, 'vigenereEncrypt'])->name('vigenere.encrypt');
Route::post('/enkripsi/vigenere', [CipherController::class, 'vigenereProcess'])->name('vigenere.process');

//decrypt vigenere 
Route::get('/dekripsi/vigenere', [CipherController::class, 'vigenereDecrypt'])->name('vigenere.decrypt');
Route::post('/dekripsi/vigenere', [CipherController::class, 'vigenereDecryptProcess'])->name('vigenere.decrypt.process');

// enkripsi aes
Route::get('/enkripsi/aes', [AesController::class, 'aesEncrypt'])->name('aes.encrypt');
Route::post('/enkripsi/aes', [AesController::class, 'aesProcess'])->name('aes.process');

// dekripsi aes
Route::get('/dekripsi/aes', [AesController::class, 'aesDecrypt'])->name('aes.decrypt');
Route::post('/dekripsi/aes', [AesController::class, 'aesDecryptProcess'])->name('aes.decrypt.process');

// Enkripsi Atbash
Route::get('/enkripsi/atbash', [AtbashController::class, 'atbashEncrypt'])->name('atbash.encrypt');
Route::post('/enkripsi/atbash', [AtbashController::class, 'atbashProcess'])->name('atbash.process');

// Dekripsi Atbash
Route::get('/dekripsi/atbash', [AtbashController::class, 'atbashDecrypt'])->name('atbash.decrypt');
Route::post('/dekripsi/atbash', [AtbashController::class, 'atbashProcess'])->name('atbash.decrypt.process');