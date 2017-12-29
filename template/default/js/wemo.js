function getPower(id, spanId) {
    $.ajax({
        cache: false,
        url: 'api/wemo.php',
        type: 'GET',
        data: 'id=' + id + '&action=getPower',
        dataType: 'json',
        success: function (data) {
            $('#' + spanId + '').html(data);
        }
    });
}
;