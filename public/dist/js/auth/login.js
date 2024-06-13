$(document).ready(function () {
    // Toggle password visibility
    $('#password-hide-and-show-addon').click(function () {
        var type = $('#password').attr('type');

        if (type === 'text') {
            $("#passwordEyeIcon").removeClass('fa-eye-slash').addClass('fa-eye');
            $('#password').attr('type', 'password');
        } else {
            $("#passwordEyeIcon").removeClass('fa-eye').addClass('fa-eye-slash');
            $('#password').attr('type', 'text');
        }
    });

    // Handle form submission with AJAX
    $('#loginForm').submit(function (event) {
        event.preventDefault();

        // Clear previous error messages
        $('.error-message').remove();

        // Client-side validation
        var email = $('#email').val();
        var password = $('#password').val();
        var isValid = true;
        var errorMessage = '';

        if (email === '') {
            isValid = false;
            errorMessage = 'Email is required';
        }

        if (password === '') {
            isValid = false;
            errorMessage = 'Password is required';
        }

        if (!isValid) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: errorMessage,
            });
            return;
        }

        // Show loader
        $('#loaderBox').css("display", "flex");

        // AJAX request
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: {
                email: email,
                password: password,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                // Hide loader
                $('#loaderBox').css("display", "none");

                if (response.status === 'success' && response.code === 200) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                    }).then(function () {
                        window.location.href = response.redirect_url; 
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed',
                        text: response.message,
                    });
                }
            },
            error: function (xhr) {
                // Hide loader
                $('#loaderBox').css("display", "none");

                // Display error message
                Swal.fire({
                    icon: 'error',
                    title: 'Login Failed',
                    text: xhr.responseJSON.message || 'An error occurred, please try again.',
                });
            }
        });
    });
});
