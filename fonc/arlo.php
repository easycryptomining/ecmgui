<?php

function get_arlo_status($arloUsername, $arloPassword) {
    $command = "python ".__DIR__."/arlo/arlo_get_status.py $arloUsername $arloPassword";
    $output = `$command`;
    $output = str_replace("u'", '"', $output);
    $output = str_replace("'", '"', $output);
    $output = json_decode($output, true);

    $status = $output['properties']['active'];

    if ($status == "mode1") {
        return json_encode("ON");
    } elseif ($status == "mode0") {
        return json_encode("OFF");
    }
}

function arm_arlo($arloUsername, $arloPassword) {
    $command = "python ".__DIR__."/arlo/arlo_arm.py $arloUsername $arloPassword";
    $output = `$command`;
    return "Armed";
}

function disarm_arlo($arloUsername, $arloPassword) {
    $command = "python ".__DIR__."/arlo/arlo_disarm.py $arloUsername $arloPassword";
    $output = `$command`;
    return "Disarmed";
}