<style>
[x-cloak] {
    display: none !important;
}

.date-input::-webkit-calendar-picker-indicator {
    filter: invert(0);
    cursor: pointer;
}

.dark .date-input::-webkit-calendar-picker-indicator {
    filter: invert(1);
}

.date-input::-webkit-calendar-picker-indicator:hover {
    opacity: 0.7;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", () => {

    const doneButtons =
        document.querySelectorAll('.doneBtn');

    doneButtons.forEach(btn => {

        const cleanBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(cleanBtn, btn);

        cleanBtn.addEventListener('click', function() {

            const modal =
                this.nextElementSibling;

            const modalContent =
                modal.querySelector('.modalContent');

            const start =
                new Date(this.dataset.start);

            const timeInput =
                modal.querySelector('.timeInput');

            const solutionInput =
                modal.querySelector('.solutionInput');

            const notesContainer =
                modal.querySelector('.notesContainer');

            const notesInput =
                modal.querySelector('.notesInput');

            const manualCheckbox =
                modal.querySelector('.manualCheckbox');

            const form =
                modal.nextElementSibling;

            // OPEN
            modal.classList.remove('hidden');

            setTimeout(() => {
                modal.classList.add('flex');
                modalContent.classList.remove(
                    'opacity-0',
                    'scale-95'
                );
                modalContent.classList.add(
                    'opacity-100',
                    'scale-100'
                );
            }, 10);

            // AUTO CALC
            const calcAutoTime = () => {

                if (
                    !manualCheckbox.checked &&
                    start &&
                    !isNaN(start)
                ) {
                    const now = new Date();

                    const diff = Math.floor(
                        (now - start) / (1000 * 60)
                    );

                    timeInput.value =
                        diff > 0 ? diff : 0;
                }
            };

            calcAutoTime();

            // MANUAL
            manualCheckbox.onchange = () => {

                if (manualCheckbox.checked) {
                    timeInput.removeAttribute(
                        'readonly'
                    );

                    notesContainer.classList
                        .remove('hidden');

                } else {

                    timeInput.setAttribute(
                        'readonly',
                        true
                    );

                    notesContainer.classList
                        .add('hidden');

                    notesInput.value = '';

                    calcAutoTime();
                }
            };

            // CANCEL
            modal.querySelector('.cancelBtn')
                .onclick = () => {

                modalContent.classList.remove(
                    'opacity-100',
                    'scale-100'
                );

                modalContent.classList.add(
                    'opacity-0',
                    'scale-95'
                );

                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }, 150);
            };

            // SAVE
            modal.querySelector('.saveBtn')
                .onclick = () => {

                if (!solutionInput.value.trim()) {
                    alert(
                        'Kolom Solution wajib diisi!'
                    );

                    solutionInput.focus();

                    return;
                }

                if (
                    !notesContainer.classList.contains('hidden') &&
                    !notesInput.value.trim()
                ) {
                    alert(
                        'Kolom Notes wajib diisi saat manual!'
                    );

                    notesInput.focus();

                    return;
                }

                form.querySelector(
                    '.hiddenTimeSpent'
                ).value = timeInput.value;

                form.querySelector(
                    '.hiddenSolution'
                ).value = solutionInput.value;

                form.querySelector(
                    '.hiddenNotes'
                ).value = notesInput.value;

                form.submit();
            };
        });
    });

});
</script>

