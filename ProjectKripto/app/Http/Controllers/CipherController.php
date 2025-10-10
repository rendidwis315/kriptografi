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
        $plaintext = strtoupper($request->input('plaintext'));
        $key = strtoupper($request->input('key'));

        $ciphertext = $this->encryptVigenere($plaintext, $key);

        return redirect()->route('vigenere.encrypt')->with('ciphertext', $ciphertext);
    }

    private function encryptVigenere($text, $key)
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
}
