$(function () {
    $('#contactUsForm').validationEngine();

    $('#contactUsForm').submit(function (e) {
        e.preventDefault();

        // Proceed only if validation passes
        if (!$('#contactUsForm').validationEngine('validate')) {
            return;
        }

        let formData = new FormData();
        formData.append("name", $('#name').val());
        formData.append("email", $('#email').val());
        formData.append("phone", $('#phone').val());
        formData.append("message", $('#message').val());
        formData.append("file", $('#file')[0].files[0]);
        formData.append("csrfToken", $('#csrfToken').val());

        $.ajax({
            url: "../controllers/2_save_contact_ajax.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                console.log(response);
                if (response.status === "success") {
                    alert(response.message);
                    $('#contactUsForm')[0].reset();
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function (xhr) {
                console.log("Status Code: " + xhr.status);
                console.log("Response: " + xhr.responseText);
                alert("Internal Server Error !");
            }
        });
    });
});
