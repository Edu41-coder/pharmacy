document.addEventListener('DOMContentLoaded', function() {
    const autoHideFlashMessages = () => {
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.classList.remove('show');
                setTimeout(() => alert.remove(), 150);
            });
        }, 5000);
    };

    const initCloseButtons = () => {
        document.querySelectorAll('.btn-close').forEach(button => {
            button.addEventListener('click', function() {
                const alert = this.closest('.alert');
                alert.classList.remove('show');
                setTimeout(() => alert.remove(), 150);
            });
        });
    };

    autoHideFlashMessages();
    initCloseButtons();
});