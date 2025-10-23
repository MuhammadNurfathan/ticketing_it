{{-- resources/views/components/modal-form.blade.php --}}
@props(['id' => 'projectModal', 'title' => 'Form Project', 'size' => 'max-w-4xl'])

<div id="{{ $id }}" 
     class="hidden fixed inset-0 z-50 flex items-center justify-center backdrop-blur-sm bg-gray-900 bg-opacity-50 transition-opacity duration-300 overflow-auto p-4 sm:p-6"
     onclick="if(event.target === this) closeModal('{{ $id }}')">

    <div class="relative w-full sm:w-11/12 {{ $size }} max-h-[90vh] overflow-y-auto p-5 border rounded-md shadow-lg bg-white dark:bg-dark-eval-1 dark:border-gray-700
                transform scale-95 opacity-0 transition-all duration-300 ease-out"
         id="{{ $id }}-content">

        {{-- Header --}}
        <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-semibold text-light-text dark:text-dark-text">{{ $title }}</h3>
            <button onclick="closeModal('{{ $id }}')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Content --}}
        <div class="mt-4">
            {{ $slot }}
        </div>
    </div>
</div>

<script>
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    const content = document.getElementById(modalId + '-content');

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    const content = document.getElementById(modalId + '-content');

    content.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';

        const form = modal.querySelector('form');
        if (form) form.reset();
    }, 300);
}

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modals = document.querySelectorAll('[id$="Modal"]');
        modals.forEach(modal => {
            if (!modal.classList.contains('hidden')) {
                closeModal(modal.id);
            }
        });
    }
});
</script>
