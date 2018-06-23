function getHash(url, spanId) {
    $.ajax({
        cache: false,
        url: '/application/get-hashrate',
        type: 'POST',
        dataType: 'JSON',
        data: {
            'url': url + '/api.json',
        },
        success: function (data) {
            var jsonObj = jQuery.parseJSON(data);
            var total = jsonObj.hashrate.total[0];
            $('#' + spanId + '').html(total.toFixed(0));
        }
    })
}
;

function getUptime(id, spanId) {
    $.ajax({
        cache: false,
        url: '/application/get-uptime',
        type: 'POST',
        dataType: 'JSON',
        data: {
            'id': id,
        },
        success: function (data) {
            $('#' + spanId + '').html(data.uptime);
        }
    });
}
;

function getTemp(id, spanId) {
    $.ajax({
        cache: false,
        url: '/application/get-temp',
        type: 'POST',
        dataType: 'JSON',
        data: {
            'id': id,
        },
        success: function (data) {
            $('#' + spanId + '').html(data.temp);
        }
    });
}
;

function getPower(id, spanId) {
    $.ajax({
        cache: false,
        url: '/application/get-power',
        type: 'POST',
        dataType: 'JSON',
        data: {
            'id': id,
        },
        success: function (data) {
            $('#' + spanId + '').html(data.power);
        }
    });
}
;

function getSumHash(spanId) {
    var totalHash = 0;
    $("span[id*='-hash']").each(function () {
        totalHash = totalHash + parseInt($(this).text());
    });
    $('#' + spanId + '').html(totalHash);
}
;

function getSumPower(spanId) {
    var totalPower = 0;
    $("span[id*='-power']").each(function () {
        totalPower = totalPower + parseInt($(this).text());
    });
    $('#' + spanId + '').html(totalPower);
}
;

function powerToggle(input) {
    var state = input.prop('checked');
    var id = input.prop('id');
    var name = input.data("name");
    var entity = input.data("entity");
    if (state === false) {
        if (!confirm('Power OFF ' + name + '?')) {
            $(this).bootstrapToggle('on');
            e.preventDefault();
        }
    }
    $.ajax({
        cache: false,
        url: '/application/toggle-power-state',
        type: 'POST',
        dataType: 'JSON',
        data: {
            'id': id,
            'state': state,
            'entity': entity
        },
    });
}
;