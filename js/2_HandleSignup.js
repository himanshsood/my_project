$(document).ready(function() {
    // Initialize validation engine
    $("#registrationForm").validationEngine();
    
    $('#registrationForm').on('submit', function(e) {
        e.preventDefault();
        
        // Validate form first
        if (!$("#registrationForm").validationEngine('validate')) {
            return false;
        }
        
        // Show loading state
        $('#registerBtn').prop('disabled', true);
        $('#btnText').text('Creating Account...');
        $('#btnSpinner').removeClass('d-none');
        
        // Clear previous alerts
        $('#alert-container').empty();
        
        $.ajax({
            url: '../controllers/1_HandleRegister.php', // Correct path to your main handler
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                console.log('Response:', response);
                
                if (response.success) {
                    // Show success message
                    $('#alert-container').html(
                        '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                        response.message +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                        '</div>'
                    );
                    
                    // Redirect to OTP verification page
                    setTimeout(function() {
                        if (response.redirect) {
                            window.location.href = '../pages/' + response.redirect;
                        } else {
                            window.location.href = '../pages/verify_otp.php';
                        }
                    }, 1500);
                    
                } else {
                    // Show error message
                    $('#alert-container').html(
                        '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                        response.message +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                        '</div>'
                    );
                    
                    // Show field-specific errors if they exist
                    if (response.errors) {
                        $.each(response.errors, function(field, error) {
                            $('#' + field).addClass('is-invalid');
                            $('#' + field).after('<div class="invalid-feedback">' + error + '</div>');
                        });
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Ajax Error:', error);
                console.error('Response:', xhr.responseText);
                
                $('#alert-container').html(
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                    'An error occurred. Please try again.' +
                    '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                    '</div>'
                );
            },
            complete: function() {
                // Reset button state
                $('#registerBtn').prop('disabled', false);
                $('#btnText').text('Create Account');
                $('#btnSpinner').addClass('d-none');
            }
        });
    });
    
    // Clear validation errors on input
    $('input').on('input', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').remove();
    });
});