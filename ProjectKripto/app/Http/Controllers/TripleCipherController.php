<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TripleCipherController extends Controller
{
    private $aesMethod = "AES-128-CBC";

    /* -------------------------
       Tampilan Halaman
       ------------------------- */
    public function encryptForm()
    {
        return view('triple.encrypt');
    }

    public function decryptForm()
    {
        return view('triple.decrypt');
    }

    /* -------------------------
       Proses Enkripsi
       ------------------------- */
    public function encryptProcess(Request $request)
    {
        $plaintext = $request->input('plaintext', '');
        $mainKey = $request->input('key', '');

        if (empty($plaintext) || empty($mainKey)) {
            return redirect()->route('triple.encrypt')->with('error', 'Plaintext dan kunci harus diisi.');
        }

        // -------------------
        // Derivasi key
        // -------------------
        // Vigenère key hanya huruf besar, spasi dihapus
        $vigenereKey = strtoupper(preg_replace('/[^A-Za-z]/', '', $mainKey));
        if ($vigenereKey === '') {
            return redirect()->route('triple.encrypt')->with('error', 'Kunci harus mengandung huruf untuk Vigenère.');
        }

        // AES key: gunakan apa adanya (huruf, angka, simbol, spasi)
        $aesKey = $this->generateAESKey($mainKey);

        // -------------------
        // 1) ATBASH (huruf besar, hasil mengikuti kapitalisasi plaintext)
        // -------------------
        $step1 = $this->atbashWithCase($plaintext);

        // -------------------
        // 2) VIGENÈRE (plaintext huruf besar untuk proses, hasil mengikuti kapitalisasi step1)
        // -------------------
        $step2 = $this->vigenereWithCase($step1, $vigenereKey);

        // -------------------
        // 3) AES (tidak menormalisasi kapital, key sesuai aesKey)
        // -------------------
        $ciphertext = $this->encryptAES($step2, $aesKey);

        return redirect()->route('triple.encrypt')->with('ciphertext', $ciphertext);
    }

    /* -------------------------
       Proses Dekripsi
       ------------------------- */
    public function decryptProcess(Request $request)
    {
        $ciphertext = $request->input('ciphertext', '');
        $mainKey = $request->input('key', '');

        if (empty($ciphertext) || empty($mainKey)) {
            return redirect()->route('triple.decrypt')->with('error', 'Ciphertext dan kunci harus diisi.');
        }

        // Derivasi key
        $vigenereKey = strtoupper(preg_replace('/[^A-Za-z]/', '', $mainKey));
        if ($vigenereKey === '') {
            return redirect()->route('triple.decrypt')->with('error', 'Kunci harus mengandung huruf untuk Vigenère.');
        }
        $aesKey = $this->generateAESKey($mainKey);

        // -------------------
        // 1) AES decrypt
        // -------------------
        $afterAes = $this->decryptAES($ciphertext, $aesKey);
        if ($afterAes === false) {
            return redirect()->route('triple.decrypt')->with('error', 'Gagal dekripsi AES — pastikan kunci dan ciphertext benar.');
        }

        // -------------------
        // 2) VIGENÈRE decrypt (mengikuti kapitalisasi step)
        // -------------------
        $afterVigenere = $this->vigenereDecryptWithCase($afterAes, $vigenereKey);

        // -------------------
        // 3) ATBASH decrypt (simetris)
        // -------------------
        $plaintext = $this->atbashWithCase($afterVigenere);

        return redirect()->route('triple.decrypt')->with('plaintext', $plaintext);
    }

    /* -------------------------
       Helper: AES Key Generator
       ------------------------- */
    private function generateAESKey(string $key): string
    {
        // jika panjang >=16 karakter, gunakan apa adanya
        if (strlen($key) >= 16) {
            return substr($key, 0, 16);
        }
        // jika kurang, bangkitkan dari hash
        return substr(hash('sha256', $key), 0, 16);
    }

    /* -------------------------
       Helper: AES
       ------------------------- */
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

    /* -------------------------
       Helper: ATBASH (mengikuti kapitalisasi plaintext)
       ------------------------- */
    private function atbashWithCase(string $text): string
    {
        $result = '';
        foreach (str_split($text) as $char) {
            if (ctype_alpha($char)) {
                $isUpper = ctype_upper($char);
                $c = strtoupper($char);
                $cipher = chr(ord('Z') - (ord($c) - ord('A')));
                $result .= $isUpper ? $cipher : strtolower($cipher);
            } else {
                $result .= $char;
            }
        }
        return $result;
    }

    /* -------------------------
       Helper: VIGENÈRE dengan kapital mengikuti plaintext
       ------------------------- */
    private function vigenereWithCase(string $text, string $key): string
    {
        $ciphertext = '';
        $filteredKey = strtoupper($key);
        $keyLen = strlen($filteredKey);
        $j = 0;

        foreach (str_split($text) as $char) {
            if (ctype_alpha($char)) {
                $isUpper = ctype_upper($char);
                $c = strtoupper($char);
                $shift = ord($filteredKey[$j % $keyLen]) - 65;
                $enc = ((ord($c) - 65 + $shift) % 26) + 65;
                $ciphertext .= $isUpper ? chr($enc) : strtolower(chr($enc));
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
                $c = strtoupper($char);
                $dec = ((ord($c) - 65 - (ord($filteredKey[$j % $keyLen]) - 65) + 26) % 26) + 65;
                $plaintext .= $isUpper ? chr($dec) : strtolower(chr($dec));
                $j++;
            } else {
                $plaintext .= $char;
            }
        }
        return $plaintext;
    }
}
