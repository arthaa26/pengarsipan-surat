<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SELAMAT DATANG DI E-ARSIP UMP</title>
    @vite('resources/css/app.css') 
    
    <style>
        .umptk-bg-blue {
            background-color: #0c66a4;
        }
    </style>
</head>

<body class="umptk-bg-blue min-h-screen flex items-center justify-center p-4">
    
    <div class="text-center text-white p-6 md:p-10">
        <div class="mb-10">
            <img 
                src="{{ asset('images/unmuh.png') }}" 
                alt="Logo UMP" 
                class="mx-auto h-15 w-auto object-contain"
            > 
        </div>
        <h1 class="text-3xl sm:text-4xl md:text-4xl font-extrabold leading-tight tracking-tight mb-2">
            SELAMAT DATANG DI E-ARSIP
        </h1>
        <h2 class="text-3xl sm:text-4xl md:text-4xl font-extrabold leading-tight tracking-tight mb-2">
            UNIVERSITAS MUHAMMADIYAH
        </h2>
        <h3 class="text-3xl sm:text-4xl md:text-4xl font-extrabold leading-tight tracking-tight mb-12">
            PONTIANAK
        </h3>
        <a 
            href="{{ route('login') }}" 
            class="
                bg-white text-gray-800 font-semibold py-3 px-12 
                rounded-full shadow-lg hover:bg-gray-100 transition duration-200 text-base
            "
            style="width: 120px;" 
        >
            Masuk
        </a>
        
    </div>
</body>
</html>
