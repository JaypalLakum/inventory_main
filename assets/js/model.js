if(typeof Dropzone !== 'undefined') {
    Dropzone.autoDiscover = false;
}

$(document).ready(function() {
    // Disable Dropzone auto discover
    if(typeof Dropzone !== 'undefined') {
        Dropzone.autoDiscover = false;
    }

    // Real-time validation
    $('#manufacturer_id').on('change', function() {
        const value = $(this).val();
        const message = $(this).closest('.col-md-6').find('.validation-message');
        if (!value) {
            message.text('Please select a manufacturer');
        } else {
            message.text('');
        }
    });

    $('#name').on('input', function() {
        const value = $(this).val();
        const message = $(this).closest('.col-md-6').find('.validation-message');
        if (!value) {
            message.text('Please enter model name');
        } else if (value.length < 2) {
            message.text('Model name must be at least 2 characters');
        } else if (value.length > 50) {
            message.text('Model name cannot exceed 50 characters');
        } else if (!/^[A-Za-z0-9\s\-&']+$/.test(value)) {
            message.text('Model name can only contain letters, numbers, spaces, hyphens, ampersands, and apostrophes');
        } else {
            message.text('');
        }
    });

    $('#color').on('input', function() {
        const value = $(this).val();
        const message = $(this).closest('.col-md-6').find('.validation-message');
        if (!value) {
            message.text('Please enter color');
        } else if (value.length < 2) {
            message.text('Color must be at least 2 characters');
        } else if (value.length > 30) {
            message.text('Color cannot exceed 30 characters');
        } else if (!/^[A-Za-z\s-]+$/.test(value)) {
            message.text('Color can only contain letters, spaces, and hyphens');
        } else {
            message.text('');
        }
    });

    $('#manufacturing_year').on('input', function() {
        const value = $(this).val();
        const currentYear = new Date().getFullYear();
        const message = $(this).closest('.col-md-6').find('.validation-message');
        if (!value) {
            message.text('Please enter manufacturing year');
        } else if (value < 1900 || value > currentYear + 1) {
            message.text(`Year must be between 1900 and ${currentYear + 1}`);
        } else {
            message.text('');
        }
    });

    $('#registration_number').on('input', function() {
        const value = $(this).val().toUpperCase();
        const message = $(this).closest('.mb-4').find('.validation-message');
        if (!value) {
            message.text('Please enter registration number');
        } else if (value.length < 5) {
            message.text('Registration number must be at least 5 characters');
        } else if (value.length > 15) {
            message.text('Registration number cannot exceed 15 characters');
        } else if (!/^[A-Z0-9-]+$/.test(value)) {
            message.text('Registration number can only contain uppercase letters, numbers, and hyphens');
        } else {
            message.text('');
        }
    });

    $('#note').on('input', function() {
        const value = $(this).val();
        const message = $(this).closest('.mb-4').find('.validation-message');
        if (value.length > 500) {
            message.text('Note cannot exceed 500 characters');
        } else {
            message.text('');
        }
    });

    // Initialize Dropzone
    let uploadedFiles = [];
    let myDropzone;
    if(typeof Dropzone !== 'undefined') {
        myDropzone = new Dropzone("#imageUpload", {
            url: "api/upload.php",
            maxFiles: 2,
            acceptedFiles: "image/*",
            maxFilesize: 5, // MB
            addRemoveLinks: true,
            autoProcessQueue: true,
            dictDefaultMessage: '<i class="fas fa-cloud-upload-alt fa-3x mb-3"></i><br>Drop files here or click to upload',
            dictFileTooBig: 'File is too big ({{filesize}}MB). Max filesize: {{maxFilesize}}MB.',
            dictInvalidFileType: 'Invalid file type. Only images are allowed.',
            dictMaxFilesExceeded: 'You can only upload a maximum of {{maxFiles}} files.',
            init: function() {
                this.on("maxfilesexceeded", function(file) {
                    this.removeFile(file);
                    alert("You can only upload 2 images!");
                });
            },
            success: function(file, response) {
                if(response.success) {
                    uploadedFiles.push(response.filename);
                    file.previewElement.classList.add('dz-success');
                } else {
                    file.previewElement.classList.add('dz-error');
                    const message = response.message || 'Upload failed';
                    $(file.previewElement).find('.dz-error-message span').text(message);
                }
            },
            error: function(file, message) {
                file.previewElement.classList.add('dz-error');
                $(file.previewElement).find('.dz-error-message span').text(message);
            },
            removedfile: function(file) {
                const filename = file.name;
                $.ajax({
                    url: 'api/upload.php',
                    type: 'DELETE',
                    data: { filename: filename },
                    success: function(response) {
                        if(response.success) {
                            uploadedFiles = uploadedFiles.filter(f => f !== filename);
                        }
                    }
                });
                file.previewElement.remove();
            }
        });
    }

    // Form submission
    $('#modelForm').on('submit', function(e) {
        e.preventDefault();

        // Validate all fields
        let isValid = true;
        const manufacturer = $('#manufacturer_id').val();
        const name = $('#name').val();
        const color = $('#color').val();
        const year = $('#manufacturing_year').val();
        const regNumber = $('#registration_number').val();
        const note = $('#note').val();

        // Trigger validation for each field
        $('#manufacturer_id').trigger('change');
        $('#name').trigger('input');
        $('#color').trigger('input');
        $('#manufacturing_year').trigger('input');
        $('#registration_number').trigger('input');
        $('#note').trigger('input');

        // Check if any validation messages exist
        $('.validation-message').each(function() {
            if ($(this).text() !== '') {
                isValid = false;
                return false;
            }
        });

        if (!isValid) {
            $('#errorMessage').text('Please fix all validation errors').show();
            $('#successMessage').hide();
            return;
        }

        // Submit form data
        submitFormData();
    });

    function submitFormData() {
        const formData = {
            manufacturer_id: $('#manufacturer_id').val(),
            name: $('#name').val(),
            color: $('#color').val(),
            manufacturing_year: $('#manufacturing_year').val(),
            registration_number: $('#registration_number').val().toUpperCase(),
            note: $('#note').val()
        };

        // Only add images if there are any
        if (uploadedFiles.length > 0) {
            formData.images = uploadedFiles;
        }

        $.ajax({
            url: 'api/model.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                if(response.success) {
                    $('#successMessage').text(response.message).show();
                    $('#errorMessage').hide();
                    $('#modelForm')[0].reset();
                    if(myDropzone) {
                        myDropzone.removeAllFiles();
                    }
                    uploadedFiles = [];
                    // Clear all validation messages
                    $('.validation-message').text('');
                } else {
                    $('#errorMessage').text(response.message).show();
                    $('#successMessage').hide();
                }
            },
            error: function() {
                $('#errorMessage').text('An error occurred. Please try again.').show();
                $('#successMessage').hide();
            }
        });
    }

    // Load manufacturers
    function loadManufacturers() {
        $.ajax({
            url: 'api/manufacturer.php',
            method: 'GET',
            success: function(response) {
                if(response.success && Array.isArray(response.data)) {
                    const select = $('#manufacturer_id');
                    select.empty().append('<option value="">Select Manufacturer</option>');
                    response.data.forEach(function(manufacturer) {
                        select.append(
                            $('<option></option>')
                                .val(manufacturer.id)
                                .text(manufacturer.name)
                        );
                    });
                } else {
                    console.error('Failed to load manufacturers:', response);
                }
            },
        });
    }

    // Initial load of manufacturers
    loadManufacturers();
});