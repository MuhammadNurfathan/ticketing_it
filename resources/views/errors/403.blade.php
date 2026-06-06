<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-700 flex items-center justify-center min-h-screen font-sans">

    <div class="bg-gray-800 rounded-3xl shadow-2xl p-12 max-w-lg text-center relative overflow-hidden border border-gray-700">
        <!-- Background circle -->
        <div class="absolute -top-20 -left-20 w-72 h-72 bg-red-700 rounded-full opacity-30 animate-ping"></div>
        <div class="absolute -bottom-20 -right-20 w-72 h-72 bg-red-700 rounded-full opacity-30 animate-ping"></div>

        <!-- Content -->
        <h1 class="text-8xl font-extrabold text-red-500 mb-4 animate-bounce">403</h1>
        <h2 class="text-3xl font-semibold mb-4 text-white">Ups! Akses Ditolak</h2>
        <p class="text-gray-300 mb-8 leading-relaxed">
            Kamu tidak memiliki izin untuk mengakses halaman ini. <br>
            Pastikan akunmu memiliki role yang sesuai atau hubungi administrator.
        </p>
        
        <a href="{{ url('/DashboardTicketsUser') }}"
           class="inline-block bg-red-600 text-white font-semibold px-8 py-3 rounded-full shadow-lg hover:bg-red-700 transform hover:-translate-y-1 transition-all duration-300">
            Kembali ke Beranda
        </a>
    </div>

</body>
</html>
