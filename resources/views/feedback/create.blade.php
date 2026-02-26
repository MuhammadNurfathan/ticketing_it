<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Feedback Ticket') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6 sm:p-8 text-gray-900 dark:text-gray-100">

                    {{-- Success Message --}}
                    @if (session('success'))
                        <div class="mb-6 p-4 text-green-800 dark:text-green-200 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                    @endif

                    {{-- Ticket Info --}}
                    <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                        <div class="flex items-start gap-3">
                            <div class="bg-blue-600 dark:bg-blue-500 rounded-full p-2">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ $ticket->ticket_code }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $ticket->problem }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Form Title --}}
                    <div class="mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                            Berikan Feedback Anda
                        </h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Bagikan pengalaman Anda dengan layanan yang telah diberikan
                        </p>
                    </div>

                    <form action="{{ route('feedback.save') }}" method="POST" class="space-y-6">
    @csrf
    <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">

    @php
        $fields = [
            ['key' => 'speed_rating',   'label' => 'Kecepatan IT Support'],
            ['key' => 'waiting_rating', 'label' => 'Waktu Menunggu'],
            ['key' => 'solution_rating','label' => 'Kualitas Solusi'],
        ];
    @endphp

    {{-- 3 Rating --}}
    @foreach($fields as $f)
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                {{ $f['label'] }} <span class="text-red-500">*</span>
            </label>

            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex gap-2 star-group" data-input="{{ $f['key'] }}">
                    @for($i=1; $i<=5; $i++)
                        <button type="button"
                                data-rating="{{ $i }}"
                                class="star-btn transition-all hover:scale-110 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded">
                            <svg class="w-8 h-8
                                {{ (isset($feedback) && ($feedback->{$f['key']} ?? 0) >= $i) ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </button>
                    @endfor
                </div>

                <input type="hidden"
                       name="{{ $f['key'] }}"
                       id="{{ $f['key'] }}"
                       value="{{ old($f['key'], $feedback->{$f['key']} ?? '') }}"
                       required>

                <div class="flex items-center">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400 rating-text"
                          data-text="{{ $f['key'] }}">
                        {{ old($f['key'], $feedback->{$f['key']} ?? '') ? old($f['key'], $feedback->{$f['key']} ?? '') . ' / 5' : 'Pilih rating' }}
                    </span>
                </div>
            </div>

            @error($f['key'])
                <div class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</div>
            @enderror
        </div>
    @endforeach

    {{-- Comment --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Comment <span class="text-red-500">*</span>
        </label>

        <textarea name="comment" rows="5"
            class="block w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700
                   text-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20
                   dark:focus:border-blue-400 dark:focus:ring-blue-400/20 transition-colors resize-none"
            required>{{ old('comment', $feedback->comment ?? '') }}</textarea>

        @error('comment')
            <div class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</div>
        @enderror
    </div>

    {{-- Buttons --}}
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
        <a href="{{ route('DashboardTicketsUser.index') }}"
           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600
                  text-gray-700 dark:text-gray-200 font-medium rounded-lg transition-colors">
            Kembali
        </a>

        <button type="submit"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600
                       text-white font-medium rounded-lg shadow-sm hover:shadow-md transition-all">
            Kirim Feedback
        </button>
    </div>
</form>



                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript for Star Rating --}}
   <script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.star-group').forEach(group => {
        const inputName = group.dataset.input;
        const hiddenInput = document.getElementById(inputName);
        const text = document.querySelector(`.rating-text[data-text="${inputName}"]`);
        const buttons = group.querySelectorAll('.star-btn');

        function paint(rating) {
            buttons.forEach((btn, idx) => {
                const star = btn.querySelector('svg');
                if (idx < rating) {
                    star.classList.add('text-yellow-400');
                    star.classList.remove('text-gray-300', 'dark:text-gray-600');
                } else {
                    star.classList.add('text-gray-300', 'dark:text-gray-600');
                    star.classList.remove('text-yellow-400');
                }
            });
            hiddenInput.value = rating;
            text.textContent = rating ? `${rating} / 5` : 'Pilih rating';
        }

        // init from old value
        paint(parseInt(hiddenInput.value || 0));

        buttons.forEach(btn => {
            btn.addEventListener('click', () => paint(parseInt(btn.dataset.rating)));
        });

        group.addEventListener('mouseleave', () => paint(parseInt(hiddenInput.value || 0)));
    });
});
</script>
</x-app-layout>