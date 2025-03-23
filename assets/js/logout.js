$(document).ready(function() {
    // Handle logout
    $('#logoutBtn').click(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'api/logout.php',
            method: 'POST',
            success: function(response) {
                window.location.href = 'login.php';
            },
            error: function() {
                window.location.href = 'login.php';
            }
        });
    });
});