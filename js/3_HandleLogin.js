$(document).ready(function() {
    // Initialize validation engine
    $("#loginForm").validationEngine();
    
    // Handle form submission
    $("#loginForm").on("submit", function(e) {
        e.preventDefault();
        
        // Validate form
        if (!$("#loginForm").validationEngine('validate')) {
            return;
        }
        
        // Show loading state
        $("#btnText").text("Logging in...");
        $("#btnSpinner").removeClass("d-none");
        $("#loginBtn").prop("disabled", true);
        
        // Clear previous alerts
        $("#alert-container").empty();
        
        // Get form data
        const formData = {
            email: $("#email").val().trim(),
            password: $("#password").val(),
            rememberMe: $("#rememberMe").is(":checked") ? 1 : 0,
            csrf_token: $("input[name='csrf_token']").val()
        };
        
        // DEBUG: Log form data to console
        console.log("Form data being sent:", formData);
        console.log("CSRF token:", formData.csrf_token);
        
        // Send AJAX request
        $.ajax({
            url: "../controllers/2_HandleLogin.php",
            type: "POST",
            data: formData,
            dataType: "json",
            success: function(response) {
                console.log("Server response:", response); // DEBUG
                
                if (response.success) {
                    // Show success message
                    $("#alert-container").html(`
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            ${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `);
                    
                    // Redirect after short delay
                    setTimeout(function() {
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            window.location.href = "profile.php";
                        }
                    }, 1500);
                    
                } else {
                    // Show error message
                    $("#alert-container").html(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            ${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `);
                    
                    // Show field-specific errors if any
                    if (response.errors) {
                        $.each(response.errors, function(field, message) {
                            $("#" + field).addClass("is-invalid");
                            $("#" + field).after(`<div class="invalid-feedback">${message}</div>`);
                        });
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
                console.error("Response status:", xhr.status);
                console.error("Response text:", xhr.responseText);
                
                $("#alert-container").html(`
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Something went wrong. Please try again. (${xhr.status}: ${error})
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);
            },
            complete: function() {
                // Reset button state
                $("#btnText").text("Login");
                $("#btnSpinner").addClass("d-none");
                $("#loginBtn").prop("disabled", false);
            }
        });
    });
    
    // Clear validation errors on input
    $("#email, #password").on("input", function() {
        $(this).removeClass("is-invalid");
        $(this).next(".invalid-feedback").remove();
    });
    
    // Handle remember me functionality
    $("#rememberMe").on("change", function() {
        if ($(this).is(":checked")) {
            console.log("Remember me checked");
        } else {
            console.log("Remember me unchecked");
        }
    });
});