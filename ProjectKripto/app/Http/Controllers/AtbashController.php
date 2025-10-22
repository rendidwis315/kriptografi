<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AtbashController extends Controller
{
    public function atbashEncrypt()
    {
        return view('atbash.encrypt');
    }

    public function atbashProcess(Request $request)
    {
        if ($request->has('plaintext')) {
            $input = $request->input('plaintext', '');
            $result = $this->encryptAtbash($input);
            return redirect()->route('atbash.encrypt')->with('ciphertext', $result);
        } elseif ($request->has('ciphertext')) {
            $input = $request->input('ciphertext', '');
            $result = $this->encryptAtbash($input); // sama untuk dekripsi
            return redirect()->route('atbash.decrypt')->with('plaintext', $result);
        }

        return back()->with('error', 'Input tidak valid.');
    }

    public function atbashDecrypt()
    {
        return view('atbash.decrypt');
    }

    private function encryptAtbash($text)
    {
        $result = '';
        for ($i = 0; $i < strlen($text); $i++) {
            $char = $text[$i];
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
}