$(document).ready(function () {
    var table = new DataTable('#dataTable', {
        scrollX : true
    });

    // Set up AJAX to include CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

   $('#dataTable').on('click', '.edit-scheme', function (e) {
        e.preventDefault();
        const id = $(this).data('id');
        $('#loaderBox').css("display", "flex");

        // Fetch NSAP scheme details
        $.ajax({
            url: `/dashboard/nsap-scheme/${id}`,
            type: 'GET',
            success: function (scheme) {
                $('#editSchemeCode').val(scheme.scheme_code);
                $('#editSchemeName').val(scheme.scheme_name);
                $('#editCentralStateScheme').val(scheme.central_state_scheme);
                $('#editFinYear').val(scheme.fin_year);
                $('#editStateDisbursement').val(scheme.state_disbursement);
                $('#editCentralDisbursement').val(scheme.central_disbursement);
                $('#editTotalDisbursement').val(scheme.total_disbursement);
                $('#editDataForm').attr('action', `/dashboard/nsap-scheme/edit/${scheme.id}`);
                $('#loaderBox').css("display", "none");

                const myModal = new bootstrap.Modal(document.getElementById('editDataModal'));
                myModal.show();
            },
            error: function (xhr) {
                $('#loaderBox').css("display", "none");
                Swal.fire('Error', 'Unable to fetch scheme data', 'error');
            }
        });
    });
    
    $('#dataTable').on('click', '.delete-scheme', function (e) {
        e.preventDefault();
        const schemeId = $(this).data('id');

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
                    url: `/dashboard/nsap-scheme/delete/${schemeId}`,
                    type: 'DELETE',
                    success: function (response) {
                        $('#loaderBox').css("display", "none");
                        Swal.fire('Deleted!', response.message, 'success').then(() => {
                            location.reload();
                        });
                    },
                    error: function (xhr) {
                        console.log(xhr);
                        $('#loaderBox').css("display", "none");
                        Swal.fire('Error', 'Unable to delete NSAP Scheme', 'error');
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

        if ($('#editSchemeCode').val().trim() === '') {
            isValid = false;
        }

        if ($('#editSchemeName').val().trim() === '') {
            isValid = false;
        }

        if ($('#editCentralStateScheme').val().trim() === '') {
            isValid = false;
        }

        if ($('#editFinYear').val().trim() === '') {
            isValid = false;
        }

        if ($('#editStateDisbursement').val().trim() === '') {
            isValid = false;
        }

        if ($('#editCentralDisbursement').val().trim() === '') {
            isValid = false;
        }

        if ($('#editTotalDisbursement').val().trim() === '') {
            isValid = false;
        }

        return isValid;
    }
});
