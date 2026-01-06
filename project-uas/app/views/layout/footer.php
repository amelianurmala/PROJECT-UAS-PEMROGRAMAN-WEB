<footer class="mt-auto py-4 bg-white border-top">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="text-muted small">
                    &copy; <?= date('Y'); ?> <strong>Perpustakaan Digital</strong>. All rights reserved.
                </div>
                <div class="mt-2 mt-md-0">
                    <span class="badge bg-light text-dark border">Version 1.0</span>
                    <span class="ms-2 text-muted small">Made with <i class="bi bi-heart-fill text-danger"></i> for Project UAS</span>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Menutup alert secara otomatis setelah 3 detik
        window.setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 3000);
    </script>
</body>
</html>