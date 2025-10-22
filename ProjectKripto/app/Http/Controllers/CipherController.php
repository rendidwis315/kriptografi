<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CipherController extends Controller
{
    public function vigenereEncrypt()
    {
        return view('vigenere.encrypt');
    }

    public function vigenereProcess(Request $request)
    {
        $plaintext = $request->input('plaintext', '');
        $key = str_replace(' ', '', $request->input('key', ''));

        if (empty($plaintext) || empty($key)) {
            return redirect()->route('vigenere.encrypt')
                ->with('error', 'Plaintext dan key harus diisi.');
        }

        $normalizedKey = $this->normalizeKeyToLetters($key);
        $ciphertext = $this->encryptVigenere($plaintext, $normalizedKey);

        return redirect()->route('vigenere.encrypt')->with('ciphertext', $ciphertext);
    }

    private function encryptVigenere($text, $key)
    {
        $ciphertext = '';
        $keyExpanded = $this->extendKey($text, $key);
        $j = 0;

        for ($i = 0; $i < strlen($text); $i++) {
            $char = $text[$i];
            if (ctype_alpha($char)) {
                $shift = $this->getShiftValue($keyExpanded[$j]);
                $ciphertext .= $this->shiftChar($char, $shift);
                $j++;
            } else {
                $ciphertext .= $char;
            }
        }

        return $ciphertext;
    }

    public function vigenereDecrypt()
    {
        return view('vigenere.decrypt');
    }

    public function vigenereDecryptProcess(Request $request)
    {
        $ciphertext = $request->input('ciphertext', '');
        $key = str_replace(' ', '', $request->input('key', ''));

        if (empty($ciphertext) || empty($key)) {
            return redirect()->route('vigenere.decrypt')
                ->with('error', 'Ciphertext dan key harus diisi.');
        }

        $normalizedKey = $this->normalizeKeyToLetters($key);
        $plaintext = $this->decryptVigenere($ciphertext, $normalizedKey);

        return redirect()->route('vigenere.decrypt')->with('plaintext', $plaintext);
    }

    private function decryptVigenere($text, $key)
    {
        $plaintext = '';
        $keyExpanded = $this->extendKey($text, $key);
        $j = 0;

        for ($i = 0; $i < strlen($text); $i++) {
            $char = $text[$i];
            if (ctype_alpha($char)) {
                $shift = $this->getShiftValue($keyExpanded[$j]);
                $plaintext .= $this->shiftChar($char, -$shift);
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
            $letter = chr(($code % 26) + 65); // A–Z
            $result .= $letter;
        }
        return $result;
    }

    private function extendKey($text, $key)
    {
        $lettersOnly = preg_replace('/[^A-Za-z]/', '', $text);
        $repeatedKey = str_repeat($key, ceil(strlen($lettersOnly) / strlen($key)));
        return substr($repeatedKey, 0, strlen($lettersOnly));
    }

    private function getShiftValue($char)
    {
        return ctype_upper($char)
            ? ord($char) - ord('A')
            : ord(strtoupper($char)) - ord('A');
    }

    private function shiftChar($char, $shift)
    {
        if (ctype_upper($char)) {
            return chr((ord($char) - ord('A') + $shift + 26) % 26 + ord('A'));
        } elseif (ctype_lower($char)) {
            return chr((ord($char) - ord('a') + $shift + 26) % 26 + ord('a'));
        }
        return $char;
    }
}