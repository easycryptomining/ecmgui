<?php
require_once __DIR__ . '/../../data/config.php';
require_once __DIR__ . '/../../fonc/common.php';
require_once __DIR__ . '/../../fonc/arlo.php';

$status = json_decode(get_arlo_status($arloUsername, $arloPassword));

$checked = '';
if ($status == "ON") {
    $checked = 'checked ';
}
?>

<input <?= $checked ?>id="arloBtn" data-toggle="toggle" type="checkbox" autocomplete="off">

<script src="template/<?= $template ?>/js/arlo.js"></script>