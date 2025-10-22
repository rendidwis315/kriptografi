<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TripleCipherController extends Controller
{
    private $aesMethod = "AES-128-CBC";

    public function encryptForm()
    {
        return view('triple.encrypt');
    }

    public function decryptForm()
    {
        return view('triple.decrypt');
    }

    public function encryptProcess(Request $request)
    {
        $plaintext = $request->input('plaintext', '');
        $mainKey = $request->input('key', '');

        if (empty($plaintext) || empty($mainKey)) {
            return redirect()->route('triple.encrypt')->with('error', 'Plaintext dan kunci harus diisi.');
        }

        // Konsisten dengan CipherController
        $vigenereKey = $this->normalizeKeyToLetters(str_replace(' ', '', $mainKey));

        if ($vigenereKey === '') {
            return redirect()->route('triple.encrypt')->with('error', 'Kunci harus mengandung huruf.');
        }

        // Konsisten dengan AESController
        $aesKey = $this->deriveAesKey($mainKey);

        // 1️⃣ Atbash
        $step1 = $this->atbashWithCase($plaintext);

        // 2️⃣ Vigenère
        $step2 = $this->vigenereWithCase($step1, $vigenereKey);

        // 3️⃣ AES
        $ciphertext = $this->encryptAES($step2, $aesKey);

        return redirect()->route('triple.encrypt')->with('ciphertext', $ciphertext);
    }

    public function decryptProcess(Request $request)
    {
        $ciphertext = $request->input('ciphertext', '');
        $mainKey = $request->input('key', '');

        if (empty($ciphertext) || empty($mainKey)) {
            return redirect()->route('triple.decrypt')->with('error', 'Ciphertext dan kunci harus diisi.');
        }

        $vigenereKey = $this->normalizeKeyToLetters(str_replace(' ', '', $mainKey));
        $aesKey = $this->deriveAesKey($mainKey);

        // 1️⃣ AES decrypt
        $afterAes = $this->decryptAES($ciphertext, $aesKey);
        if ($afterAes === false) {
            return redirect()->route('triple.decrypt')->with('error', 'Gagal dekripsi AES.');
        }

        // 2️⃣ Vigenère decrypt
        $afterVigenere = $this->vigenereDecryptWithCase($afterAes, $vigenereKey);

        // 3️⃣ Atbash decrypt (simetris)
        $plaintext = $this->atbashWithCase($afterVigenere);

        return redirect()->route('triple.decrypt')->with('plaintext', $plaintext);
    }

    /* --- Helper: AES Key --- */
    private function deriveAesKey(string $key): string
    {
        return strlen($key) === 16
            ? $key
            : substr(hash('sha256', $key), 0, 16);
    }

    /* --- AES --- */
    private function encryptAES(string $text, string $key): string
    {
        $iv = substr(hash('sha256', $key), 0, 16);
        $encrypted = openssl_encrypt($text, $this->aesMethod, $key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($encrypted);
    }

    private function decryptAES(string $base64Cipher, string $key)
    {
        $iv = substr(hash('sha256', $key), 0, 16);
        $decoded = base64_decode($base64Cipher, true);
        if ($decoded === false) return false;
        $decrypted = openssl_decrypt($decoded, $this->aesMethod, $key, OPENSSL_RAW_DATA, $iv);
        return $decrypted === false ? false : $decrypted;
    }

    /* --- Atbash --- */
    private function atbashWithCase(string $text): string
    {
        $result = '';
        foreach (str_split($text) as $char) {
            if (ctype_upper($char)) {
                $result .= chr(ord('Z') - (ord($char) - ord('A')));
            } elseif (ctype_lower($char)) {
                $result .= chr(ord('z') - (ord($char) - ord('a')));
            } else {
                $result .= $char;
            }
        }
        return $result;
    }

    /* --- Vigenère --- */
    private function vigenereWithCase(string $text, string $key): string
    {
        $ciphertext = '';
        $filteredKey = strtoupper($key);
        $keyLen = strlen($filteredKey);
        $j = 0;

        foreach (str_split($text) as $char) {
            if (ctype_alpha($char)) {
                $isUpper = ctype_upper($char);
                $shift = ord($filteredKey[$j % $keyLen]) - 65;
                $base = $isUpper ? ord('A') : ord('a');
                $ciphertext .= chr((ord($char) - $base + $shift) % 26 + $base);
                $j++;
            } else {
                $ciphertext .= $char;
            }
        }
        return $ciphertext;
    }

    private function vigenereDecryptWithCase(string $text, string $key): string
    {
        $plaintext = '';
        $filteredKey = strtoupper($key);
        $keyLen = strlen($filteredKey);
        $j = 0;

        foreach (str_split($text) as $char) {
            if (ctype_alpha($char)) {
                $isUpper = ctype_upper($char);
                $shift = ord($filteredKey[$j % $keyLen]) - 65;
                $base = $isUpper ? ord('A') : ord('a');
                $plaintext .= chr((ord($char) - $base - $shift + 26) % 26 + $base);
                $j++;
            } else {
                $plaintext .= $char;
            }
        }
        return $plaintext;
    }

    private function normalizeKeyToLetters(string $key): string
    {
        $result = '';
        foreach (str_split($key) as $char) {
            $code = ord($char);
            $result .= chr(($code % 26) + 65);
        }
        return $result;
    }
}