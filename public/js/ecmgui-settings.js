jQuery(function ($) {

    // Populates wallets table with data
    var walletsDataTable = $('#walletsTable').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [0, 'ASC'],
        "ajax": {
            type: "POST",
            url: "/settings/get-all-wallets",
            dataType: 'JSON',
            error: function (error) {
                console.log(error);
            },
        },
        "columnDefs": [
            {
                "targets": [4, 5],
                "orderable": false,
            },
        ],
    });

    // Clears form's data and changes text
    $('#addWallet').on('click', function (e) {
        e.preventDefault();
        $('#addUpdWalletForm')[0].reset();
        $('#statusWalletsMsg').html("");
        $('#titleWalletModal').text('Add new wallet');
        $('#addUpdWalletBtn').val('Add');
        $(document.body).append('<script>var walletIdToEdit=null;</script>');
    });

    // Loads wallet data
    $('#walletsTable').on('click', '#editWallet', function (e) {
        e.preventDefault();

        $('#statusWalletsMsg').html("");
        var fetchId = $(this).data('walletid');
        $(document.body).append('<script>var walletIdToEdit=' + fetchId + ';</script>');
        var walletId = walletIdToEdit;

        $.ajax({
            type: 'POST',
            url: '/settings/get-wallet',
            dataType: 'JSON',
            cache: false,
            data: {
                'walletId': walletId,
            },
            success: function (data) {
                $('#addUpdWalletModal #type').val(data.wallet.type);
                $('#addUpdWalletModal #number').val(data.wallet.number);
                $('#addUpdWalletModal #balance').val(data.wallet.balance);
                $('#titleWalletModal').text('Update wallet');
                $('#addUpdWalletBtn').val('Update');
//                console.log("Loaded");
            },
            // https://stackoverflow.com/a/13698395
            error: function (xhr, textStatus, errorThrown) {
                if (xhr.status === 0) {
                    alert('Not connect.\n Verify Network.');
                } else if (xhr.status == 404) {
                    alert('Requested page not found. [404]');
                } else if (xhr.status == 500) {
                    alert('Server Error [500].');
                } else if (errorThrown === 'parsererror') {
                    alert('Requested JSON parse failed.');
                } else if (errorThrown === 'timeout') {
                    alert('Time out error.');
                } else if (errorThrown === 'abort') {
                    alert('Ajax request aborted.');
                } else {
                    alert('There was some error. Try again.');
                }
            },
        });

        // Opens modal
        $('#addUpdWalletModal').modal('show');
    });

    // Adds and updates wallet
    $('#addUpdWalletForm').on('submit', function (e) {
        e.preventDefault();

        $('#statusWalletsMsg').html('');

        var type, number, balance, walletId, url;

        type = $('#type').val();
        number = $('#number').val();
        balance = $('#balance').val();
        walletId = walletIdToEdit;

        if (walletId == null) {
            url = '/settings/add-wallet'
        } else {
            url = '/settings/upd-wallet'
        }

        $.ajax({
            type: 'POST',
            url: url,
            dataType: 'JSON',
            cache: false,
            data: {
                'type': type,
                'number': number,
                'balance': balance,
                'walletId': walletId,
            },
            success: function (data) {
                if (data.error) {
                    for (i = 0; i < data.error.length; i++) {
                        $('#statusWalletsMsg').append('<div class="alert alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' + data.error[i] + '</div>');
                    }
                } else {
                    $('#addUpdWalletForm')[0].reset();
//                    console.log(data.success);
                    $('#addUpdWalletModal').modal('hide');
                    walletsDataTable.ajax.reload();
                }
            },
            // https://stackoverflow.com/a/13698395
            error: function (xhr, textStatus, errorThrown) {
                if (xhr.status === 0) {
                    alert('Not connect.\n Verify Network.');
                } else if (xhr.status == 404) {
                    alert('Requested page not found. [404]');
                } else if (xhr.status == 500) {
                    alert('Server Error [500].');
                } else if (errorThrown === 'parsererror') {
                    alert('Requested JSON parse failed.');
                } else if (errorThrown === 'timeout') {
                    alert('Time out error.');
                } else if (errorThrown === 'abort') {
                    alert('Ajax request aborted.');
                } else {
                    alert('There was some error. Try again.');
                }
            },
        });
    });

    $('#deleteWalletModal').on('show.bs.modal', function (e) {
        var button = $(e.relatedTarget);
        var walletId = button.data('walletid');

        $(document.body).append('<script>var walletIdToDelete=' + walletId + ';</script>');
        $('#walletIdSpan').text(walletIdToDelete);
    });

    $('#deleteWalletBtn').on('click', function (e) {
        e.preventDefault();

        var walletId = walletIdToDelete;

        $.ajax({
            type: 'POST',
            url: '/settings/del-wallet',
            dataType: 'JSON',
            cache: false,
            data: {
                'walletId': walletId,
            },
            success: function (data) {
//                console.log(data.success);
                $('#deleteWalletModal').modal('hide');
                walletsDataTable.ajax.reload();
            },
            // https://stackoverflow.com/a/13698395
            error: function (xhr, textStatus, errorThrown) {
                if (xhr.status === 0) {
                    alert('Not connect.\n Verify Network.');
                } else if (xhr.status == 404) {
                    alert('Requested page not found. [404]');
                } else if (xhr.status == 500) {
                    alert('Server Error [500].');
                } else if (errorThrown === 'parsererror') {
                    alert('Requested JSON parse failed.');
                } else if (errorThrown === 'timeout') {
                    alert('Time out error.');
                } else if (errorThrown === 'abort') {
                    alert('Ajax request aborted.');
                } else {
                    alert('There was some error. Try again.');
                }
            },
        });
    });
    
    // -------------------------------------------------------------------------
    
    // Populates workers table with data
    var workersDataTable = $('#workersTable').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [0, 'ASC'],
        "ajax": {
            type: "POST",
            url: "/settings/get-all-workers",
            dataType: 'JSON',
            error: function (error) {
                console.log(error);
            },
        },
        "columnDefs": [
            {
                "targets": [4, 5],
                "orderable": false,
            },
        ],
    });

    // Clears form's data and changes text
    $('#addWorker').on('click', function (e) {
        e.preventDefault();
        $('#addUpdWorkerForm')[0].reset();
        $('#statusWorkersMsg').html("");
        $('#titleWorkerModal').text('Add new worker');
        $('#addUpdWorkerBtn').val('Add');
        $(document.body).append('<script>var workerIdToEdit=null;</script>');
    });

    // Loads worker data
    $('#workersTable').on('click', '#editWorker', function (e) {
        e.preventDefault();

        $('#statusWorkersMsg').html("");
        var fetchId = $(this).data('workerid');
        $(document.body).append('<script>var workerIdToEdit=' + fetchId + ';</script>');
        var workerId = workerIdToEdit;

        $.ajax({
            type: 'POST',
            url: '/settings/get-worker',
            dataType: 'JSON',
            cache: false,
            data: {
                'workerId': workerId,
            },
            success: function (data) {
                $('#addUpdWorkerModal #name').val(data.worker.name);
                $('#addUpdWorkerModal #ip').val(data.worker.ip);
                $('#addUpdWorkerModal #pool').val(data.worker.pool);
                $('#addUpdWorkerModal #insight').val(data.worker.insight);
                $('#addUpdWorkerModal #software').val(data.worker.software);
                $('#addUpdWorkerModal #softwareport').val(data.worker.softwareport);
                $('#addUpdWorkerModal #amp').val(data.worker.amp);
                $('#addUpdWorkerModal #walletid').val(data.worker.walletid);
                $('#addUpdWorkerModal #sshuser').val(data.worker.sshuser);
                $('#addUpdWorkerModal #sshpassword').val(data.worker.sshpassword);
                $('#addUpdWorkerModal #sshport').val(data.worker.sshport);
                $('#titleWorkerModal').text('Update worker');
                $('#addUpdWorkerBtn').val('Update');
//                console.log("Loaded");
            },
            // https://stackoverflow.com/a/13698395
            error: function (xhr, textStatus, errorThrown) {
                if (xhr.status === 0) {
                    alert('Not connect.\n Verify Network.');
                } else if (xhr.status == 404) {
                    alert('Requested page not found. [404]');
                } else if (xhr.status == 500) {
                    alert('Server Error [500].');
                } else if (errorThrown === 'parsererror') {
                    alert('Requested JSON parse failed.');
                } else if (errorThrown === 'timeout') {
                    alert('Time out error.');
                } else if (errorThrown === 'abort') {
                    alert('Ajax request aborted.');
                } else {
                    alert('There was some error. Try again.');
                }
            },
        });

        // Opens modal
        $('#addUpdWorkerModal').modal('show');
    });

    // Adds and updates worker
    $('#addUpdWorkerForm').on('submit', function (e) {
        e.preventDefault();

        $('#statusWorkersMsg').html('');

        var name, ip, pool, insight, software, softwareport, amp, walletid, workerId, sshuser, sshpassword, sshport, url;

        name = $('#name').val();
        ip = $('#ip').val();
        pool = $('#pool').val();
        insight = $('#insight').val();
        software = $('#software').val();
        softwareport = $('#softwareport').val();
        amp = $('#amp').val();
        walletid = $('#walletid').val();
        workerId = workerIdToEdit;
        sshuser = $('#sshuser').val();
        sshpassword = $('#sshpassword').val();
        sshport = $('#sshport').val();

        if (workerId == null) {
            url = '/settings/add-worker'
        } else {
            url = '/settings/upd-worker'
        }

        $.ajax({
            type: 'POST',
            url: url,
            dataType: 'JSON',
            cache: false,
            data: {
                'name': name,
                'ip': ip,
                'pool': pool,
                'insight': insight,
                'software': software,
                'softwareport': softwareport,
                'amp': amp,
                'walletid': walletid,
                'workerId': workerId,
                'sshuser': sshuser,
                'sshpassword': sshpassword,
                'sshport': sshport,
            },
            success: function (data) {
                if (data.error) {
                    for (i = 0; i < data.error.length; i++) {
                        $('#statusWorkersMsg').append('<div class="alert alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' + data.error[i] + '</div>');
                    }
                } else {
                    $('#addUpdWorkerForm')[0].reset();
//                    console.log(data.success);
                    $('#addUpdWorkerModal').modal('hide');
                    workersDataTable.ajax.reload();
                }
            },
            // https://stackoverflow.com/a/13698395
            error: function (xhr, textStatus, errorThrown) {
                if (xhr.status === 0) {
                    alert('Not connect.\n Verify Network.');
                } else if (xhr.status == 404) {
                    alert('Requested page not found. [404]');
                } else if (xhr.status == 500) {
                    alert('Server Error [500].');
                } else if (errorThrown === 'parsererror') {
                    alert('Requested JSON parse failed.');
                } else if (errorThrown === 'timeout') {
                    alert('Time out error.');
                } else if (errorThrown === 'abort') {
                    alert('Ajax request aborted.');
                } else {
                    alert('There was some error. Try again.');
                }
            },
        });
    });

    $('#deleteWorkerModal').on('show.bs.modal', function (e) {
        var button = $(e.relatedTarget);
        var workerId = button.data('workerid');

        $(document.body).append('<script>var workerIdToDelete=' + workerId + ';</script>');
        $('#workerIdSpan').text(workerIdToDelete);
    });

    $('#deleteWorkerBtn').on('click', function (e) {
        e.preventDefault();

        var workerId = workerIdToDelete;

        $.ajax({
            type: 'POST',
            url: '/settings/del-worker',
            dataType: 'JSON',
            cache: false,
            data: {
                'workerId': workerId,
            },
            success: function (data) {
//                console.log(data.success);
                $('#deleteWorkerModal').modal('hide');
                workersDataTable.ajax.reload();
            },
            // https://stackoverflow.com/a/13698395
            error: function (xhr, textStatus, errorThrown) {
                if (xhr.status === 0) {
                    alert('Not connect.\n Verify Network.');
                } else if (xhr.status == 404) {
                    alert('Requested page not found. [404]');
                } else if (xhr.status == 500) {
                    alert('Server Error [500].');
                } else if (errorThrown === 'parsererror') {
                    alert('Requested JSON parse failed.');
                } else if (errorThrown === 'timeout') {
                    alert('Time out error.');
                } else if (errorThrown === 'abort') {
                    alert('Ajax request aborted.');
                } else {
                    alert('There was some error. Try again.');
                }
            },
        });
    });
});