    </div> <!-- End of main-container -->

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/logout.js"></script>
    <?php if($current_page === 'inventory.php'): ?>
    <script src="assets/js/inventory.js"></script>
    <?php endif; ?>
    <?php if($current_page === 'manufacturer.php'): ?>
    <script src="assets/js/manufacturer.js"></script>
    <?php endif; ?>
    <?php if($current_page === 'model.php'): ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <script src="assets/js/model.js"></script>
    <?php endif; ?>
</body>
</html> 