<?php

function ping($host) {
    $pingresult = exec("ping -c 1 -s 64 $host", $outcome, $status);
    if (0 == $status) {
        $result = true;
    } else {
        $result = false;
    }

    return $result;
}