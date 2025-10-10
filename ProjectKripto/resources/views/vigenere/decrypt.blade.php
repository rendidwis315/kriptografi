<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dekripsi Vigenère Cipher</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body
    class="bg-gradient-to-r from-blue-900 via-blue-700 to-blue-500 text-white min-h-screen flex items-center justify-center font-sans">

    <div class="bg-white text-gray-900 p-8 rounded-xl shadow-2xl w-full max-w-lg">
        <h2 class="text-3xl font-bold mb-6 text-center text-blue-700">🔓 Dekripsi Vigenère Cipher</h2>

        <form action="{{ route('vigenere.decrypt.process') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Ciphertext -->
            <div>
                <label for="ciphertext" class="block font-semibold mb-2">Ciphertext</label>
                <textarea id="ciphertext" name="ciphertext" rows="3"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 uppercase"
                    placeholder="Masukkan teks terenkripsi (huruf A–Z)">{{ old('ciphertext') }}</textarea>
            </div>

            <!-- Key -->
            <div>
                <label for="key" class="block font-semibold mb-2">Kunci (Key)</label>
                <input type="text" id="key" name="key"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 uppercase"
                    placeholder="Masukkan kata kunci" value="{{ old('key') }}">
            </div>

            <!-- Tombol Submit -->
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-500 text-white py-3 rounded-lg font-bold transition">
                🔓 Dekripsi
            </button>
        </form>

        <!-- Pesan Error -->
        @if (session('error'))
            <div class="mt-4 p-3 bg-red-100 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Hasil Dekripsi -->
        @if (session('plaintext'))
            <div class="mt-6 p-4 bg-blue-100 text-blue-900 rounded-lg">
                <h3 class="font-bold mb-2">Hasil Dekripsi:</h3>
                <p class="break-words">{{ session('plaintext') }}</p>
            </div>
        @endif
    </div>

</body>

</html>
