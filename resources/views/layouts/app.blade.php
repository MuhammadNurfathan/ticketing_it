<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Ticketing IT') }}</title>

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />

    <!-- Styles -->
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-light-bg text-light-text dark:bg-dark-bg dark:text-dark-text">
    <div x-data="mainState" :class="{ dark: isDarkMode }" @resize.window="handleWindowResize" x-cloak>
        <div class="min-h-screen bg-light-bg text-light-text dark:bg-dark-bg dark:text-dark-text">

            <!-- Sidebar -->
            <x-sidebar.sidebar />

            <!-- Page Wrapper -->
            <div class="flex min-h-screen flex-col transition-[margin] duration-200 ease-out"
                :class="{
                    'lg:ml-64': isSidebarOpen,
                    'md:ml-16': !isSidebarOpen
                }">

                <!-- Navbar -->
                <x-navbar />

                <!-- Page Heading -->
                <header class="px-4 sm:px-6 lg:px-8 py-2 sm:py-3">
                    {{ $header }}
                </header>



                <!-- Page Content -->
                <main class="flex-1" >
                    {{-- Content container full-width, tapi tetap rapi --}}
                    <div class="w-full">
                        {{ $slot }}
                    </div>
                </main>

                <!-- Footer -->
                <x-footer />
            </div>
        </div>
    </div>
</body>

</html>
