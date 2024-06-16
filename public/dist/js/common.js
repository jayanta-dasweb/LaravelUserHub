$(window).on('load', function () {
    $('#loaderBox').css("display", "none");
    loadPermissions();

    // Function to fetch count of users without roles
    function fetchUsersWithoutRolesCount() {
        $.ajax({
            url: '/dashboard/new/users/count',
            type: 'GET',
            success: function (response) {
                $('#usersWithoutRolesCount').text(response.count);
            },
            error: function (xhr) {
                console.error('Error fetching users without roles count:', xhr);
            }
        });
    }

    // Fetch the count initially
    fetchUsersWithoutRolesCount();

    // Set interval to fetch the count every 30 seconds
    setInterval(fetchUsersWithoutRolesCount, 30000);


    function toggleSidebar() {
        var sidebarVisible = $('#sideBarMenue').hasClass('visible');

        if ($(window).width() <= 768) { // Tablet screen size or smaller
            if (sidebarVisible) {
                $('#sideBarMenue').css('left', '-280px').removeClass('visible');
            } else {
                $('#sideBarMenue').css('left', '0px').addClass('visible');
            }
        } else { // Greater than tablet size
            if (sidebarVisible) {
                $('#sideBarMenue').css('margin-left', '-280px').removeClass('visible');
            } else {
                $('#sideBarMenue').css('margin-left', '0px').addClass('visible');
            }
        }
    }

    $('#hamburgerMenu').click(function (e) {
        e.preventDefault();
        toggleSidebar();
    });

    function checkScreenSize() {
        if ($(window).width() <= 768) { // Tablet screen size or smaller
            $('#sideBarMenue').css({
                'position': 'absolute',
                'z-index': 999,
                'top': '60px',
                'left': '-280px', // Initially hidden
                'transition': 'left 0.3s ease',
                'margin-left': 'unset'
            });
            if ($('#sideBarMenue').hasClass('visible')) {
                $('#sideBarMenue').removeClass('visible');
            }
        } else { // Greater than tablet size
            $('#sideBarMenue').css({
                'position': 'relative',
                'z-index': 'unset',
                'top': 'unset',
                'left': 'unset',
                'transition': 'margin-left 0.3s ease',
                'margin-left': '0px' // Initially hidden
            });
            $('#sideBarMenue').addClass('visible');
        }
    }

    // Check screen size on load
    checkScreenSize();

    // Check screen size on resize
    $(window).resize(function () {
        checkScreenSize();
    });

    // logout Function 
    $('#logoutButton').click(function () {
        $('#loaderBox').css("display", "flex");
        var logoutUrl = $(this).data('logout-url');
        var csrfToken = $(this).data('csrf-token');

        $.ajax({
            url: logoutUrl,
            type: 'POST',
            data: {
                _token: csrfToken
            },
            success: function (response) {
                $('#loaderBox').css("display", "none");
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                    }).then(function () {
                        window.location.href = response.redirect_url;
                    });
                } else {
                    alert('Logout failed, please try again.');
                }
            },
            error: function (xhr) {
                alert('An error occurred while logging out.');
            }
        });
    });

    $('.dropdown-toggle').on('click', function () {
        var $this = $(this);
        var $submenu = $this.next('.collapse');
        var $icon = $this.find('.fa-chevron-right, .fa-chevron-down');

        if ($submenu.hasClass('show')) {
            $submenu.collapse('hide');
            $icon.removeClass('fa-chevron-down').addClass('fa-chevron-right');
        } else {
            $submenu.collapse('show');
            $icon.removeClass('fa-chevron-right').addClass('fa-chevron-down');
        }
    });

    $('.collapse').on('hide.bs.collapse', function () {
        $(this).prev('.dropdown-toggle').find('.fa-chevron-down').removeClass('fa-chevron-down').addClass('fa-chevron-right');
    });

    $('.collapse').on('show.bs.collapse', function () {
        $(this).prev('.dropdown-toggle').find('.fa-chevron-right').removeClass('fa-chevron-right').addClass('fa-chevron-down');
    });


   
    // Handle role selection change event
    $('#roleSelect').change(function () {
        let roleId = $(this).val();
        if (roleId) {
            $.ajax({
                url: '/dashboard/role/' + roleId + '/permissions',
                method: 'GET',
                success: function (assignedPermissions) {
                    $('input[name="permissionId[]"]').each(function () {
                        $(this).prop('checked', assignedPermissions.includes(parseInt($(this).val())));
                    });
                },
                error: function (error) {
                    console.log(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load permissions for the selected role.'
                    });
                }
            });
        }
    });

   

});


// Load permissions on page load
function loadPermissions() {
    $.ajax({
        url: '/dashboard/permissions',
        method: 'GET',
        success: function (data) {
            let tableBody = $('#permissionsTableForRoleSelect');
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
                        row += '<input class="form-check-input permission-checkbox" type="checkbox" id="permission' + permission.id + '" value="' + permission.id + '" name="permissionId[]" data-group="' + displayGroup + '" data-permission="' + shortName.toLowerCase() + '" disabled>';
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
                    row += '<input class="form-check-input permission-checkbox" type="checkbox" id="permission' + permission.id + '" value="' + permission.id + '" name="permissionId[]" data-group="New User" data-permission="' + shortName.toLowerCase() + '" disabled>';
                    row += '<label class="form-check-label" for="permission' + permission.id + '">' + shortName + '</label>';
                    row += '</div>';
                });
                if (assignRolePermission) {
                    let shortName = getShortPermissionName(assignRolePermission.name);
                    row += '<div class="form-check form-check-inline">';
                    row += '<input class="form-check-input permission-checkbox" type="checkbox" id="permission' + assignRolePermission.id + '" value="' + assignRolePermission.id + '" name="permissionId[]" data-group="New User" data-permission="assign role" disabled>';
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
                    row += '<input class="form-check-input permission-checkbox" type="checkbox" id="permission' + permission.id + '" value="' + permission.id + '" name="permissionId[]" data-group="Role Management" data-permission="' + shortName.toLowerCase() + '" disabled>';
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
}

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


