document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggle = document.getElementById('sidebarToggle');

    if (toggle && sidebar && overlay) {
        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
        });
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
        });
    }

    document.querySelectorAll('[data-confirm]').forEach((el) => {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            const form = this.closest('form');
            const message = this.getAttribute('data-confirm') || 'Are you sure?';
            const title = this.getAttribute('data-confirm-title') || 'Please Confirm';
            const confirmText = this.getAttribute('data-confirm-btn') || 'Yes, continue';
            const icon = this.getAttribute('data-confirm-icon') || 'warning';

            Swal.fire({
                title: title,
                text: message,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: '#ff6b00',
                cancelButtonColor: '#64748b',
                confirmButtonText: confirmText,
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                customClass: { popup: 'rounded-4' }
            }).then((result) => {
                if (result.isConfirmed && form) {
                    form.submit();
                }
            });
        });
    });

    const toast = document.getElementById('flashToast');
    if (toast) {
        const message = toast.dataset.message;
        const type = toast.dataset.type || 'success';
        if (message) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: type,
                title: message,
                showConfirmButton: false,
                timer: 3200,
                timerProgressBar: true
            });
        }
    }
});
