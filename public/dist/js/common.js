$(window).on('load', function () {
    $('#loaderBox').css("display", "none");

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
            error: function(xhr) {
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

});