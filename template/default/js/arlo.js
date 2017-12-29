$(function () {
    $('#secuBtn').bootstrapToggle();
    $('#secuBtn').change(function () {
        checkedStatus = $(this).prop('checked');

        $.ajax({
            cache: false,
            url: 'api/arlo.php',
            type: 'GET',
            data: 'action=' + checkedStatus,
            dataType: 'html',
            success: function (code_html, statut) {
                $('#alert').removeClass("hidden");
                $('#alert').html(code_html);
            },

            error: function (resultat, statut, erreur) {
                $('#alert').removeClass("hidden");
                $('#alert').html('Error :  ' + erreur);
            },

            complete: function (resultat, statut) {
                $("#secuPanelBody").load('secu.php');
            }
        });
    });
})