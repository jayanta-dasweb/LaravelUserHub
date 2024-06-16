$(document).ready(function () {
    permissionsTable
    $('#loaderBox').css("display", "flex");

    // Load permissions on page load
    $.ajax({
        url: '/dashboard/permissions',
        method: 'GET',
        success: function (data) {
            let tableBody = $('#permissionsTable');
            tableBody.empty();

            // Handle custom arrangement for "New User" group
            let newUserPermissions = [];
            let rolePermissions = [];
            let assignRolePermission;

            $.each(data, function (group, permissions) {
                let displayGroup = capitalizeFirstLetter(group);

                // Replace group names with custom names
                if (group.toLowerCase() === 'user') {
                    displayGroup = 'User Management';
                } else if (group.toLowerCase() === 'users') {
                    displayGroup = 'Import Bulk Users Data';
                } else if (group.toLowerCase() === 'role') {
                    displayGroup = 'Role Management';
                } else if (group.toLowerCase() === 'nsap') {
                    displayGroup = 'NSAP Scheme Management';
                }

                if (group.toLowerCase() === 'new') {
                    newUserPermissions = permissions;
                } else if (group.toLowerCase() === 'role') {
                    rolePermissions = permissions.filter(permission => {
                        if (permission.name.includes('assign role')) {
                            assignRolePermission = permission;
                            return false;
                        }
                        return true;
                    });
                } else {
                    let row = '<tr><td>' + displayGroup + '</td><td>';
                    $.each(permissions, function (index, permission) {
                        let shortName = getShortPermissionName(permission.name);
                        row += '<div class="form-check form-check-inline">';
                        row += '<input class="form-check-input permission-checkbox" type="checkbox" id="permission' + permission.id + '" value="' + permission.id + '" name="permissionId[]" data-group="' + displayGroup + '" data-permission="' + shortName.toLowerCase() + '">';
                        row += '<label class="form-check-label" for="permission' + permission.id + '">' + shortName + '</label>';
                        row += '</div>';
                    });
                    row += '</td></tr>';
                    tableBody.append(row);
                }
            });

            // Append "New User" group with "Assign Role" permission
            if (newUserPermissions.length > 0) {
                let row = '<tr><td>New User</td><td>';
                $.each(newUserPermissions, function (index, permission) {
                    let shortName = getShortPermissionName(permission.name);
                    row += '<div class="form-check form-check-inline">';
                    row += '<input class="form-check-input permission-checkbox" type="checkbox" id="permission' + permission.id + '" value="' + permission.id + '" name="permissionId[]" data-group="New User" data-permission="' + shortName.toLowerCase() + '">';
                    row += '<label class="form-check-label" for="permission' + permission.id + '">' + shortName + '</label>';
                    row += '</div>';
                });
                if (assignRolePermission) {
                    let shortName = getShortPermissionName(assignRolePermission.name);
                    row += '<div class="form-check form-check-inline">';
                    row += '<input class="form-check-input permission-checkbox" type="checkbox" id="permission' + assignRolePermission.id + '" value="' + assignRolePermission.id + '" name="permissionId[]" data-group="New User" data-permission="assign role">';
                    row += '<label class="form-check-label" for="permission' + assignRolePermission.id + '">Assign Role</label>';
                    row += '</div>';
                }
                row += '</td></tr>';
                tableBody.append(row);
            }

            // Append "Role Management" group without "Assign Role" permission
            if (rolePermissions.length > 0) {
                let row = '<tr><td>Role Management</td><td>';
                $.each(rolePermissions, function (index, permission) {
                    let shortName = getShortPermissionName(permission.name);
                    row += '<div class="form-check form-check-inline">';
                    row += '<input class="form-check-input permission-checkbox" type="checkbox" id="permission' + permission.id + '" value="' + permission.id + '" name="permissionId[]" data-group="Role Management" data-permission="' + shortName.toLowerCase() + '">';
                    row += '<label class="form-check-label" for="permission' + permission.id + '">' + shortName + '</label>';
                    row += '</div>';
                });
                row += '</td></tr>';
                tableBody.append(row);
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
                        // Automatically uncheck the 'view' permission if 'edit' or 'delete' or 'assign role' is unchecked
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

    // Handle form submission
    $('#createRoleForm').submit(function (event) {
        event.preventDefault();
        $('#loaderBox').css("display", "flex");

        let roleName = $('#roleName').val();
        let permissions = [];
        $('input[name="permissionId[]"]:checked').each(function () {
            permissions.push($(this).val());
        });

        // Check if 'view' permission is unchecked while 'edit' or 'delete' or 'assign role' is checked
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
            url: '/dashboard/role/create',
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
                    $('#roleName').val('');
                    $('input[name="permissionId[]"]').prop('checked', false);
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
