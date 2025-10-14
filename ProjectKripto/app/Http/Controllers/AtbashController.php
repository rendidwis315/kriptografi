<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AtbashController extends Controller
{
    // Halaman enkripsi
    public function atbashEncrypt()
    {
        return view('atbash.encrypt');
    }

    // Proses enkripsi & dekripsi
    public function atbashProcess(Request $request)
    {
        if ($request->has('plaintext')) {
            $input = $request->input('plaintext', '');
            $result = $this->encryptAtbash($input);
            return redirect()->route('atbash.encrypt')->with('ciphertext', $result);
        } elseif ($request->has('ciphertext')) {
            $input = $request->input('ciphertext', '');
            $result = $this->encryptAtbash($input); // fungsi sama untuk dekripsi
            return redirect()->route('atbash.decrypt')->with('plaintext', $result);
        }

        return back()->with('error', 'Input tidak valid.');
    }

    // Halaman dekripsi
    public function atbashDecrypt()
    {
        return view('atbash.decrypt');
    }

    // Fungsi utama (Atbash bersifat simetris)
    private function encryptAtbash($text)
    {
        $result = '';

        for ($i = 0; $i < strlen($text); $i++) {
            $char = $text[$i];

            if (ctype_upper($char)) {
                // Huruf besar: A ↔ Z
                $cipherChar = chr(ord('Z') - (ord($char) - ord('A')));
                $result .= $cipherChar;
            } elseif (ctype_lower($char)) {
                // Huruf kecil: a ↔ z
                $cipherChar = chr(ord('z') - (ord($char) - ord('a')));
                $result .= $cipherChar;
            } else {
                // Karakter lain tetap
                $result .= $char;
            }
        }

        return $result;
    }
}
