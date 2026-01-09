<x-app-layout>
    <x-slot name="header">

    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10">

            {{-- Menu Cards Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 lg:gap-8 mb-8 sm:mb-12 px-2">

                {{-- Create Ticket Card --}}
                <a href="{{ route('DashboardTicketsUser.createUser') }}"
                    class="block group relative rounded-xl sm:rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 p-[2px]
           transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl hover:shadow-blue-500/30">
                    <div
                        class="relative bg-white dark:bg-gray-900 rounded-xl sm:rounded-2xl p-5 sm:p-8 overflow-hidden">

                        {{-- Icon --}}
                        <div class="mb-4 sm:mb-6 relative z-10">
                            <div
                                class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-blue-500 flex items-center justify-center
                       transform group-hover:rotate-6 transition-transform duration-300">
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="relative z-10">
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                Create Ticket
                            </h3>
                            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mb-4 sm:mb-6">
                                Submit a new support request
                            </p>

                            <div
                                class="flex items-center text-blue-600 dark:text-blue-400
                       group-hover:translate-x-2 transition-transform duration-300">
                                <span class="text-sm sm:text-base font-semibold mr-2">Get Started</span>
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </div>
                        </div>

                        {{-- Decorative --}}
                        <div
                            class="absolute top-0 right-0 w-32 h-32 sm:w-40 sm:h-40
                   bg-blue-400/10 rounded-full blur-3xl -z-10">
                        </div>
                    </div>
                </a>


                {{-- My Tickets Card --}}
                <a href="{{ route('DashboardTicketsUser.indexUser') }}"
                    class="block group relative rounded-xl sm:rounded-2xl bg-gradient-to-br from-blue-400 to-blue-500 p-[2px]
           transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl hover:shadow-blue-400/30">
                    <div
                        class="relative bg-white dark:bg-gray-900 rounded-xl sm:rounded-2xl p-5 sm:p-8 overflow-hidden">

                        {{-- Icon --}}
                        <div class="mb-4 sm:mb-6 relative z-10">
                            <div
                                class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-blue-400 flex items-center justify-center
                       transform group-hover:rotate-6 transition-transform duration-300">
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                </svg>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="relative z-10">
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                My Tickets
                            </h3>
                            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mb-4 sm:mb-6">
                                View and track your tickets
                            </p>

                            <div
                                class="flex items-center text-blue-600 dark:text-blue-400
                       group-hover:translate-x-2 transition-transform duration-300">
                                <span class="text-sm sm:text-base font-semibold mr-2">View All</span>
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </div>
                        </div>

                        {{-- Decorative --}}
                        <div
                            class="absolute top-0 right-0 w-32 h-32 sm:w-40 sm:h-40
                   bg-blue-300/10 rounded-full blur-3xl -z-10">
                        </div>
                    </div>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>
