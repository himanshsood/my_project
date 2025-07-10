$(function() {
            // Auto-focus on OTP input
            $('#otp').focus();
            
            // Only allow numbers in OTP input
            $('#otp').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                if (this.value.length > 6) {
                    this.value = this.value.slice(0, 6);
                }
            });
            
            // Show alert message
            function showAlert(message, type = 'danger') {
                const alertHtml = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                $('#alert-container').html(alertHtml);
            }
            
            // Clear error states
            function clearErrors() {
                $('#otp').removeClass('is-invalid');
                $('#otp-error').text('');
                $('#alert-container').empty();
            }
            
            // Validate OTP
            function validateOTP() {
                const otp = $('#otp').val().trim();
                
                if (!otp) {
                    $('#otp').addClass('is-invalid');
                    $('#otp-error').text('Please enter the verification code');
                    return false;
                }
                
                if (!/^\d{6}$/.test(otp)) {
                    $('#otp').addClass('is-invalid');
                    $('#otp-error').text('Please enter a valid 6-digit code');
                    return false;
                }
                
                return true;
            }
            
            // Handle form submission
            $('#otpForm').on('submit', function(e) {
                e.preventDefault();
                clearErrors();
                
                if (!validateOTP()) {
                    return;
                }
                
                // Show loading state
                $('#verifyBtn').prop('disabled', true);
                $('#btnText').text('Verifying...');
                $('#btnSpinner').removeClass('d-none');
                
                const otp = $('#otp').val().trim();
                
                $.ajax({
                    url: 'process_otp_verification.php',
                    type: 'POST',
                    data: { otp: otp },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#verification-form').hide();
                            $('#success-message').show();
                        } else {
                            showAlert(response.message, 'danger');
                            if (response.field_error) {
                                $('#otp').addClass('is-invalid');
                                $('#otp-error').text(response.message);
                            }
                        }
                    },
                    error: function(xhr, status, error) {
    showAlert('An error occurred. Please try again.', 'danger');
    console.error('AJAX Error:', error);
    console.error('Raw Response:', xhr.responseText); // 🔍 Add this line
},

                    complete: function() {
                        // Reset button state
                        $('#verifyBtn').prop('disabled', false);
                        $('#btnText').text('Verify OTP');
                        $('#btnSpinner').addClass('d-none');
                    }
                });
            });
            
            // Handle resend OTP
            $('#resendOtpBtn').on('click', function () {
    $.ajax({
        url: '../controllers/resend_otp.php',
        type: 'POST',
        success: function (response) {
            if (response.success) {
                showAlert(response.message, 'success'); // You can customize this
            } else {
                showAlert(response.message, 'danger');
                if (response.redirect) {
                    setTimeout(() => {
                        window.location.replace(response.redirect);
                    }, 2000);
                }
            }
        },
        error: function () {
            showAlert('An error occurred while resending the OTP.', 'danger');
        }
    });
});
            
            // Clear errors on input
            $('#otp').on('input', function() {
                if ($(this).val().length === 6) {
                    $(this).removeClass('is-invalid');
                    $('#otp-error').text('');
                }
            });
        });