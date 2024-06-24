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

    let currentRoleId;

    // Event listener for Edit button
    $('#dataTable').on('click', '.edit-role', function (e) {
        $('#loaderBox').css("display", "flex");
        currentRoleId = $(this).data('id');

        $.ajax({
            url: `/dashboard/role/edit/${currentRoleId}`,
            method: 'GET',
            success: function (response) {
                $('#loaderBox').css("display", "none");
                $('#roleName').val(response.name);
                loadPermissions(response.permissions.map(permission => permission.id));
                $('#editDataModal').modal('show');
            },
            error: function (response) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.responseJSON.message
                });
            }
        });
    });

    // Load permissions into the modal
    function loadPermissions(rolePermissions) {
        $('#loaderBox').css("display", "flex");
        $.ajax({
            url: '/dashboard/permissions',
            method: 'GET',
            success: function (data) {
                let tableBody = $('#permissionsTable');
                tableBody.empty();

                // Filter permissions to ensure "Assign Role" is correctly placed
                let newUserPermissions = [];
                let roleManagementPermissions = [];

                $.each(data, function (group, permissions) {
                    let displayGroup = group;

                    if (group.toLowerCase() === 'user') {
                        displayGroup = 'User Management';
                    } else if (group.toLowerCase() === 'users') {
                        displayGroup = 'Import Bulk Users Data';
                    } else if (group.toLowerCase() === 'role') {
                        displayGroup = 'Role Management';
                    } else if (group.toLowerCase() === 'new') {
                        displayGroup = 'New User';
                    } else if (group.toLowerCase() === 'nsap') {
                        displayGroup = 'NSAP Scheme Management';
                    }

                    if (displayGroup === 'New User') {
                        newUserPermissions = newUserPermissions.concat(permissions);
                    } else if (displayGroup === 'Role Management') {
                        roleManagementPermissions = roleManagementPermissions.concat(permissions);
                    } else {
                        let row = '<tr><td>' + displayGroup + '</td><td>';
                        $.each(permissions, function (index, permission) {
                            let shortName = getShortPermissionName(permission.name);
                            let checked = rolePermissions.includes(permission.id) ? 'checked' : '';
                            row += '<div class="form-check form-check-inline">';
                            row += '<input class="form-check-input permission-checkbox" type="checkbox" id="permission' + permission.id + '" value="' + permission.id + '" name="permissionId[]" data-group="' + displayGroup + '" data-permission="' + shortName.toLowerCase() + '" ' + checked + '>';
                            row += '<label class="form-check-label" for="permission' + permission.id + '">' + shortName + '</label>';
                            row += '</div>';
                        });
                        row += '</td></tr>';
                        tableBody.append(row);
                    }
                });

                // Add "Assign Role" to "New User" group
                let assignRolePermission = roleManagementPermissions.find(permission => getShortPermissionName(permission.name).toLowerCase() === 'assign');
                if (assignRolePermission) {
                    newUserPermissions.push(assignRolePermission);
                    roleManagementPermissions = roleManagementPermissions.filter(permission => getShortPermissionName(permission.name).toLowerCase() !== 'assign');
                }

                // Render "New User" permissions
                if (newUserPermissions.length > 0) {
                    let newUserRow = '<tr><td>New User</td><td>';
                    $.each(newUserPermissions, function (index, permission) {
                        let shortName = getShortPermissionName(permission.name);
                        let checked = rolePermissions.includes(permission.id) ? 'checked' : '';
                        if (shortName.toLowerCase() === 'assign') {
                            shortName = 'Assign Role';
                        }
                        newUserRow += '<div class="form-check form-check-inline">';
                        newUserRow += '<input class="form-check-input permission-checkbox" type="checkbox" id="permission' + permission.id + '" value="' + permission.id + '" name="permissionId[]" data-group="New User" data-permission="' + shortName.toLowerCase() + '" ' + checked + '>';
                        newUserRow += '<label class="form-check-label" for="permission' + permission.id + '">' + shortName + '</label>';
                        newUserRow += '</div>';
                    });
                    newUserRow += '</td></tr>';
                    tableBody.append(newUserRow);
                }

                // Render "Role Management" permissions
                if (roleManagementPermissions.length > 0) {
                    let roleManagementRow = '<tr><td>Role Management</td><td>';
                    $.each(roleManagementPermissions, function (index, permission) {
                        let shortName = getShortPermissionName(permission.name);
                        let checked = rolePermissions.includes(permission.id) ? 'checked' : '';
                        roleManagementRow += '<div class="form-check form-check-inline">';
                        roleManagementRow += '<input class="form-check-input permission-checkbox" type="checkbox" id="permission' + permission.id + '" value="' + permission.id + '" name="permissionId[]" data-group="Role Management" data-permission="' + shortName.toLowerCase() + '" ' + checked + '>';
                        roleManagementRow += '<label class="form-check-label" for="permission' + permission.id + '">' + shortName + '</label>';
                        roleManagementRow += '</div>';
                    });
                    roleManagementRow += '</td></tr>';
                    tableBody.append(roleManagementRow);
                }

                // Add event listener for permission checkboxes
                $('.permission-checkbox').change(function () {
                    let group = $(this).data('group');
                    let permission = $(this).data('permission');

                    if (permission === 'edit' || permission === 'delete' || permission === 'assign role') {
                        if ($(this).is(':checked')) {
                            // Automatically check the 'view' permission
                            $(`.permission-checkbox[data-group="${group}"][data-permission="view"]`).prop('checked', true);
                        } else {
                            // Automatically uncheck the 'view' permission if 'edit', 'delete', or 'assign role' is unchecked
                            let otherPermissionsChecked = $(`.permission-checkbox[data-group="${group}"][data-permission="edit"]:checked`).length > 0 ||
                                $(`.permission-checkbox[data-group="${group}"][data-permission="delete"]:checked`).length > 0 ||
                                $(`.permission-checkbox[data-group="${group}"][data-permission="assign role"]:checked`).length > 0;
                            if (!otherPermissionsChecked) {
                                $(`.permission-checkbox[data-group="${group}"][data-permission="view"]`).prop('checked', false);
                            }
                        }
                    }
                });
                $('#loaderBox').css("display", "none");
            }
        });
    }

    // Handle form submission for editing role
    $('#editRoleForm').submit(function (event) {
        event.preventDefault();
        $('#loaderBox').css("display", "flex");

        let roleName = $('#roleName').val();
        let permissions = [];
        $('input[name="permissionId[]"]:checked').each(function () {
            permissions.push($(this).val());
        });

        // Check if 'view' permission is unchecked while 'edit', 'delete', or 'assign role' is checked
        let invalidGroups = [];
        $('.permission-checkbox').each(function () {
            let group = $(this).data('group');
            if ($(this).data('permission') === 'view' && !$(this).is(':checked')) {
                let hasEditOrDeleteOrAssign = $(`.permission-checkbox[data-group="${group}"][data-permission="edit"]:checked`).length > 0 ||
                    $(`.permission-checkbox[data-group="${group}"][data-permission="delete"]:checked`).length > 0 ||
                    $(`.permission-checkbox[data-group="${group}"][data-permission="assign role"]:checked`).length > 0;
                if (hasEditOrDeleteOrAssign) {
                    invalidGroups.push(group);
                }
            }
        });

        if (!roleName) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Role Name is required!'
            });
            return;
        }

        if (permissions.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'At least one permission should be selected!'
            });
            return;
        }

        if (invalidGroups.length > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: `Please check the 'View' permission for the following groups: ${invalidGroups.join(', ')}`
            });
            return;
        }

        $.ajax({
            url: `/dashboard/role/edit/${currentRoleId}`,
            method: 'POST',
            data: {
                name: roleName,
                permissions: permissions,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                $('#loaderBox').css("display", "none");
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message
                }).then(() => {
                    location.reload();  // Reload the page to reflect changes
                });
            },
            error: function (response) {
                $('#loaderBox').css("display", "none");
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: response.responseJSON.message
                });
            }
        });
    });

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    function getShortPermissionName(permission) {
        let parts = permission.split(' ');
        if (parts.length > 1) {
            return capitalizeFirstLetter(parts[0]);
        }
        return permission;
    }
});
