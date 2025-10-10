<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AesController extends Controller
{
    private $method = "AES-128-CBC"; 

    public function aesEncrypt()
    {
        return view('aes.encrypt');
    }

    public function aesProcess(Request $request)
    {
        $plaintext = $request->input('plaintext');
        $key = $request->input('key');

        
        if (strlen($key) !== 16) {
            return redirect()->route('aes.encrypt')
                ->with('error', 'Key harus 16 karakter untuk AES-128.');
        }

        $ciphertext = $this->encryptAES($plaintext, $key);

        return redirect()->route('aes.encrypt')->with('ciphertext', $ciphertext);
    }

    private function encryptAES($text, $key)
    {
        $iv = substr(hash('sha256', $key), 0, 16);
        $encrypted = openssl_encrypt($text, $this->method, $key, 0, $iv);
        return base64_encode($encrypted);
    }

    public function aesDecrypt()
    {
    return view('aes.decrypt');
    }

    public function aesDecryptProcess(Request $request)
    {
        $ciphertext = $request->input('ciphertext');
        $key = $request->input('key');

        if (strlen($key) !== 16) {
            return redirect()->route('aes.decrypt')
                ->with('error', 'Key harus 16 karakter untuk AES-128.');
        }

        $plaintext = $this->decryptAES($ciphertext, $key);

        return redirect()->route('aes.decrypt')->with('plaintext', $plaintext);
    }

    private function decryptAES($encryptedText, $key)
    {
        $iv = substr(hash('sha256', $key), 0, 16);
        $decrypted = openssl_decrypt(base64_decode($encryptedText), $this->method, $key, 0, $iv);
        return $decrypted ?: 'Gagal dekripsi — pastikan key dan ciphertext sesuai.';
    }

}
