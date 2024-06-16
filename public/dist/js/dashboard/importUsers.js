$(document).ready(function () {
    var table;
    var dataArray = [];
    var headers = []; // Declare headers at a higher scope



    $('#excelFile').on('change', function () {
        var file = this.files[0];
        if (file) {
            var formData = new FormData();
            formData.append('excelFile', file);

            // Show loader
            $('#loaderBox').css("display", "flex");

            $.ajax({
                url: '/dashboard/import/users/parse',
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                processData: false,
                contentType: false,
                success: function (response) {
                    console.log('Response:', response); // Log the response to check its structure

                    if (response.error) {
                        Swal.fire('Error', response.error, 'error');
                        $('#loaderBox').css("display", "none");
                        return;
                    }

                    headers = response.headers;
                    var data = response.data;
                    var invalidRows = response.invalidRows;

                    console.log('Headers:', headers); // Log the headers
                    console.log('Data:', data);       // Log the data

                    if (!Array.isArray(headers)) {
                        Swal.fire('Error', 'Invalid headers format.', 'error');
                        $('#loaderBox').css("display", "none");
                        return;
                    }

                    if (!data || data.length === 0) {
                        Swal.fire('Error', 'No data found in the file.', 'error');
                        $('#loaderBox').css("display", "none");
                        return;
                    }

                    // Add id to data
                    dataArray = data.map((row, index) => {
                        row['id'] = index + 1; // Assign a unique id
                        return row;
                    });

                    renderTable();

                    $('#submitBtn').prop('disabled', false);
                    // Hide loader
                    $('#loaderBox').css("display", "none");

                    // Show alert for invalid rows
                    if (invalidRows.length > 0) {
                        var alertHtml = '<div class="alert alert-warning alert-dismissible fade show" role="alert">' +
                            '<strong>Warning!</strong><ul>';
                        invalidRows.forEach(function (message) {
                            alertHtml += '<li>' + message + '</li>';
                        });
                        alertHtml += '</ul><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                        $('#alert').html(alertHtml);
                    }
                },
                error: function (xhr, status, error) {
                    Swal.fire('Error', 'Failed to parse the Excel file. Please ensure the file format is correct.', 'error');
                    // Hide loader
                    $('#loaderBox').css("display", "none");
                }
            });
        }
    });

    function renderTable() {
        // Destroy any existing DataTable instance
        if ($.fn.DataTable.isDataTable('#dataTable')) {
            table.clear().destroy(); // Clear and destroy the table
            $('#dataTable thead').empty(); // Clear the table header
            $('#dataTable tbody').empty(); // Clear the table body
        }

        var headerHtml = '<tr>';
        headers.forEach(function (header) {
            if (header !== 'id') {
                headerHtml += '<th>' + header + '</th>';
            }
        });
        headerHtml += '<th>Actions</th>';
        headerHtml += '</tr>';
        $('#tableHeader').html(headerHtml);

        var bodyHtml = '';
        dataArray.forEach(function (row) {
            var rowHtml = '<tr>';
            headers.forEach(function (header) {
                if (header !== 'id') {
                    rowHtml += '<td>' + (row[header] || '') + '</td>';
                }
            });
            rowHtml += '<td>' +
                '<button type="button" class="btn btn-sm btn-primary edit-btn" data-id="' + row.id + '">Edit</button> ' +
                '<button type="button" class="btn btn-sm btn-danger delete-btn" data-id="' + row.id + '">Delete</button>' +
                '</td>';
            rowHtml += '</tr>';
            bodyHtml += rowHtml;
        });
        $('#tableBody').html(bodyHtml);

        // Initialize DataTable after populating the table
        table = $('#dataTable').DataTable({
            scrollX: true
        });
    }

    $('#dataTable').on('click', '.edit-btn', function () {
        var id = $(this).data('id');
        var rowData = dataArray.find(row => row.id === id);

        console.log('Editing row data before update:', rowData); // Log the row data before updating

        var formHtml = '<input type="hidden" id="editId" name="id" value="' + id + '">';
        headers.forEach(function (header) {
            if (header !== 'id' && header !== 'Actions') {
                formHtml += '<div class="form-group">' +
                    '<label for="edit-' + header + '">' + header.charAt(0).toUpperCase() + header.slice(1) + '</label>' +
                    '<input type="text" class="form-control" id="edit-' + header + '" name="' + header + '" value="' + (rowData[header] || '') + '">' +
                    '</div>';
            }
        });
        formHtml += '<button type="submit " class="btn btn-primary mt-3" id="updateBtn">Update</button>';



        $("#dynamicFormContent").html(formHtml);
        $('#editModal').modal('show');

        $('#updateExcelDataForm').off('submit').on('submit', function (e) {
            e.preventDefault();
            var form = $(this);

            // Show loader
            $('#loaderBox').css("display", "flex");

            // Log serialized form data
            console.log('Serialized form data:', form.serialize());

            // Validate email and role via AJAX
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    $('#loaderBox').css("display", "none");

                    if (response.error) {
                        Swal.fire('Error', response.error, 'error');
                    } else {
                        var id = $('#editId').val();
                        var rowIndex = dataArray.findIndex(row => row.id == id);

                        var editEmail = $('#edit-email').val();

                        if (response.message === "Validation successful.") {
                            var emailExists = dataArray.some(row => row.email === editEmail && row.id != id);

                            if (emailExists) {
                                Swal.fire('Error', 'Email already exists in the Excel dataset.', 'error');
                                return;
                            }

                            headers.forEach(function (header) {
                                if (header !== 'id' && header !== 'Actions') {
                                    dataArray[rowIndex][header] = $('#edit-' + header).val();
                                }
                            });

                            console.log('Updated row data:', dataArray[rowIndex]); // Log the row data after updating

                            renderTable();
                            $('#editModal').modal('hide');
                            $("#dynamicFormContent").html('');
                            Swal.fire('Success', 'Record updated successfully.', 'success');
                        }
                    }
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseJSON.message);
                    $('#loaderBox').css("display", "none");
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log('Error - ' + errorMessage);
                    Swal.fire('Error', xhr.responseJSON.message, 'error');
                }
            });
        });
    });
    $('#dataTable').on('click', '.delete-btn', function () {
        var id = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loader
                $('#loaderBox').css("display", "flex");

                setTimeout(function () {
                    dataArray = dataArray.filter(row => row.id !== id);
                    renderTable();
                    // Hide loader
                    $('#loaderBox').css("display", "none");

                    Swal.fire(
                        'Deleted!',
                        'Your row has been deleted.',
                        'success'
                    );
                }, 1000); // Simulate delay for the delete action
            }
        });
    });

    $('#importUsersDataForm').on('submit', function (e) {
        e.preventDefault();

        // Show loader
        $('#loaderBox').css("display", "flex");

        $.ajax({
            url: '/dashboard/import/users',
            type: 'POST',
            data: JSON.stringify({ _token: $('input[name="_token"]').val(), data: dataArray }),
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                $('#loaderBox').css("display", "none");
                Swal.fire('Success', 'Users imported successfully.', 'success');
                window.location.reload();
            },
            error: function (xhr, status, error) {
                $('#loaderBox').css("display", "none");
                Swal.fire('Error', 'Failed to store the users. Please try again.', 'error');
            }
        });
    });

});
