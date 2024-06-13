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

    $('#confirm-password-hide-and-show-addon').click(function () {
        var type = $('#password_confirmation').attr('type');
        if (type === 'text') {
            $("#confirmPasswordEyeIcon").removeClass('fa-eye-slash').addClass('fa-eye');
            $('#password_confirmation').attr('type', 'password');
        } else {
            $("#confirmPasswordEyeIcon").removeClass('fa-eye').addClass('fa-eye-slash');
            $('#password_confirmation').attr('type', 'text');
        }
    });

    $('#registerForm').submit(function (e) {
        e.preventDefault();

        // Client-side validation
        var name = $('#name').val().trim();
        var email = $('#email').val().trim();
        var password = $('#password').val().trim();
        var confirmPassword = $('#password_confirmation').val().trim();
        var errorMessage = '';

        if (name === '') {
            errorMessage = 'Full Name is required';
        } else if (email === '') {
            errorMessage = 'Email is required';
        } else if (!validateEmail(email)) {
            errorMessage = 'Please enter a valid email address';
        } else if (password === '') {
            errorMessage = 'Password is required';
        } else if (password.length < 8) {
            errorMessage = 'Password must be at least 8 characters long';
        } else if (confirmPassword === '') {
            errorMessage = 'Confirm Password is required';
        } else if (password !== confirmPassword) {
            errorMessage = 'Passwords do not match';
        }

        if (errorMessage) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: errorMessage,
            });
            return;
        }

        // AJAX request
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $('#registerForm').serialize(),
            beforeSend: function () {
                $('.error-message').remove(); 
            },
            success: function (response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Registration Successful',
                        text: response.message,
                    }).then(function () {
                        window.location.href = response.redirect_url;
                    });
                }
            },
            error: function (xhr) {
                var errorMessage = xhr.responseJSON.message || 'An error occurred, please try again.';
                Swal.fire({
                    icon: 'error',
                    title: 'Registration Failed',
                    text: errorMessage,
                });
            }
        });
    });

    function validateEmail(email) {
        var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
});
