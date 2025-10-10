<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AtbashController extends Controller
{
    // Menampilkan halaman form enkripsi Atbash
    public function atbashEncrypt()
    {
        return view('atbash.encrypt');
    }

    // Memproses input plaintext dan menghasilkan ciphertext
    public function atbashProcess(Request $request)
    {
        // Cek apakah input berasal dari form enkripsi atau dekripsi
        if ($request->has('plaintext')) {
            $input = strtoupper($request->input('plaintext'));
            $result = $this->encryptAtbash($input);
            return redirect()->route('atbash.encrypt')->with('ciphertext', $result);
        } elseif ($request->has('ciphertext')) {
            $input = strtoupper($request->input('ciphertext'));
            $result = $this->encryptAtbash($input); // fungsi sama untuk dekripsi
            return redirect()->route('atbash.decrypt')->with('plaintext', $result);
        }

        return back()->with('error', 'Input tidak valid.');
    }

    // Menampilkan halaman form dekripsi Atbash
    public function atbashDecrypt()
    {
        return view('atbash.decrypt');
    }

    // Fungsi utama untuk enkripsi dan dekripsi (karena Atbash simetris)
    private function encryptAtbash($text)
    {
        $result = '';

        for ($i = 0; $i < strlen($text); $i++) {
            $char = $text[$i];

            if (ctype_alpha($char)) {
                // A ↔ Z, B ↔ Y, dst
                $cipherChar = chr(90 - (ord($char) - 65));
                $result .= $cipherChar;
            } else {
                $result .= $char;
            }
        }

        return $result;
    }
}