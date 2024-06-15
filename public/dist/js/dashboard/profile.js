$(document).ready(function () {

    // Set up AJAX to include CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Load user details on page load
    $.ajax({
        url: '/dashboard/profile/full-name',
        method: 'GET',
        success: function (data) {
            $('#user-name').text(data.name);
        }
    });

    $.ajax({
        url: '/dashboard/profile/email',
        method: 'GET',
        success: function (data) {
            $('#user-email').text(data.email);
        }
    });

    // Show edit name modal with current name
    $('.edit-name').on('click', function () {
        $.ajax({
            url: '/dashboard/profile/full-name',
            method: 'GET',
            success: function (data) {
                $('#editName').val(data.name);
                const nameModal = new bootstrap.Modal(document.getElementById('editNameModal'));
                nameModal.show();
            }
        });
    });

    // Show edit email modal with current email
    $('.edit-email').on('click', function () {
        $.ajax({
            url: '/dashboard/profile/email',
            method: 'GET',
            success: function (data) {
                $('#editEmail').val(data.email);
                const emailModal = new bootstrap.Modal(document.getElementById('editEmailModal'));
                emailModal.show();
            }
        });
    });

    // Show edit password modal
    $('.edit-password').on('click', function () {
        const passwordModal = new bootstrap.Modal(document.getElementById('editPasswordModal'));
        passwordModal.show();
    });

    // Edit name
    $('#edit-name-form').submit(function (e) {
        e.preventDefault();
        let name = $('#editName').val();

        if (name.trim() === '') {
            Swal.fire('Warning', 'Name field is required', 'warning');
            return;
        }

        $.ajax({
            url: '/dashboard/profile/full-name',
            method: 'POST',
            data: { name: name },
            success: function (data) {
                Swal.fire('Success', data.success, 'success').then((result) => {
                    if (result.isConfirmed) {
                        const nameModal = bootstrap.Modal.getInstance(document.getElementById('editNameModal'));
                        nameModal.hide();
                        location.reload();
                        $('#user-name').text(name);
                    }
                });
            },
            error: function (jqXHR) {
                console.log(jqXHR);
                if (jqXHR.responseJSON && jqXHR.responseJSON.errors && jqXHR.responseJSON.errors.name) {
                    Swal.fire('Error', jqXHR.responseJSON.errors.name[0], 'error');
                } else {
                    Swal.fire('Error', 'An error occurred while updating the name. Please try again.', 'error');
                }
            }
        });
    });

    // Edit email
    $('#edit-email-form').submit(function (e) {
        e.preventDefault();
        let email = $('#editEmail').val();

        if (email.trim() === '') {
            Swal.fire('Warning', 'Email field is required', 'warning');
            return;
        }

        $.ajax({
            url: '/dashboard/profile/email',
            method: 'POST',
            data: { email: email },
            success: function (data) {
                Swal.fire('Success', data.success, 'success');
                const emailModal = bootstrap.Modal.getInstance(document.getElementById('editEmailModal'));
                emailModal.hide();
                $('#user-email').text(email);
            },
            error: function (jqXHR) {
                if (jqXHR.responseJSON && jqXHR.responseJSON.errors && jqXHR.responseJSON.errors.email) {
                    Swal.fire('Error', jqXHR.responseJSON.errors.email[0], 'error');
                } else {
                    Swal.fire('Error', 'An error occurred while updating the email. Please try again.', 'error');
                }
            }
        });
    });

    // Edit password
    $('#edit-password-form').submit(function (e) {
        e.preventDefault();
        let current_password = $('#current_password').val();
        let new_password = $('#new_password').val();
        let new_password_confirmation = $('#new_password_confirmation').val();

        if (current_password.trim() === '' || new_password.trim() === '' || new_password_confirmation.trim() === '') {
            Swal.fire('Warning', 'All fields are required', 'warning');
            return;
        }

        if (new_password !== new_password_confirmation) {
            Swal.fire('Warning', 'New password and confirm password do not match', 'warning');
            return;
        }

        $.ajax({
            url: '/dashboard/profile/password',
            method: 'POST',
            data: {
                current_password: current_password,
                new_password: new_password,
                new_password_confirmation: new_password_confirmation
            },
            success: function (data) {
                Swal.fire('Success', data.success, 'success');
                const passwordModal = bootstrap.Modal.getInstance(document.getElementById('editPasswordModal'));
                passwordModal.hide();
            },
            error: function (jqXHR) {
                if (jqXHR.responseJSON && jqXHR.responseJSON.error) {
                    Swal.fire('Error', jqXHR.responseJSON.error, 'error');
                } else {
                    Swal.fire('Error', 'An error occurred while updating the password. Please try again.', 'error');
                }
            }
        });
    });
});
