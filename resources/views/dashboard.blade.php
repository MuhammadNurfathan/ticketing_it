<x-app-layout>
    <x-slot name="header">
        <div class="text-center">
            <h2
                class="text-4xl font-extrabold bg-gradient-to-r from-blue-600 to-purple-600 dark:from-blue-400 dark:to-purple-400 bg-clip-text text-transparent">
                SELAMAT DATANG DI TICKETING IT
            </h2>
        </div>
    </x-slot>

    {{-- Wrap konten supaya fleksibel dan bisa menempel footer --}}
    <div class="flex flex-col min-h-[calc(100vh-4rem)]">
        
        {{-- Konten utama dashboard --}}
        <main class="flex-1 max-w-full px-6 py-8 space-y-10">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-[repeat(auto-fit,minmax(100px,1fr))] gap-6 justify-items-center">
                {{-- Menu 1: Create Ticket --}}
                <a href="{{ route('DashboardTicketsUser.createUser') }}"
                    class="w-full h-full block overflow-hidden bg-gradient-to-br from-purple-500 via-pink-500 to-red-500 rounded-3xl shadow-xl transition-all duration-300 hover:scale-[1.02] text-center border border-pink-200">
                    <div
                        class="flex flex-col justify-between h-full bg-gradient-to-br from-white/90 to-pink-50 dark:from-gray-800 dark:to-gray-900 rounded-3xl shadow-inner p-6 text-center border border-pink-100 dark:border-gray-700">
                        <div>
                            <div class="text-4xl mb-2">➕</div>
                            <h2
                                class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 dark:from-purple-400 dark:to-pink-400 bg-clip-text text-transparent mb-1">
                                Create Ticket
                            </h2>
                            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                Open a new ticket and manage tasks easily
                            </p>
                        </div>
                    </div>
                </a>

                {{-- Menu 2: My Tickets --}}
                <a href="{{ route('DashboardTicketsUser.indexUser') }}"
                    class="w-full h-full block overflow-hidden bg-gradient-to-br from-green-400 via-green-500 to-green-600 rounded-3xl shadow-xl transition-all duration-300 hover:scale-[1.02] text-center border border-green-200">
                    <div
                        class="flex flex-col justify-between h-full bg-gradient-to-br from-white/90 to-green-50 dark:from-gray-800 dark:to-gray-900 rounded-3xl shadow-inner p-6 text-center border border-green-100 dark:border-gray-700">
                        <div>
                            <div class="text-4xl mb-2">📂</div>
                            <h2
                                class="text-2xl font-bold bg-gradient-to-r from-green-600 to-teal-600 dark:from-green-400 dark:to-teal-400 bg-clip-text text-transparent mb-1">
                                My Tickets
                            </h2>
                            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                Tickets assigned to you for tracking and action
                            </p>
                        </div>
                    </div>
                </a>



            </div>


        </main>
    </div>
</x-app-layout>
