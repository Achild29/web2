$(document).ready(function() {
    // Dapatkan referensi ke modal
    var deleteModal = document.getElementById('confirmDeleteModal');

    // Dengarkan event saat modal akan ditampilkan
    deleteModal.addEventListener('show.bs.modal', function (event) {
        // Tombol yang memicu modal
        var button = event.relatedTarget; 

        // Ambil data dari atribut data-* tombol
        var taskId = button.getAttribute('data-id');
        var taskName = button.getAttribute('data-hp');

        // Perbarui konten di dalam modal body
        var modalTaskId = deleteModal.querySelector('#modal-delete-task-id');
        var modalTaskName = deleteModal.querySelector('#modal-delete-task-name');
        
        modalTaskId.textContent = taskId;
        modalTaskName.textContent = taskName;

        // Perbarui nilai input hidden pada formulir delete
        var formInputId = deleteModal.querySelector('#modal-delete-id');
        formInputId.value = taskId;
    });
});