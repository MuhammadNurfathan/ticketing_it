<form method="GET"
    action="{{ route('DashboardTicketsAdmin.index') }}"
    class="{{ $card }} p-5">

    <div class="flex flex-wrap items-end gap-4">

        {{-- START DATE --}}
        <div class="min-w-[220px]">
            <label class="block text-sm font-semibold mb-2 text-light-text dark:text-dark-text">
                Request Date
            </label>

            <div class="relative">
                <input
                    type="text"
                    id="start_date"
                    name="start_date"
                    value="{{ $start }}"
                    placeholder="Select date..."
                    class="date-picker w-full rounded-xl {{ $input }} pl-11">

                <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                    📅
                </div>
            </div>
        </div>

        {{-- END DATE --}}
        <div class="min-w-[220px]">
            <label class="block text-sm font-semibold mb-2 text-light-text dark:text-dark-text">
                End Date
            </label>

            <div class="relative">
                <input
                    type="text"
                    id="end_date"
                    name="end_date"
                    value="{{ $end }}"
                    placeholder="Select date..."
                    class="date-picker w-full rounded-xl {{ $input }} pl-11">

                <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                    📅
                </div>
            </div>
        </div>

        {{-- BUTTON --}}
        <div class="flex items-center gap-2">

            <button type="submit"
                class="{{ $btnPrimary }}">
                Filter
            </button>

            <a href="{{ route('DashboardTicketsAdmin.index') }}"
                class="{{ $btnGhost }}">
                Reset
            </a>

        </div>

    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const darkMode =
        document.documentElement.classList.contains('dark');

    flatpickr(".date-picker", {
        dateFormat: "Y-m-d",
        allowInput: true,
        altInput: true,
        altFormat: "d F Y",
        theme: darkMode ? "dark" : "light"
    });

});
</script>

