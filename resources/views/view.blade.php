<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SELAMAT DATANG DI E-ARSIP UMP</title>
    @vite('resources/css/app.css') 
    
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
</head>

<body class="bg-[#1f72a4] min-h-screen flex items-center justify-center p-4">
    
    <div class="text-center text-white space-y-8 max-w-4xl mx-auto">
        
        <div class="mb-8">
            <img 
                src="{{ asset('images/logo-ump.png') }}" 
                alt="Logo UMP" 
                class="mx-auto w-24 md:w-32 shadow-lg rounded-full border-2 border-white"
            > 
        </div>
        
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold leading-tight tracking-tight">
            SELAMAT DATANG DI E-ARSIP <br>
            UNIVERSITAS MUHAMMADIYAH PONTIANAK
        </h1>
        
        <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-8 mt-10">
            
            <a 
                href="{{ route('register') }}" 
                class="
                    bg-white text-[#1f72a4] font-bold py-3 px-10 rounded-full shadow-lg 
                    hover:bg-gray-100 transform hover:scale-105 transition duration-300 
                    w-full sm:w-auto text-lg
                "
            >
                Daftar
            </a>
            
            <a 
                href="{{ route('login') }}" 
                class="
                    bg-white text-[#1f72a4] font-bold py-3 px-10 rounded-full shadow-lg 
                    hover:bg-gray-100 transform hover:scale-105 transition duration-300 
                    w-full sm:w-auto text-lg
                "
            >
                Masuk
            </a>
        </div>
    </div>
</body>
</html>