<?php
ini_set('default_socket_timeout', 2);

require_once __DIR__ . '/../../data/config.php';
require_once __DIR__ . '/../../fonc/common.php';
require_once __DIR__ . '/../../fonc/wemo.php';
require_once __DIR__ . '/../../vendor/autoload.php';
?>
<table class="table table-hover table-bordered">
    <tr><th>Name</th><th>Status</th></tr>
    <?php
    $asToggle = false;
    foreach ($wemoSwitch as $lightId => $lightName) {

        $ping = ping("$lightId");
        if ($ping) {
            $asToggle = true;
            $checked = '';
            $status = get_wemo_status($lightId);

            if ($status == 1) {
                $checked = 'checked ';
            }
            echo '<tr><td>' . $lightName . '</td><td><input ' . $checked . 'id="' . $lightId . '" class="btn-toggle wemo-switch" data-toggle="toggle" type="checkbox" autocomplete="off"></td></tr>';
        } else {
            echo "<tr><td>$lightName</td><td>Wemo switch $lightId don't ping.</td></tr>";
        }
    }
    ?>
</table>
<?php
if ($asToggle) {
    ?>
    <script>
        $(function () {
            $('.wemo-switch').bootstrapToggle();
            $('.wemo-switch').change(function () {
                checkedStatus = $(this).prop('checked');
                id = $(this).prop('id');
                $.ajax({
                    cache: false,
                    url: 'api/wemo.php',
                    type: 'GET',
                    data: 'id=' + id + '&action=' + checkedStatus,
                    dataType: 'json',
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