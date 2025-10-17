<x-app-layout>
    <x-slot name="header">
        <div class="text-center">
            <h2
                class="text-4xl font-extrabold bg-gradient-to-r from-blue-600 to-purple-600 dark:from-blue-400 dark:to-purple-400 bg-clip-text text-transparent">
                Ticket Management Dashboard
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage your workflow efficiently</p>
        </div>
    </x-slot>

    {{-- Wrap konten supaya fleksibel dan bisa menempel footer --}}
    <div class="flex flex-col min-h-[calc(100vh-4rem)]"> {{-- asumsi header tinggi 4rem --}}

        {{-- Konten utama dashboard --}}
        <main class="flex-1 max-w-full px-6 py-8 space-y-10">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 justify-items-stretch">

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

                {{-- Menu 3: All Tickets --}}
                <a href="{{ route('DashboardTicketsAdmin.index') }}"
                    class="w-full h-full block overflow-hidden bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600 rounded-3xl shadow-xl transition-all duration-300 hover:scale-[1.02] text-center border border-blue-200">
                    <div
                        class="flex flex-col justify-between h-full bg-gradient-to-br from-white/90 to-blue-50 dark:from-gray-800 dark:to-gray-900 rounded-3xl shadow-inner p-6 text-center border border-blue-100 dark:border-gray-700">
                        <div>
                            <div class="text-4xl mb-2">🎫</div>
                            <h2
                                class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-400 dark:to-indigo-400 bg-clip-text text-transparent mb-1">
                                All Tickets
                            </h2>
                            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                View all tickets and manage them efficiently
                            </p>
                        </div>
                    </div>
                </a>

                <div class="w-full h-full hidden lg:block"></div>
            </div>

            {{-- INFO SECTION --}}
            <div
                class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900 rounded-3xl shadow-xl p-12 text-center border border-blue-100 dark:border-gray-700">
                <div class="max-w-3xl mx-auto">
                    <h2
                        class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-400 dark:to-indigo-400 bg-clip-text text-transparent mb-4">
                        Manage Everything In One Place 🎯
                    </h2>
                    <p class="text-lg text-gray-700 dark:text-gray-300 leading-relaxed">
                        Streamline your workflow with our powerful ticket management system.
                        Track progress, collaborate with your team, and deliver exceptional results effortlessly.
                    </p>
                </div>
            </div>

        </main>
    </div>
</x-app-layout>
