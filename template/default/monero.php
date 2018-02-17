<?php
ini_set('default_socket_timeout', 2);

require_once __DIR__ . '/../../data/config.php';
require_once __DIR__ . '/../../fonc/common.php';
require_once __DIR__ . '/../../fonc/monero.php';
require_once __DIR__ . '/../../fonc/wemo.php';
require_once __DIR__ . '/../../vendor/autoload.php';

$poolData = get_nanopool($moneroPoolBalanceApi);
?>

<div class="row">
    <div class="col-md-6">
        <h4>Pending : <?= $poolData->data ?> <?= strtoupper($moneroCurrencyShort) ?></h4>
        <h4><a href="https://<?= $moneroCurrencyShort ?>.nanopool.org/account/<?= $moneroWallet ?>" target="_blank">Pool</a></h4>
        <h4><a href="https://coinmarketcap.com/currencies/<?= $moneroCurrencyLong ?>/" target="_blank">Market</a></h4>
    </div>
    <div class="col-md-6">
        <?php
        $coinmarketcap = get_coinmarketcap($moneroCurrencyLong);
        $unit_price = $coinmarketcap[0]->price_eur;
        $total_value = $moneroWalletAmount * $unit_price;
        ?>
        <h4>Currency price : <?= round($unit_price, 2) ?> €</h4>
        <h4>Wallet : <?= $moneroWalletAmount ?> <?= strtoupper($moneroCurrencyShort) ?></h4>
        <h4>Wallet value : <?= round($total_value, 2) ?> €</h4>
    </div>
</div>

<script src="template/<?= $template ?>/js/monero.js"></script>
<script src="template/<?= $template ?>/js/wemo.js"></script>

<p class="clearfix"></p>
<table class="table table-hover table-bordered table-responsive">
    <tr><th>Status</th><th>Worker</th><th>Hasrate</th><th>Uptime</th><th>Temperature</th><th>Power</th><th>Power Cost</th><th>Power Status</th></tr>
    <?php
    $power_refresh = "";
    $hash_refresh = "";
    $asToggle = false;

    // Get nanopool workers
    $data = get_nanopool($moneroPoolWorkerApi);
    // Extract machines list with data
    $machines = $data->data;

    foreach ($machines as $machine) {
        // Use worker id as hostname
        $hostname = $machine->id;
        // Create machine link with hostname and xmr-stak port in config.php
        $link = 'http://' . $hostname . ':' . $moneroMachines[$hostname];

        $hashrate = '-';
        $uptime = '-';
        $temperature = '-';
        $power = '-';

        // Get last share send to nanopool
        $lastShare = $machine->lastShare;

        if ((time() - $lastShare) < 3600) {
            $connection = false;
            $ping = ping($hostname);
            if ($ping) {
                $connection = @fsockopen($hostname, '22');
            }

            if ($connection != false) {
                $uptime = get_uptime($hostname, $sshUser, $sshKeyPub, $sshKeyPriv);
                $temperature = get_temp($hostname, $sshUser, $sshKeyPub, $sshKeyPriv);
                $hash_refresh .= "getHash('$link', '$hostname-hash');";
                $alivedMachines[] = $link;
                fclose($connection);
            } else {
                $uptime = '-';
            }
            echo '<tr><td class="text-center"><i class="fa fa-power-off green"></i></td>';
        } else {
            echo '<tr><td class="text-center"><i class="fa fa-power-off red"></i></td>';
        }

        echo '<td><a href="' . $link . '" target="_blank">' . $hostname . '</a></td>';
        echo '<td><span class="big-numbers" id="' . $hostname . '-hash">' . $hashrate . '</span> ' . $moneroSpeedUnit . '</td>';
        echo '<td>' . $uptime . '</td>';
        echo '<td>' . $temperature . '</td>';

        $wemoId = $moneroMachinesInsight[$hostname];
        $ping = ping($wemoId);
        if ($ping) {
            $asToggle = true;
            $checked = '';
            $powerAmp = '';
            $alivedInsight[] = $wemoId;
            $status = get_wemo_status($wemoId);

            $power = get_wemo_power($wemoId);

            $kwh = round(24 * 365 * ($power / 1000));
            $yearCost = round($kwh * $powerCost);
            $monthCost = round($yearCost / 12);

            if (isset($moneroMachinesAmp[$hostname])) {
                $powerAmp = '<br />' . $moneroMachinesAmp[$hostname] . ' A';
            }

            $power_refresh .= "getPower('wemo-$hostname', '$hostname-power');";

            echo '<td><span class="big-numbers" id="' . $hostname . '-power">' . $power . '</span> W<br /><small>' . $kwh . ' kWh / year' . $powerAmp . '</small></td>';
            echo '<td>' . $monthCost . ' € / month<br />' . $yearCost . ' € / year</td>';

            if ($status == 1) {
                $checked = 'checked ';
            }
            echo '<td><input ' . $checked . 'id="wemo-' . $hostname . '" class="btn-toggle wemo-insight" data-toggle="toggle" type="checkbox" autocomplete="off"></td>';
        } else {
            echo '<td>' . $power . '</td>';
            echo '<td>-</td>';
            echo '<td>-</td>';
        }

        echo '</tr>';
    }
    ?>
    <tr>
        <th>Total</th>
        <th>&nbsp;</th>
        <th>
            <span class="big-numbers" id="total-hash">-</span> <?= $moneroSpeedUnit ?>
        </th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>
            <span class="big-numbers" id="total-power">-</span> W
        </th>
        <th>
            <span id="total-power-cost">-</span>
        </th>
        <th>&nbsp;</th>
    </tr>
</table>
<?php
if ($asToggle) {
    ?>
    <script>
        $(function () {
            $('.wemo-insight').bootstrapToggle();
            $('.wemo-insight').change(function () {
                checkedStatus = $(this).prop('checked');
                id = $(this).prop('id');

                $.ajax({
                    cache: false,
                    url: 'api/wemo.php',
                    type: 'GET',
                    data: 'id=' + id + '&action=' + checkedStatus,
                    dataType: 'html',

                    error: function (result, status, error) {
                        $('#alert').removeClass("hidden");
                        $('#alert').removeClass("alert-info");
                        $('#alert').addClass("alert-danger");
                        $('#alert').html('Error :  ' + error);
                    },
                });
            });
        })
    </script>
    <?php
}
?>
<?php
echo '<script>
        setInterval(function () {
            ' . ($power_refresh != '' ? $power_refresh : "") . '
            ' . ($hash_refresh != '' ? $hash_refresh : "") . '
            ' . (count($alivedMachines) != 0 ? 'getTotalHash(' . json_encode($alivedMachines) . ', "total-hash")' : "") . '
            ' . (count($alivedInsight) != 0 ? 'getTotalPower(' . json_encode($alivedInsight) . ', "total-power")' : "") . '
        }, ' . $autoRefresh . ');
</script>';
?>