<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kriptografi Enkripsi 3 Layer</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-to-r from-blue-900 via-blue-700 to-blue-500 text-white font-sans">

    <!-- Hero Section -->
    <section class="h-screen flex flex-col justify-center items-center text-center px-6">
        <h2 class="text-5xl md:text-6xl font-extrabold mb-6 leading-tight">
            Keamanan Data<br>
            <span class="text-yellow-400">Dengan Enkripsi 3 Layer</span>
        </h2>
        <p class="text-lg md:text-xl mb-10 max-w-2xl text-blue-100">
            Menggabungkan <span class="font-semibold">Atbash</span>, <span class="font-semibold">Vigenère</span>,
            dan <span class="font-semibold">AES</span> untuk perlindungan berlapis yang kuat dan modern.
        </p>

        <!-- Button Group -->
        <div class="flex space-x-12">

            <!-- Enkripsi Dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                    class="bg-yellow-400 text-black px-10 py-5 text-lg rounded-lg font-bold hover:bg-yellow-300 transition min-w-[300px]">
                    🔒 Enkripsi
                </button>
                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-2"
                    class="absolute mt-3 w-full bg-white text-gray-800 rounded-xl shadow-xl overflow-hidden z-50 min-w-[300px]">

                    <a href="{{ route('atbash.encrypt')}}" class="block px-6 py-3 hover:bg-gray-100 transition">🔄 Atbash</a>
                    <div class="border-t"></div>
                    <a href="{{ route('vigenere.encrypt') }}" class="block px-6 py-3 hover:bg-gray-100 transition">🔑 Vigenère</a>
                    <div class="border-t"></div>
                    <a href="{{ route('aes.encrypt') }}" class="block px-6 py-3 hover:bg-gray-100 transition">🔐 AES</a>
                    <div class="border-t"></div>
                    <a href="#" class="block px-6 py-3 hover:bg-gray-100 font-semibold text-blue-700 transition">✨
                        Semua (3 Layer)</a>
                </div>
            </div>

            <!-- Dekripsi Dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                    class="border border-white px-10 py-5 text-lg rounded-lg font-bold hover:bg-white hover:text-blue-900 transition min-w-[300px]">
                    🔓 Dekripsi
                </button>
                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-2"
                    class="absolute mt-3 w-full bg-white text-gray-800 rounded-xl shadow-xl overflow-hidden z-50 min-w-[300px]">

                    <a href="{{ route('atbash.decrypt')}}" class="block px-6 py-3 hover:bg-gray-100 transition">🔄 Atbash</a>
                    <div class="border-t"></div>
                    <a href="{{route('vigenere.decrypt')}}" class="block px-6 py-3 hover:bg-gray-100 transition">🔑 Vigenère</a>
                    <div class="border-t"></div>
                    <a href="{{ route('aes.decrypt') }}" class="block px-6 py-3 hover:bg-gray-100 transition">🔐 AES</a>
                    <div class="border-t"></div>
                    <a href="#" class="block px-6 py-3 hover:bg-gray-100 font-semibold text-blue-700 transition">✨
                        Semua (3 Layer)</a>
                </div>
            </div>

        </div>
    </section>

</body>

</html>
