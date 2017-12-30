<?php

header('Content-type: application/json');

require_once __DIR__ . '/../fonc/wemo.php';

$wemoId = $_GET['id'];
$action = $_GET['action'];

if ($action == 'true') {
    echo json_encode(on_wemo($wemoId));
} elseif ($action == 'false') {
    echo json_encode(off_wemo($wemoId));
} elseif ($action == 'getPower') {
    echo json_encode(get_wemo_power($wemoId));
}
?>
