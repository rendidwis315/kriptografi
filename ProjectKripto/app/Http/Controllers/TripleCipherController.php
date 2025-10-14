<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TripleCipherController extends Controller
{
    // AES mode (AES-128-CBC) — AES key harus 16 karakter
    private $aesMethod = "AES-128-CBC";

    /* -------------------------
       Views: tampilkan form
       ------------------------- */
    public function encryptForm()
    {
        // buat view 'triple.encrypt' yang berisi form dengan field:
        // plaintext, vigenere_key, aes_key
        return view('triple.encrypt');
    }

    public function decryptForm()
    {
        // buat view 'triple.decrypt' yang berisi form dengan field:
        // ciphertext, vigenere_key, aes_key
        return view('triple.decrypt');
    }

    /* -------------------------
       Proses Enkripsi
       ------------------------- */
    public function encryptProcess(Request $request)
    {
        $plaintext = $request->input('plaintext', '');
        $vigenereKey = $request->input('vigenere_key', '');
        $aesKey = $request->input('aes_key', '');

        // Validasi sederhana
        if (empty($plaintext)) {
            return redirect()->route('triple.encrypt')->with('error', 'Plaintext harus diisi.');
        }
        if (empty($vigenereKey) || !ctype_alpha($vigenereKey)) {
            return redirect()->route('triple.encrypt')->with('error', 'Vigenere key harus diisi dan hanya huruf A-Z.');
        }
        if (strlen($aesKey) !== 16) {
            return redirect()->route('triple.encrypt')->with('error', 'AES key harus 16 karakter untuk AES-128.');
        }

        // 1) ATBASH (treatment: uppercase untuk huruf)
        $step1 = $this->atbashEncrypt(strtoupper($plaintext));

        // 2) VIGENERE (menghasilkan huruf uppercase)
        $step2 = $this->vigenereEncrypt(strtoupper($step1), strtoupper($vigenereKey));

        // 3) AES (plaintext => AES binary encrypted => base64)
        $ciphertext = $this->encryptAES($step2, $aesKey);

        return redirect()->route('triple.encrypt')->with('ciphertext', $ciphertext);
    }

    /* -------------------------
       Proses Dekripsi
       ------------------------- */
    public function decryptProcess(Request $request)
    {
        $ciphertext = $request->input('ciphertext', '');
        $vigenereKey = $request->input('vigenere_key', '');
        $aesKey = $request->input('aes_key', '');

        if (empty($ciphertext)) {
            return redirect()->route('triple.decrypt')->with('error', 'Ciphertext harus diisi.');
        }
        if (empty($vigenereKey) || !ctype_alpha($vigenereKey)) {
            return redirect()->route('triple.decrypt')->with('error', 'Vigenere key harus diisi dan hanya huruf A-Z.');
        }
        if (strlen($aesKey) !== 16) {
            return redirect()->route('triple.decrypt')->with('error', 'AES key harus 16 karakter untuk AES-128.');
        }

        // 1) AES decrypt (mengembalikan teks uppercase yang berasal dari langkah Vigenere)
        $afterAes = $this->decryptAES($ciphertext, $aesKey);
        if ($afterAes === false) {
            return redirect()->route('triple.decrypt')->with('error', 'Gagal dekripsi AES — pastikan key dan ciphertext benar.');
        }

        // 2) VIGENERE decrypt
        $afterVigenere = $this->vigenereDecrypt(strtoupper($afterAes), strtoupper($vigenereKey));

        // 3) ATBASH (symmetrical)
        $plaintext = $this->atbashEncrypt($afterVigenere); // fungsi sama untuk dekripsi

        return redirect()->route('triple.decrypt')->with('plaintext', $plaintext);
    }

    /* -------------------------
       Helper: ATBASH
       ------------------------- */
    private function atbashEncrypt(string $text): string
    {
        $result = '';
        $len = strlen($text);

        for ($i = 0; $i < $len; $i++) {
            $char = $text[$i];
            if (ctype_alpha($char)) {
                // hanya A-Z diharapkan (teks sudah di-UCASE sebelum masuk)
                $cipherChar = chr( ord('Z') - (ord($char) - ord('A')) );
                $result .= $cipherChar;
            } else {
                $result .= $char;
            }
        }

        return $result;
    }

    /* -------------------------
       Helper: VIGENERE (encrypt & decrypt)
       ------------------------- */
    private function vigenereEncrypt(string $text, string $key): string
    {
        $ciphertext = '';
        $keyLength = strlen($key);
        $keyIndex = 0;

        for ($i = 0; $i < strlen($text); $i++) {
            $char = $text[$i];
            if (ctype_alpha($char)) {
                $shift = ord($key[$keyIndex % $keyLength]) - 65; // A=0
                $encrypted = ((ord($char) - 65 + $shift) % 26) + 65;
                $ciphertext .= chr($encrypted);
                $keyIndex++;
            } else {
                $ciphertext .= $char;
            }
        }

        return $ciphertext;
    }

    private function vigenereDecrypt(string $text, string $key): string
    {
        $plaintext = '';
        $keyLength = strlen($key);
        $keyIndex = 0;

        for ($i = 0; $i < strlen($text); $i++) {
            $char = $text[$i];
            if (ctype_alpha($char)) {
                $shift = ord($key[$keyIndex % $keyLength]) - 65;
                $decrypted = ((ord($char) - 65 - $shift + 26) % 26) + 65;
                $plaintext .= chr($decrypted);
                $keyIndex++;
            } else {
                $plaintext .= $char;
            }
        }

        return $plaintext;
    }

    /* -------------------------
       Helper: AES (encrypt & decrypt)
       - encryptAES: menerima plain text (STRING), mengembalikan base64 string
       - decryptAES: menerima base64 ciphertext, mengembalikan decrypted string atau false
       ------------------------- */
    private function encryptAES(string $text, string $key): string
    {
        // IV derivation from key (tetap seperti implementasimu)
        $iv = substr(hash('sha256', $key), 0, 16);
        $encrypted = openssl_encrypt($text, $this->aesMethod, $key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($encrypted);
    }

    private function decryptAES(string $base64Cipher, string $key)
    {
        $iv = substr(hash('sha256', $key), 0, 16);
        $decoded = base64_decode($base64Cipher, true);
        if ($decoded === false) {
            return false;
        }
        $decrypted = openssl_decrypt($decoded, $this->aesMethod, $key, OPENSSL_RAW_DATA, $iv);
        return $decrypted === false ? false : $decrypted;
    }
}
