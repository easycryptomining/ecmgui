<?php

function get_nanopool($nanopoolUrl) {
    $content = file_get_contents($nanopoolUrl);
    return json_decode($content);
}

function get_coinmarketcap($currency) {
    $content = file_get_contents("https://api.coinmarketcap.com/v1/ticker/$currency/?convert=EUR");
    return json_decode($content);
}

function get_uptime($machine, $sshUser, $sshKeyPub, $sshKeyPriv) {
    $ssh = ssh2_connect($machine, 22, array('hostkey' => 'ssh-rsa'));
    if (ssh2_auth_pubkey_file($ssh, $sshUser, $sshKeyPub, $sshKeyPriv)) {
        $stream = ssh2_exec($ssh, "uptime -p");
        stream_set_blocking($stream, true);
        $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
        $uptime = stream_get_contents($stream_out);
        $uptime = str_replace('up ', '', $uptime);
        $uptime = str_replace('years', 'Y', $uptime);
        $uptime = str_replace('year', 'Y', $uptime);
        $uptime = str_replace('mounths', 'M', $uptime);
        $uptime = str_replace('mounth', 'M', $uptime);
        $uptime = str_replace('weeks', 'W', $uptime);
        $uptime = str_replace('week', 'W', $uptime);
        $uptime = str_replace('days', 'd', $uptime);
        $uptime = str_replace('day', 'd', $uptime);
        $uptime = str_replace('hours', 'h', $uptime);
        $uptime = str_replace('hour', 'h', $uptime);
        $uptime = str_replace('minutes', 'm', $uptime);
        $uptime = str_replace('minute', 'm', $uptime);
        $uptime = str_replace('seconds', 's', $uptime);
        $uptime = str_replace('second', 's', $uptime);
        $uptime = str_replace(' ', '', $uptime);
        $uptime = str_replace(',', ' ', $uptime);

        return $uptime;
    } else {
        return '-';
    }
}

function get_temp($machine, $sshUser, $sshKeyPub, $sshKeyPriv) {
    $ssh = ssh2_connect($machine, 22, array('hostkey' => 'ssh-rsa'));
    if (ssh2_auth_pubkey_file($ssh, $sshUser, $sshKeyPub, $sshKeyPriv)) {
        $temp = '';
        $stream = ssh2_exec($ssh, "sensors");
        stream_set_blocking($stream, true);
        $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
        $sensors = stream_get_contents($stream_out);

        if (trim($sensors) !== "") {
            $sensors = str_replace(":\n", ":", $sensors);
            $sensors = str_replace("\n\n", "\n", $sensors);
            $lines = preg_split("/\n/", $sensors, -1, PREG_SPLIT_NO_EMPTY);
        }
        $matcheKey = array_keys(preg_grep('/^coretemp/i', $lines));
        $strinToParse = $lines[$matcheKey[0] + 2];
        $data = explode(" ", $strinToParse);
        $coreTemp = str_replace('+', '', $data[4]);
        $temp = "Cpu: $coreTemp °C";

        $matcheKey = array_keys(preg_grep('/^amdgpu/i', $lines));
        if (!empty($matcheKey)) {
            $i = 1;
            foreach ($matcheKey as $key) {
                $strinToParse = $lines[$key + 3];
                $data = explode(" ", $strinToParse);
                $gpuTemp = str_replace('+', '', $data[8]);
                $temp .= "<br />Gpu$i: $gpuTemp °C";
                $i++;
            }
        } else {
            $stream = ssh2_exec($ssh, "nvidia-smi --query-gpu=temperature.gpu --format=csv,noheader");
            stream_set_blocking($stream, true);
            $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
            $sensors = trim(stream_get_contents($stream_out));
            $data = explode(PHP_EOL, $sensors);
            
            $i = 1;
            foreach ($data as $value) {
                $temp .= "<br />Gpu$i: $value °C";
                $i++;
            }
            
        }
        return $temp;
    } else {
        return '-';
    }
}
