<?php
require_once '../../data/config.php';
require_once '../../fonc/common.php';
require_once '../../fonc/arlo.php';

$status = json_decode(get_arlo_status($arloUsername, $arloPassword));

$checked = '';
if ($status == "ON") {
	$checked = 'checked ';
}
?>

<input <?= $checked ?>id="secuBtn" data-toggle="toggle" type="checkbox" autocomplete="off">

<script src="template/<?= $template ?>/js/arlo.js"></script>