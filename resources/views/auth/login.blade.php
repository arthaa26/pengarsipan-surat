<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login E-Arsip UMP</title>

    <!-- Pastikan salah satu link CSS ini yang aktif, sesuai setup Laravel Anda -->
    @vite('resources/css/app.css') 
    
    <style>
        /* Menggunakan warna biru dari halaman sambutan: #0c66a4 */
        .umptk-bg-blue {
            background-color: #0c66a4;
        }
        /* Style untuk kotak form (biru terang) */
        .login-box-blue {
            background-color: #3b91c1; /* Warna biru muda untuk kotak form */
        }
        /* Style untuk input dan tombol (rounded penuh) */
        .full-rounded-input {
            border-radius: 9999px; /* Tailwind's rounded-full equivalent */
            padding-left: 1.5rem; /* px-6 */
            padding-right: 1.5rem; /* px-6 */
        }
    </style>
</head>

<body class="umptk-bg-blue min-h-screen flex flex-col items-center justify-between p-4">
    
    <!-- Kontainer Login di Tengah -->
    <div class="flex-grow flex items-center justify-center w-full">
        <div class="w-full max-w-md login-box-blue rounded-3xl shadow-2xl p-8 md:p-12">
            
            <!-- Judul MASUK -->
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-white tracking-wider">MASUK</h1>
            </div>

            <!-- Tampilkan Error Otentikasi (Tetap dipertahankan untuk fungsi) -->
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-4" role="alert">
                    @foreach ($errors->all() as $error)
                        <p class="text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            
            <!-- Formulir Login -->
            <form method="POST" action="{{ url('/login') }}">
                @csrf

                <!-- Field Email (tanpa label, rounded-full) -->
                <div class="mb-5">
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus
                        class="w-full py-3 border-none shadow-md full-rounded-input focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror" 
                        placeholder="sahroni@unmuhpnk.ac.id"
                    >
                </div>

                <!-- Field Password (tanpa label, rounded-full) -->
                <div class="mb-8">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required 
                        class="w-full py-3 border-none shadow-md full-rounded-input focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror" 
                        placeholder="********"
                    >
                </div>
                
                <!-- Checkbox Remember Me (tanpa link Lupa Password) -->
                <div class="mb-10 flex items-center justify-center">
                    <div class="flex items-center text-white">
                        <!-- Menggunakan class 'appearance-none' dan styling kustom untuk checkbox bulat jika diperlukan, 
                             tapi kita gunakan style default dulu. -->
                        <input type="checkbox" name="remember" id="remember" class="h-5 w-5 text-blue-600 border-gray-300 rounded-full">
                        <label for="remember" class="ml-2 block text-base font-medium">Ingat Saya</label>
                    </div>
                </div>

                <!-- Tombol Submit (rounded-full) -->
                <div class="text-center">
                    <button 
                        type="submit" 
                        class="bg-white text-gray-800 font-bold py-2.5 px-8 full-rounded-input shadow-lg hover:bg-gray-100 transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white"
                    >
                        Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Footer LP3M UM PONTIANAK -->
    <footer class="text-center text-white text-xs py-4 w-full">
        LP3M UM PONTIANAK
    </footer>
</body>
</html>
