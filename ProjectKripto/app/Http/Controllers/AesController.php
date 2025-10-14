<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AesController extends Controller
{
    private $method = "AES-128-CBC";

    /* -------------------------
       Halaman Enkripsi
    ------------------------- */
    public function aesEncrypt()
    {
        return view('aes.encrypt');
    }

    public function aesProcess(Request $request)
    {
        $plaintext = $request->input('plaintext', '');
        $key = $request->input('key', '');

        if (empty($plaintext)) {
            return redirect()->route('aes.encrypt')->with('error', 'Plaintext harus diisi.');
        }

        if (empty($key)) {
            return redirect()->route('aes.encrypt')->with('error', 'Kunci harus diisi.');
        }

        // Key tetap apa adanya (tidak hapus spasi atau normalisasi kapital)
        $aesKey = $this->deriveAesKey($key);

        $ciphertext = $this->encryptAES($plaintext, $aesKey);

        return redirect()->route('aes.encrypt')->with('ciphertext', $ciphertext);
    }

    /* -------------------------
       Halaman Dekripsi
    ------------------------- */
    public function aesDecrypt()
    {
        return view('aes.decrypt');
    }

    public function aesDecryptProcess(Request $request)
    {
        $ciphertext = $request->input('ciphertext', '');
        $key = $request->input('key', '');

        if (empty($ciphertext)) {
            return redirect()->route('aes.decrypt')->with('error', 'Ciphertext harus diisi.');
        }

        if (empty($key)) {
            return redirect()->route('aes.decrypt')->with('error', 'Kunci harus diisi.');
        }

        // Key tetap apa adanya (tidak hapus spasi atau normalisasi kapital)
        $aesKey = $this->deriveAesKey($key);

        $plaintext = $this->decryptAES($ciphertext, $aesKey);

        return redirect()->route('aes.decrypt')->with('plaintext', $plaintext);
    }

    /* -------------------------
       Helper: AES
    ------------------------- */
    private function deriveAesKey(string $key): string
    {
        // Jika panjang key tidak 16 karakter, buat seed hash
        return strlen($key) === 16 ? $key : substr(hash('sha256', $key), 0, 16);
    }

    private function encryptAES(string $text, string $key): string
    {
        $iv = substr(hash('sha256', $key), 0, 16);
        $encrypted = openssl_encrypt($text, $this->method, $key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($encrypted);
    }

    private function decryptAES(string $base64Cipher, string $key): string
    {
        $iv = substr(hash('sha256', $key), 0, 16);
        $decoded = base64_decode($base64Cipher, true);

        if ($decoded === false) {
            return 'Ciphertext tidak valid (bukan base64).';
        }

        $decrypted = openssl_decrypt($decoded, $this->method, $key, OPENSSL_RAW_DATA, $iv);
        return $decrypted === false ? 'Gagal dekripsi — pastikan key dan ciphertext sesuai.' : $decrypted;
    }
}
