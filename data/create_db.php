<?php
$db = new PDO('sqlite:' . __DIR__ . '/ecmgui.db');
$fh = fopen(__DIR__ . '/schema.sql', 'r');
while ($line = fread($fh, 4096)) {
    $line = trim($line);
    $db->exec($line);
}
fclose($fh);