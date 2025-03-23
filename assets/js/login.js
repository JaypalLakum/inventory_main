$(document).ready(function() {
    console.log("llog");
    
    $('#loginForm').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $button = $('#submitBtn');
        const $spinner = $('#spinner');
        const $alert = $('#loginAlert');
        
        $button.prop('disabled', true);
        $spinner.show();
        $alert.hide();

        $.ajax({
            url: 'api/login.php',
            type: 'POST',
            data: $form.serialize(),
            success: function(response) {
                if (response.success) {
                    window.location.href = 'inventory.php';
                } else {
                    $alert
                        .removeClass('alert-success alert-danger')
                        .addClass('alert-danger')
                        .text(response.message || 'Login failed. Please try again.')
                        .show();
                }
            },
            error: function() {
                $alert
                    .removeClass('alert-success alert-danger')
                    .addClass('alert-danger')
                    .text('An error occurred. Please try again.')
                    .show();
            },
            complete: function() {
                $button.prop('disabled', false);
                $spinner.hide();
            }
        });
    });
});