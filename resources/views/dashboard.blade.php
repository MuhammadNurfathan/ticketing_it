<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('TICKETING IT') }}
        </h2>
    </x-slot>

    <div class="min-h-[calc(100vh-4rem)]">
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
            
            {{-- Welcome Section --}}
            <div class="mb-8 sm:mb-12">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-2">
                    Welcome back! 👋
                </h1>
                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400">
                    Manage your IT support tickets efficiently
                </p>
            </div>

            {{-- Menu Cards Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 lg:gap-8">
                
                {{-- Create Ticket Card --}}
                <a href="{{ route('DashboardTicketsUser.createUser') }}" 
                   class="group relative overflow-hidden rounded-2xl sm:rounded-3xl bg-gradient-to-br from-purple-500 via-pink-500 to-rose-500 p-[2px] transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl hover:shadow-purple-500/30">
                    <div class="relative h-full bg-white dark:bg-gray-900 rounded-2xl sm:rounded-3xl p-6 sm:p-8 lg:p-10">
                        {{-- Icon --}}
                        <div class="mb-4 sm:mb-6">
                            <div class="w-14 h-14 sm:w-16 sm:h-16 lg:w-20 lg:h-20 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center transform group-hover:rotate-6 transition-transform duration-300">
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 lg:w-10 lg:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                        </div>
                        
                        {{-- Content --}}
                        <div>
                            <h3 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white mb-2 sm:mb-3">
                                Create Ticket
                            </h3>
                            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mb-4 sm:mb-6">
                                Submit a new support request
                            </p>
                            
                            {{-- Arrow Icon --}}
                            <div class="flex items-center text-purple-600 dark:text-purple-400 group-hover:translate-x-2 transition-transform duration-300">
                                <span class="text-sm sm:text-base font-semibold mr-2">Get Started</span>
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </div>
                        </div>

                        {{-- Decorative Elements --}}
                        <div class="absolute -top-10 -right-10 w-32 h-32 sm:w-40 sm:h-40 bg-gradient-to-br from-purple-400/20 to-pink-400/20 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-500"></div>
                    </div>
                </a>

                {{-- My Tickets Card --}}
                <a href="{{ route('DashboardTicketsUser.indexUser') }}" 
                   class="group relative overflow-hidden rounded-2xl sm:rounded-3xl bg-gradient-to-br from-emerald-500 via-teal-500 to-cyan-500 p-[2px] transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl hover:shadow-emerald-500/30">
                    <div class="relative h-full bg-white dark:bg-gray-900 rounded-2xl sm:rounded-3xl p-6 sm:p-8 lg:p-10">
                        {{-- Icon --}}
                        <div class="mb-4 sm:mb-6">
                            <div class="w-14 h-14 sm:w-16 sm:h-16 lg:w-20 lg:h-20 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center transform group-hover:rotate-6 transition-transform duration-300">
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 lg:w-10 lg:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                </svg>
                            </div>
                        </div>
                        
                        {{-- Content --}}
                        <div>
                            <h3 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white mb-2 sm:mb-3">
                                My Tickets
                            </h3>
                            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mb-4 sm:mb-6">
                                View and track your tickets
                            </p>
                            
                            {{-- Arrow Icon --}}
                            <div class="flex items-center text-emerald-600 dark:text-emerald-400 group-hover:translate-x-2 transition-transform duration-300">
                                <span class="text-sm sm:text-base font-semibold mr-2">View All</span>
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </div>
                        </div>

                        {{-- Decorative Elements --}}
                        <div class="absolute -top-10 -right-10 w-32 h-32 sm:w-40 sm:h-40 bg-gradient-to-br from-emerald-400/20 to-teal-400/20 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-500"></div>
                    </div>
                </a>

            </div>

            {{-- Quick Stats (Optional - bisa dihapus jika tidak perlu) --}}
            <div class="mt-8 sm:mt-12 grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-gray-200 dark:border-gray-700">
                    <div class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 mb-1 sm:mb-2">Total Tickets</div>
                    <div class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white" id="all-projects">-</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-gray-200 dark:border-gray-700">
                    <div class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 mb-1 sm:mb-2">In Progress</div>
                    <div class="text-xl sm:text-2xl lg:text-3xl font-bold text-amber-600 dark:text-amber-400" id="active-projects">-</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-gray-200 dark:border-gray-700 col-span-2 sm:col-span-1">
                    <div class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 mb-1 sm:mb-2">Resolved</div>
                    <div class="text-xl sm:text-2xl lg:text-3xl font-bold text-emerald-600 dark:text-emerald-400" id="closed-projects">-</div>
                </div>
            </div>

        </main>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", async () => {
            async function loadSummary(year) {
                try {
                    const res = await fetch(`{{ url('/api/SummaryProject') }}?year=${year}`);
                    if (!res.ok) throw new Error("Gagal ambil data summary");
                    const data = await res.json();
                    
                    // Update stats dengan data dari API
                    document.getElementById('all-projects').textContent = data.total ?? '-';
                    document.getElementById('active-projects').textContent = data.active ?? '-';
                    document.getElementById('closed-projects').textContent = data.closed ?? '-';
                    
                } catch (err) {
                    console.error('Error loading summary:', err);
                    // Set default value jika error
                    document.getElementById('all-projects').textContent = '-';
                    document.getElementById('active-projects').textContent = '-';
                    document.getElementById('closed-projects').textContent = '-';
                }
            }

            // Load data untuk tahun sekarang
            const currentYear = new Date().getFullYear();
            loadSummary(currentYear);
        });
    </script>
</x-app-layout>