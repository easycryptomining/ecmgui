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

function getTotalPower(id, spanId) {
    var total = 0;
    $.each(id, function (key, value) {
        $.ajax({
            cache: false,
            url: 'api/wemo.php',
            type: 'GET',
            data: 'id=' + value + '&action=getPower',
            dataType: 'json',
            async: false,
            success: function (data) {
                total += data;
            }
        });
    });
    $('#' + spanId + '').html(total.toFixed(0));
}
;