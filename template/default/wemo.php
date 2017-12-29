<?php
ini_set('default_socket_timeout', 2);

require_once '../../data/config.php';
require_once '../../fonc/common.php';
require_once '../../fonc/wemo.php';
require_once '../../vendor/autoload.php';
?>
<table class="table table-hover table-bordered">
    <tr><th>Name</th><th>Status</th></tr>
    <?php
    foreach ($wemoSwitch as $lightId => $lightName) {

        $ping = ping("$lightId");
        if ($ping) {
            $checked = '';
            $status = '';

            $device = \a15lam\PhpWemo\Discovery::lookupDevice('id', $lightId);
            $client = new \a15lam\PhpWemo\WemoClient($device['ip'], $device['port']);
            $deviceClass = $device['class_name'];
            $myDevice = new $deviceClass($device['id'], $client);
            $status = $myDevice->state();
            
            if ($status == 1) {
                $checked = 'checked ';
            }
            echo '<tr><td>' . $lightName . '</td><td><input ' . $checked . 'id="' . $lightId . '" class="btn-toggle" data-toggle="toggle" type="checkbox" autocomplete="off"></td></tr>';
            echo "<script>
                    $(function () {
                        $('#$lightId').bootstrapToggle();
                        $('#$lightId').change(function () {
                            checkedStatus = $(this).prop('checked');
                            id = $(this).prop('id');

                            $.ajax({
                                cache: false,
                                url: 'api/wemo.php',
                                type: 'GET',
                                data: 'id=' + id + '&action=' + checkedStatus,
                                dataType: 'html',
                                success: function (code_html, statut) {
                //                    $('#alert').removeClass(\"hidden\");
                //                    $('#alert').html(code_html);
                                },

                                error: function (resultat, statut, erreur) {
                                    $('#alert').removeClass(\"hidden\");
                                    $('#alert').html('Error :  ' + erreur);
                                },

                                complete: function (resultat, statut) {
                //                    $('#lightPanelBody').load('light.php');
                                }
                            });
                        });
                    })
                </script>";
        } else {
            echo '<tr><td>' . $lightName . '</td><td>-</td></tr>';
        }
    }
    ?>
</table>