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
            $('#' + spanId + '').html(total + ' H/s');
        }
    });
}
;