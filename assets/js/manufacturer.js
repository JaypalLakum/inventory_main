$(document).ready(function() {
    const nameInput = $('#name');
    const validationMessage = $('#validationMessage');
    const submitButton = $('#submitButton');

    // Real-time validation
    nameInput.on('input', function() {
        const value = $(this).val();
        
        // Update validation message
        if (value.length < 2) {
            validationMessage.text('Name must be at least 2 characters long').addClass('text-danger').removeClass('text-success');
            submitButton.prop('disabled', true);
        } else if (value.length > 50) {
            validationMessage.text('Name cannot exceed 50 characters').addClass('text-danger').removeClass('text-success');
            submitButton.prop('disabled', true);
        } else if (!/^[A-Za-z0-9\s\-&']+$/.test(value)) {
            validationMessage.text('Only letters, numbers, spaces, hyphens, ampersands, and apostrophes are allowed')
                .addClass('text-danger').removeClass('text-success');
            submitButton.prop('disabled', true);
        } else {
            validationMessage.text('Valid manufacturer name').addClass('text-success').removeClass('text-danger');
            submitButton.prop('disabled', false);
        }
    });

    // Load manufacturers
    function loadManufacturers() {
        $.ajax({
            url: 'api/manufacturer.php',
            method: 'GET',
            success: function(response) {
                if(response.success) {
                    let html = '';
                    response.data.forEach(function(manufacturer) {
                        html += `
                            <tr>
                                <td>${manufacturer.name}</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-danger delete-manufacturer" data-id="${manufacturer.id}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#manufacturersTable tbody').html(html || '<tr><td colspan="2" class="text-center">No manufacturers found</td></tr>');
                }
            }
        });
    }

    // Initial load
    loadManufacturers();

    // Handle form submission
    $('#manufacturerForm').submit(function(e) {
        e.preventDefault();
        
        // Disable submit button
        submitButton.prop('disabled', true);
        
        const formData = $(this).serialize();
        $.ajax({
            url: 'api/manufacturer.php',
            method: 'POST',
            data: formData,
            success: function(response) {
                if(response.success) {
                    $('#successAlert').show();
                    $('#errorAlert').hide();
                    $('#manufacturerForm')[0].reset();
                    validationMessage.text('').removeClass('text-success text-danger');
                    loadManufacturers();
                } else {
                    $('#errorAlert').text(response.message).show();
                    $('#successAlert').hide();
                }
            },
            error: function() {
                $('#errorAlert').text('An error occurred. Please try again.').show();
                $('#successAlert').hide();
            },
            complete: function() {
                // Re-enable submit button
                submitButton.prop('disabled', false);
            }
        });
    });

    // Handle delete
    $(document).on('click', '.delete-manufacturer', function() {
        const button = $(this);
        const manufacturerId = button.data('id');
        const manufacturerName = button.closest('tr').find('td:first').text();
        
        // Show confirmation dialog with manufacturer name
        if(confirm(`Are you sure you want to delete the manufacturer "${manufacturerName}"?`)) {
            // Disable the delete button and show loading state
            button.prop('disabled', true)
                  .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            
            $.ajax({
                url: 'api/manufacturer.php',
                method: 'DELETE',
                contentType: 'application/json',
                data: JSON.stringify({ id: manufacturerId }),
                success: function(response) {
                    if(response.success) {
                        // Show success message
                        $('#successAlert').text('Manufacturer deleted successfully.').show();
                        $('#errorAlert').hide();
                        loadManufacturers();
                    } else {
                        // Show error message
                        $('#errorAlert').text(response.message).show();
                        $('#successAlert').hide();
                        
                        // Re-enable the delete button
                        button.prop('disabled', false)
                              .html('<i class="fas fa-trash"></i>');
                    }
                },
                error: function(xhr) {
                    // Handle network or server errors
                    let errorMessage = 'An error occurred while deleting the manufacturer.';
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.message) {
                            errorMessage = response.message;
                        }
                    } catch(e) {}
                    
                    $('#errorAlert').text(errorMessage).show();
                    $('#successAlert').hide();
                    
                    // Re-enable the delete button
                    button.prop('disabled', false)
                          .html('<i class="fas fa-trash"></i>');
                },
                complete: function() {
                    // Scroll to the top to ensure error/success message is visible
                    if ($('#errorAlert').is(':visible') || $('#successAlert').is(':visible')) {
                        $('html, body').animate({
                            scrollTop: $('#alertContainer').offset().top - 20
                        }, 200);
                    }
                }
            });
        }
    });
});

