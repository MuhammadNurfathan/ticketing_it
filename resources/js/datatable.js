export function initDataTable(selector = ".datatable", options = {}) {

    document.querySelectorAll(selector).forEach((table) => {

        // 🔥 CEK PENTING: sudah pernah di-init atau belum
        if ($.fn.dataTable.isDataTable(table)) {
            return; // skip biar tidak re-init
        }

        new DataTable(table, {
            responsive: true,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],

            layout: {
                topStart: {
                    pageLength: {
                        menu: [5, 10, 25, 50]
                    }
                },
                topEnd: {
                    search: {
                        placeholder: "Cari data..."
                    }
                },
                bottomStart: "info",
                bottomEnd: "paging"
            },

            language: {
                search: "",
                searchPlaceholder: "🔍 Cari data...",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                zeroRecords: "Data tidak ditemukan",
                emptyTable: "Belum ada data",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "›",
                    previous: "‹"
                }
            },

            stripeClasses: [
                'bg-white dark:bg-gray-800',
                'bg-gray-50 dark:bg-gray-700'
            ],

            ...options
        });
    });
}