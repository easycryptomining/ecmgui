<?php
header('Content-type: application/json');
require_once '../vendor/autoload.php';

$id = $_GET['id'];
$action = $_GET['action'];

$device = \a15lam\PhpWemo\Discovery::lookupDevice('id', $id);
$client = new \a15lam\PhpWemo\WemoClient($device['ip'], $device['port']);
$deviceClass = $device['class_name'];
$myDevice = new $deviceClass($device['id'], $client);

if ($action == 'true') {
    $myDevice->On();
} elseif ($action == 'false') {
    $myDevice->Off();
} elseif ($action == 'getPower') {
    $params = $myDevice->getParams();
    $parts = explode('|', $params);
    $power = round($parts[7] / 1000);
    echo json_encode($power);
}
?>
