<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kriptografi 3 Layer</title>
    <link rel="icon" href="{{ asset('icons/cripto.png') }}" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-cover bg-center text-white font-sans" style="background-image: url('/icons/kripto.png');">

    <section x-data="{ encryptionOpen: false, decryptionOpen: false }"
        class="h-screen flex flex-col justify-center items-center text-center px-6">

        <h2 class="text-5xl md:text-6xl font-extrabold mb-10 leading-tight">
            Keamanan Data<br>
            <span class="text-yellow-400">Dengan Enkripsi 3 Layer</span>
        </h2>

        <div class="flex space-x-12">
            <!-- Enkripsi Button -->
            <button @click="encryptionOpen = true"
                class="bg-yellow-400 text-black px-10 py-5 text-lg rounded-lg font-bold hover:bg-yellow-300 transition">
                🔒 Enkripsi
            </button>

            <!-- Dekripsi Button -->
            <button @click="decryptionOpen = true"
                class="border border-white px-10 py-5 text-lg rounded-lg font-bold hover:bg-white hover:text-blue-900 transition">
                🔓 Dekripsi
            </button>
        </div>

        <!-- Modal Enkripsi -->
        <div x-show="encryptionOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div @click.away="encryptionOpen = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white text-gray-900 rounded-xl shadow-2xl p-8 w-full max-w-md relative">

                <h3 class="text-2xl font-bold mb-6 text-center text-indigo-700">Pilih Cipher 🔒</h3>

                <a href="{{ route('atbash.encrypt')}}"
                    class="block px-6 py-3 mb-2 bg-indigo-100 rounded-lg text-indigo-800 font-semibold hover:bg-indigo-200 transition">
                    🔄 Atbash
                </a>
                <a href="{{ route('vigenere.encrypt')}}"
                    class="block px-6 py-3 mb-2 bg-indigo-100 rounded-lg text-indigo-800 font-semibold hover:bg-indigo-200 transition">
                    🔑 Vigenère
                </a>
                <a href="{{ route('aes.encrypt')}}"
                    class="block px-6 py-3 mb-2 bg-indigo-100 rounded-lg text-indigo-800 font-semibold hover:bg-indigo-200 transition">
                    🔐 AES
                </a>
                <a href="{{ route('triple.encrypt')}}"
                    class="block px-6 py-3 bg-indigo-200 rounded-lg text-blue-700 font-bold hover:bg-indigo-300 transition">
                    ✨ Semua (3 Layer)
                </a>

                <!-- Close Button -->
                <button @click="encryptionOpen = false"
                    class="absolute top-3 right-3 text-gray-700 hover:text-gray-900 font-bold text-lg">&times;</button>
            </div>
        </div>

        <!-- Modal Dekripsi -->
        <div x-show="decryptionOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div @click.away="decryptionOpen = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white text-gray-900 rounded-xl shadow-2xl p-8 w-full max-w-md relative">

                <h3 class="text-2xl font-bold mb-6 text-center text-indigo-700">Pilih Cipher 🔓</h3>

                <a href="{{ route('atbash.decrypt')}}"
                    class="block px-6 py-3 mb-2 bg-blue-100 rounded-lg text-blue-800 font-semibold hover:bg-blue-200 transition">
                    🔄 Atbash
                </a>
                <a href="{{ route('vigenere.decrypt')}}"
                    class="block px-6 py-3 mb-2 bg-blue-100 rounded-lg text-blue-800 font-semibold hover:bg-blue-200 transition">
                    🔑 Vigenère
                </a>
                <a href="{{ route('aes.decrypt')}}"
                    class="block px-6 py-3 mb-2 bg-blue-100 rounded-lg text-blue-800 font-semibold hover:bg-blue-200 transition">
                    🔐 AES
                </a>
                <a href="{{ route('triple.decrypt')}}"
                    class="block px-6 py-3 bg-blue-200 rounded-lg text-blue-700 font-bold hover:bg-blue-300 transition">
                    ✨ Semua (3 Layer)
                </a>

                <!-- Close Button -->
                <button @click="decryptionOpen = false"
                    class="absolute top-3 right-3 text-gray-700 hover:text-gray-900 font-bold text-lg">&times;</button>
            </div>
        </div>

    </section>

</body>

</html>
