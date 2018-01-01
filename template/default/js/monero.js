function getHash(url, spanId) {
    $.ajax({
        cache: false,
        url: 'proxy.php',
        type: 'GET',
        data: 'url=' + url + '/api.json',
        dataType: 'json',
        success: function (data) {
            var jsonObj = jQuery.parseJSON(data);
            var total = jsonObj.hashrate.total[0];
            $('#' + spanId + '').html(total.toFixed(0));
        }
    });
}
;

function getTotalHash(url, spanId) {
    var total = 0;
    $.each(url, function (key, value) {
        $.ajax({
            cache: false,
            url: 'proxy.php',
            type: 'GET',
            data: 'url=' + value + '/api.json',
            dataType: 'json',
            async: false,
            success: function (data) {
                var jsonObj = jQuery.parseJSON(data);
                total += jsonObj.hashrate.total[0];
            }
        });
    });
    $('#' + spanId + '').html(total.toFixed(0));
}
;