<?php
function get_nanopool($nanopoolUrl) {
    $content = file_get_contents($nanopoolUrl);
    return json_decode($content);
}

function get_coinmarketcap($currency) {
    $content = file_get_contents("https://api.coinmarketcap.com/v1/ticker/$currency/?convert=EUR");
    return json_decode($content);
}

function get_uptime($machine, $last_machine, $last_uptime, $sshUser, $sshKeyPub, $sshKeyPriv) {
    if ($last_machine !== $machine) {
        $ssh = ssh2_connect($machine, 22, array('hostkey' => 'ssh-rsa'));
        if (ssh2_auth_pubkey_file($ssh, $sshUser, $sshKeyPub, $sshKeyPriv)) {
            $stream = ssh2_exec($ssh, "uptime -p");
            stream_set_blocking($stream, true);
            $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
            $uptime = stream_get_contents($stream_out);
            $uptime = str_replace('up ', '', $uptime);
            return $uptime;
        } else {
            return '-';
        }
    } else {
        return $last_uptime;
    }
}