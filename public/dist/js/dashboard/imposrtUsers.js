$(document).ready(function () {
    var table = new DataTable('#dataTable', {
        responsive: true
    });

    // Set up AJAX to include CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

});