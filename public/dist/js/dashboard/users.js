$(document).ready(function () {
    var table = new DataTable('#dataTable', {
        scrollX: true
    });

    // Set up AJAX to include CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#dataTable').on('click', '.edit-user', function (e) {
        e.preventDefault();
        loadPermissions();
        const userId = $(this).data('id');
        $('#loaderBox').css("display", "flex");

        // Fetch user details
        $.ajax({
            url: `/dashboard/user/${userId}`,
            type: 'GET',
            success: function (user) {
                $('#editName').val(user.name);
                $('#editEmail').val(user.email);
                $('#editDataForm').attr('action', `/dashboard/user/edit/${user.id}`);

                // Fetch roles and populate the select box
                $.ajax({
                    url: `/dashboard/roles/list`,
                    type: 'GET',
                    success: function (roles) {
                        let roleOptions = '<option value="">Select a role</option>';
                        roles.forEach(role => {
                            roleOptions += `<option value="${role.id}" ${user.roles[0] && user.roles[0].id === role.id ? 'selected' : ''}>${role.name}</option>`;
                        });
                        $('#roleSelect').html(roleOptions);
                        $('#roleSelect').select2({
                            dropdownParent: $('#editDataModal'),
                            width: '100%'
                        });
                        $('#roleSelect').trigger('change');
                        $('#loaderBox').css("display", "none");

                        const myModal = new bootstrap.Modal(document.getElementById('editDataModal'));
                        myModal.show();
                    },
                    error: function (xhr) {
                        $('#loaderBox').css("display", "none");
                        Swal.fire('Error', 'Unable to fetch roles', 'error');
                    }
                });
            },
            error: function (xhr) {
                $('#loaderBox').css("display", "none");
                Swal.fire('Error', 'Unable to fetch user data', 'error');
            }
        });
    });

    $('#dataTable').on('click', '.delete-user', function (e) {
        e.preventDefault();
        const userId = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#loaderBox').css("display", "flex");

                $.ajax({
                    url: `/dashboard/user/delete/${userId}`,
                    type: 'DELETE',
                    success: function (response) {
                        $('#loaderBox').css("display", "none");
                        Swal.fire('Deleted!', response.message, 'success').then(() => {
                            location.reload();
                        });
                    },
                    error: function (xhr) {
                        $('#loaderBox').css("display", "none");
                        Swal.fire('Error', 'Unable to delete user', 'error');
                    }
                });
            }
        });
    });


    $("#editDataForm").submit(function (e) {
        e.preventDefault();

        const form = $(this);
        const actionUrl = form.attr('action');
        $('#loaderBox').css("display", "flex");

        if (!validateForm()) {
            Swal.fire('Warning', 'Please fill all required fields correctly', 'warning');
            $('#loaderBox').css("display", "none");
            return;
        }

        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: form.serialize(),
            success: function (response) {
                $('#loaderBox').css("display", "none");
                Swal.fire('Success', response.message, 'success').then(() => {
                    location.reload();
                });
            },
            error: function (xhr) {
                $('#loaderBox').css("display", "none");
                if (xhr.status === 422) {
                    const error = xhr.responseJSON.message;
                    Swal.fire('Error', error, 'error');
                } else {
                    Swal.fire('Error', 'An unexpected error occurred', 'error');
                }
            }
        });
    });

    function validateForm() {
        let isValid = true;

        if ($('#editName').val().trim() === '') {
            isValid = false;
        }

        if ($('#editEmail').val().trim() === '') {
            isValid = false;
        }

        if ($('#roleSelect').val() === null || $('#roleSelect').val() === '') {
            isValid = false;
        }

        return isValid;
    }

    
});
