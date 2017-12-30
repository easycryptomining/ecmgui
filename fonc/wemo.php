<?php

require_once __DIR__ . '/../vendor/autoload.php';

function on_wemo($wemoId) {
    $device = \a15lam\PhpWemo\Discovery::lookupDevice('id', $wemoId);
    $client = new \a15lam\PhpWemo\WemoClient($device['ip'], $device['port']);
    $deviceClass = $device['class_name'];
    $myDevice = new $deviceClass($device['id'], $client);
    $myDevice->On();
    return "ON";
}

function off_wemo($wemoId) {
    $device = \a15lam\PhpWemo\Discovery::lookupDevice('id', $wemoId);
    $client = new \a15lam\PhpWemo\WemoClient($device['ip'], $device['port']);
    $deviceClass = $device['class_name'];
    $myDevice = new $deviceClass($device['id'], $client);
    $myDevice->Off();
    return "OFF";
}

function get_wemo_power($wemoId) {
    $device = \a15lam\PhpWemo\Discovery::lookupDevice('id', $wemoId);
    $client = new \a15lam\PhpWemo\WemoClient($device['ip'], $device['port']);
    $deviceClass = $device['class_name'];
    $myDevice = new $deviceClass($device['id'], $client);
    $params = $myDevice->getParams();
    $parts = explode('|', $params);
    $power = round($parts[7] / 1000);
    return $power;
}

function get_wemo_status($wemoId) {
    $device = \a15lam\PhpWemo\Discovery::lookupDevice('id', $wemoId);
    $client = new \a15lam\PhpWemo\WemoClient($device['ip'], $device['port']);
    $deviceClass = $device['class_name'];
    $myDevice = new $deviceClass($device['id'], $client);
    $status = $myDevice->state();
    return $status;
}
