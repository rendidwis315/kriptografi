<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dekripsi 3-Layer (AES + Vigenere + Atbash)</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body
    class="bg-gradient-to-r from-indigo-900 via-indigo-700 to-indigo-500 text-white min-h-screen flex items-center justify-center font-sans">

    <div class="bg-white text-gray-900 p-8 rounded-xl shadow-2xl w-full max-w-lg">
        <h2 class="text-3xl font-bold mb-6 text-center text-indigo-700">🔓 Dekripsi 3-Layer</h2>
        <p class="text-center text-sm text-gray-600 mb-6">(AES → VIGENERE → ATBASH)</p>

        <form action="{{ url('/triple/decrypt') }}" method="POST" class="space-y-5">
            @csrf
            <!-- Ciphertext -->
            <div>
                <label for="ciphertext" class="block font-semibold mb-2">Ciphertext (Base64)</label>
                <textarea id="ciphertext" name="ciphertext" rows="3"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                    placeholder="Masukkan hasil ciphertext dari proses enkripsi..."></textarea>
            </div>

            <!-- Vigenere Key -->
            <div>
                <label for="vigenere_key" class="block font-semibold mb-2">Kunci Vigenere (huruf A-Z)</label>
                <input type="text" id="vigenere_key" name="vigenere_key"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                    placeholder="Contoh: FORTIS" required>
            </div>

            <!-- AES Key -->
            <div>
                <label for="aes_key" class="block font-semibold mb-2">Kunci AES (16 karakter)</label>
                <input type="text" id="aes_key" name="aes_key"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                    maxlength="16" placeholder="Contoh: 1234567890ABCDEF" required>
            </div>

            <!-- Submit -->
            <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-500 text-white py-3 rounded-lg font-bold transition">
                🔍 Dekripsi Sekarang
            </button>
        </form>

        <!-- Error -->
        @if (session('error'))
            <div class="mt-4 p-3 bg-red-100 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Hasil -->
        @if (session('plaintext'))
            <div class="mt-6 p-4 bg-indigo-100 text-indigo-900 rounded-lg">
                <h3 class="font-bold mb-2">Hasil Dekripsi:</h3>
                <p class="break-words">{{ session('plaintext') }}</p>
            </div>
        @endif
    </div>

</body>

</html>
