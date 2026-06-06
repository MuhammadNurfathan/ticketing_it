<x-guest-layout>
    <x-auth-card>
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="grid gap-6">
                <!-- username Address -->
                <div class="space-y-2">
                    <x-label for="username" :value="__('Username')" />

                    <x-input-with-icon-wrapper>
                        <x-slot name="icon">
                            <x-heroicon-o-user aria-hidden="true" class="w-5 h-5" />
                        </x-slot>
                        <x-input withicon id="username" class="block w-full border-gray-300 rounded" type="text"
                            name="username" :value="old('username')" placeholder="{{ __('Username') }}" required />
                    </x-input-with-icon-wrapper>
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <x-label for="password" :value="__('Password')" />

                    <div class="relative">
                        <x-input-with-icon-wrapper>
                            <x-slot name="icon">
                                <x-heroicon-o-lock-closed aria-hidden="true" class="w-5 h-5" />
                            </x-slot>

                            <x-input withicon id="password" class="block w-full pr-10" type="password" name="password"
                                required autocomplete="current-password" placeholder="{{ __('Password') }}" />
                        </x-input-with-icon-wrapper>

                        <!-- Tombol Lihat Password -->
                        <button type="button" onclick="togglePassword()"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700">

                            <!-- Mata open → tampil default -->
                            <x-heroicon-o-eye id="eyeOpen" class="w-5 h-5" />

                            <!-- Mata close → disembunyikan dulu -->
                            <x-heroicon-o-eye-off id="eyeClose" class="w-5 h-5 hidden" />
                        </button>
                    </div>
                </div>



                <div class="flex items-center justify-between">
                  

                    @if (Route::has('password.request'))
                    <a class="text-sm text-blue-500 hover:underline" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                    @endif
                </div   >

                <div>
                    <x-button class="justify-center w-full gap-2">
                        <x-heroicon-o-login class="w-6 h-6" aria-hidden="true" />
                        <span>{{ __('Log in') }}</span>
                    </x-button>
                </div>

            </div>
        </form>
    </x-auth-card>
    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const eyeOpen = document.getElementById('eyeOpen');
            const eyeClose = document.getElementById('eyeClose');

            if (input.type === "password") {
                input.type = "text";
                eyeOpen.classList.add("hidden");
                eyeClose.classList.remove("hidden");
            } else {
                input.type = "password";
                eyeOpen.classList.remove("hidden");
                eyeClose.classList.add("hidden");
            }
        }
    </script>
    <style>
        /* HILANGKAN ICON MATA DI EDGE */
        input::-ms-reveal,
        input::-ms-clear {
            display: none !important;
        }

        /* HILANGKAN ICON MATA DI CHROME */
        input::-webkit-contacts-auto-fill-button,
        input::-webkit-credentials-auto-fill-button {
            visibility: hidden !important;
            display: none !important;
        }

        /* EXTRA FIX BIAR GAK KELUAR SAMA SEKALI */
        input[type="password"] {
            -webkit-text-security: disc !important;
        }
    </style>

</x-guest-layout>
