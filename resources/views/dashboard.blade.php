<x-app-layout>
    <x-slot name="header"></x-slot>

    <div class="py-10 sm:py-14">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10">

            {{-- Menu Cards Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 lg:gap-8">

                {{-- Create Ticket Card --}}
                <a href="{{ route('DashboardTicketsUser.createUser') }}"
                   class="group relative overflow-hidden rounded-2xl bg-white/70 dark:bg-gray-900/60
                          ring-1 ring-gray-200/70 dark:ring-gray-800
                          shadow-sm hover:shadow-lg transition-all duration-300
                          hover:-translate-y-0.5">
                    {{-- Subtle top accent --}}
                    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-blue-500/80 via-sky-400/60 to-blue-500/80"></div>

                    {{-- Decorative blobs (very subtle) --}}
                    <div class="pointer-events-none absolute -top-16 -right-16 h-40 w-40 rounded-full bg-blue-500/10 blur-3xl"></div>
                    <div class="pointer-events-none absolute -bottom-20 -left-20 h-48 w-48 rounded-full bg-sky-500/10 blur-3xl"></div>

                    <div class="relative p-6 sm:p-8">
                        {{-- Icon --}}
                        <div class="mb-5">
                            <div class="inline-flex h-12 w-12 sm:h-14 sm:w-14 items-center justify-center rounded-2xl
                                        bg-blue-600/10 dark:bg-blue-500/15
                                        ring-1 ring-blue-600/15 dark:ring-blue-400/15
                                        transition-transform duration-300 group-hover:scale-105">
                                <svg class="h-6 w-6 sm:h-7 sm:w-7 text-blue-600 dark:text-blue-400"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                        </div>

                        {{-- Content --}}
                        <h3 class="text-xl sm:text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                            Create Ticket
                        </h3>
                        <p class="mt-2 text-sm sm:text-base text-gray-600 dark:text-gray-400">
                            Submit a new support request
                        </p>

                        {{-- CTA --}}
                        <div class="mt-6 inline-flex items-center gap-2 text-sm sm:text-base font-semibold
                                    text-blue-600 dark:text-blue-400">
                            <span class="transition-colors duration-300 group-hover:text-blue-700 dark:group-hover:text-blue-300">
                                Get Started
                            </span>
                            <svg class="h-4 w-4 sm:h-5 sm:w-5 transition-transform duration-300 group-hover:translate-x-1"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </div>
                    </div>
                </a>

                {{-- My Tickets Card --}}
                <a href="{{ route('DashboardTicketsUser.indexUser') }}"
                   class="group relative overflow-hidden rounded-2xl bg-white/70 dark:bg-gray-900/60
                          ring-1 ring-gray-200/70 dark:ring-gray-800
                          shadow-sm hover:shadow-lg transition-all duration-300
                          hover:-translate-y-0.5">
                    {{-- Subtle top accent --}}
                    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-blue-500/80 via-sky-400/60 to-blue-500/80"></div>

                    {{-- Decorative blobs (very subtle) --}}
                    <div class="pointer-events-none absolute -top-16 -right-16 h-40 w-40 rounded-full bg-blue-500/10 blur-3xl"></div>
                    <div class="pointer-events-none absolute -bottom-20 -left-20 h-48 w-48 rounded-full bg-sky-500/10 blur-3xl"></div>

                    <div class="relative p-6 sm:p-8">
                        {{-- Icon --}}
                        <div class="mb-5">
                            <div class="inline-flex h-12 w-12 sm:h-14 sm:w-14 items-center justify-center rounded-2xl
                                        bg-blue-600/10 dark:bg-blue-500/15
                                        ring-1 ring-blue-600/15 dark:ring-blue-400/15
                                        transition-transform duration-300 group-hover:scale-105">
                                <svg class="h-6 w-6 sm:h-7 sm:w-7 text-blue-600 dark:text-blue-400"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                </svg>
                            </div>
                        </div>

                        {{-- Content --}}
                        <h3 class="text-xl sm:text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                            My Tickets
                        </h3>
                        <p class="mt-2 text-sm sm:text-base text-gray-600 dark:text-gray-400">
                            View and track your tickets
                        </p>

                        {{-- CTA --}}
                        <div class="mt-6 inline-flex items-center gap-2 text-sm sm:text-base font-semibold
                                    text-blue-600 dark:text-blue-400">
                            <span class="transition-colors duration-300 group-hover:text-blue-700 dark:group-hover:text-blue-300">
                                View All
                            </span>
                            <svg class="h-4 w-4 sm:h-5 sm:w-5 transition-transform duration-300 group-hover:translate-x-1"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </div>
                    </div>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>
