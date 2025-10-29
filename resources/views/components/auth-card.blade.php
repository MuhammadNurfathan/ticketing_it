<main class="flex flex-col items-center justify-center min-h-screen px-4 py-8 bg-gray-50 dark:bg-gray-900">
    <!-- Logo -->
    <div class="mb-8">
        <a href="/">
            <x-application-logo-2 class="w-24 h-24 transition-transform transform hover:scale-105" />
        </a>
    </div>

    <!-- Card -->
    <div class="w-full max-w-md px-6 py-8 bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl dark:bg-gray-800">
        {{ $slot }}
    </div>
</main>
