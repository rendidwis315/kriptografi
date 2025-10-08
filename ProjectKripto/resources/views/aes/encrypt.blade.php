<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Enkripsi AES</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body
    class="bg-gradient-to-r from-indigo-900 via-indigo-700 to-indigo-500 text-white min-h-screen flex items-center justify-center font-sans">

    <div class="bg-white text-gray-900 p-8 rounded-xl shadow-2xl w-full max-w-lg">
        <h2 class="text-3xl font-bold mb-6 text-center text-indigo-700">🔑 Enkripsi AES</h2>

        <form action="{{ route('aes.process') }}" method="POST" class="space-y-5">
            @csrf
            <!-- Plaintext -->
            <div>
                <label for="plaintext" class="block font-semibold mb-2">Plaintext</label>
                <textarea id="plaintext" name="plaintext" rows="3"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>

            <!-- Key -->
            <div>
                <label for="key" class="block font-semibold mb-2">Key (16 karakter)</label>
                <input type="text" id="key" name="key"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" maxlength="16">
            </div>

            <!-- Submit -->
            <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-500 text-white py-3 rounded-lg font-bold transition">
                🔒 Enkripsi
            </button>
        </form>

        <!-- Error -->
        @if (session('error'))
            <div class="mt-4 p-3 bg-red-100 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Hasil -->
        @if (session('ciphertext'))
            <div class="mt-6 p-4 bg-indigo-100 text-indigo-900 rounded-lg">
                <h3 class="font-bold mb-2">Hasil Enkripsi:</h3>
                <p class="break-words">{{ session('ciphertext') }}</p>
            </div>
        @endif
    </div>

</body>

</html>
