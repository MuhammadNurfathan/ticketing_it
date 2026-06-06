
<script>
    document.addEventListener('DOMContentLoaded', function() {

        document.querySelectorAll('.delete-form').forEach(form => {

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const isDark = document.documentElement.classList.contains('dark');

                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: 'Data yang dihapus tidak bisa dikembalikan!',
                    icon: 'warning',

                    background: isDark ? '#1f2937' : '#ffffff',
                    color: isDark ? '#f9fafb' : '#111827',

                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',

                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',

                    reverseButtons: true,

                    customClass: {
                        popup: 'rounded-2xl shadow-2xl'
                    }

                }).then((result) => {

                    if (result.isConfirmed) {
                        form.submit();
                    }

                });

            });

        });

    });
</script>

