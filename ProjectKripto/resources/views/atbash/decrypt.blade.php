<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dekripsi Atbash</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-indigo-900 via-indigo-700 to-indigo-500 text-white min-h-screen flex items-center justify-center font-sans">

    <div class="bg-white text-gray-900 p-8 rounded-xl shadow-2xl w-full max-w-lg">
        <h2 class="text-3xl font-bold mb-6 text-center text-indigo-700">🔄 Dekripsi Atbash</h2>

        <form action="{{ route('atbash.process') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Ciphertext -->
            <div>
                <label for="ciphertext" class="block font-semibold mb-2">Ciphertext</label>
                <textarea id="ciphertext" name="ciphertext" rows="3"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                    placeholder="Masukkan teks terenkripsi yang ingin didekripsi..."></textarea>
            </div>

            <!-- Tombol Dekripsi -->
            <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-500 text-white py-3 rounded-lg font-bold transition">
                🔓 Dekripsi
            </button>
        </form>

        <!-- Hasil Dekripsi -->
        @if (session('plaintext'))
            <div class="mt-6 p-4 bg-indigo-100 text-indigo-900 rounded-lg">
                <h3 class="font-bold mb-2">Hasil Dekripsi:</h3>
                <p class="break-words">{{ session('plaintext') }}</p>
            </div>
        @endif
    </div>

</body>
</html>