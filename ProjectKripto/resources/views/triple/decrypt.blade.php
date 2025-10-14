<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dekripsi 3-Layer (AES + Vigenere + Atbash)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function copyPlain() {
            const text = document.getElementById('plaintext').innerText;
            navigator.clipboard.writeText(text);

            const notif = document.getElementById('copyNotif');
            notif.classList.remove('hidden');
            notif.classList.add('flex');

            setTimeout(() => {
                notif.classList.add('hidden');
                notif.classList.remove('flex');
            }, 2000);
        }
    </script>
</head>

<body class="text-white min-h-screen flex items-center justify-center font-sans bg-cover bg-center" style="background-image: url('/icons/kripto.png');">

    <div class="bg-white text-gray-900 p-8 rounded-xl shadow-2xl w-full max-w-lg">
        <h2 class="text-3xl font-bold mb-6 text-center text-indigo-700">🔓 Dekripsi 3-Layer</h2>
        <p class="text-center text-sm text-gray-600 mb-6">(AES → VIGENERE → ATBASH)</p>

        <form action="{{ url('/triple/decrypt') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="ciphertext" class="block font-semibold mb-2">Ciphertext (Base64)</label>
                <textarea id="ciphertext" name="ciphertext" rows="3"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                    placeholder="Masukkan hasil ciphertext dari proses enkripsi..." required></textarea>
            </div>

            <div>
                <label for="key" class="block font-semibold mb-2">Kunci Utama (huruf A-Z)</label>
                <input type="text" id="key" name="key"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                    placeholder="Contoh: FORTIS" required>
                <p class="text-xs text-gray-500 mt-1">Kunci ini digunakan untuk proses Vigenere & derivasi AES secara otomatis.</p>
            </div>

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
            <div class="mt-6 p-4 bg-indigo-100 text-indigo-900 rounded-lg relative">
                <h3 class="font-bold mb-2">Hasil Dekripsi:</h3>
                <p id="plaintext" class="break-words">{{ session('plaintext') }}</p>

                <div class="mt-4 flex justify-between items-center">
                    <button onclick="copyPlain()"
                        class="bg-indigo-600 hover:bg-indigo-500 text-white py-2 px-4 rounded-lg font-semibold transition">
                        📋 Copy
                    </button>

                    <a href="{{ url('/') }}"
                        class="bg-gray-500 hover:bg-gray-400 text-white py-2 px-4 rounded-lg font-semibold transition">
                        ⬅️ Kembali
                    </a>
                </div>

                <div id="copyNotif"
                    class="hidden mt-3 text-sm text-green-700 bg-green-100 border border-green-300 rounded-lg px-3 py-2 flex items-center gap-2">
                    ✅ Plaintext berhasil disalin!
                </div>
            </div>
        @endif
    </div>

</body>

</html>
