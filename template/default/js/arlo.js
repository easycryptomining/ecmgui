$(function () {
    $('#arloBtn').bootstrapToggle();
    $('#arloBtn').change(function () {
        checkedStatus = $(this).prop('checked');
        $.ajax({
            cache: false,
            url: 'api/arlo.php',
            type: 'GET',
            data: 'action=' + checkedStatus,
            dataType: 'json',
            success: function (result) {
                $('#alert').removeClass("hidden");
                $('#alert').removeClass("alert-danger");
                $('#alert').addClass("alert-info");
                $('#alert').html(result);
            },
            error: function (result, status, error) {
                $('#alert').removeClass("hidden");
                $('#alert').removeClass("alert-info");
                $('#alert').addClass("alert-danger");
                $('#alert').html('Error :  ' + error);
            },
        });
    });
})