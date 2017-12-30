<?php

header('Content-type: application/json');

require_once __DIR__ . '/../data/config.php';
require_once __DIR__ . '/../fonc/arlo.php';

$action = $_GET['action'];

if ($action == 'true') {
    echo arm_arlo($arloUsername, $arloPassword);
} elseif ($action == 'false') {
    echo disarm_arlo($arloUsername, $arloPassword);
} elseif ($action == 'getstatus') {
    echo get_arlo_status($arloUsername, $arloPassword);
}
?>
