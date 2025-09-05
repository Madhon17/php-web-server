<?php
require 'connect.php';

function calculateBitmask($selected_relays) {
    $bitmask = 0;
    foreach ($selected_relays as $relay) {
        $index = intval(substr($relay, 2)) - 1;
        if ($index >= 0 && $index < 8) {
            $bitmask |= (1 << $index);
        }
    }
    return $bitmask;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uid_old = $_POST['uid_old'];
    $uid = $_POST['uid'];
    $name = $_POST['name'];
    $division = $_POST['division'];
    $relays = isset($_POST['relays']) ? $_POST['relays'] : [];

    $mask = calculateBitmask($relays);

    $stmt = $pdo->prepare("UPDATE cards SET uid=?, name=?, division=?, mask=? WHERE uid=?");
    $stmt->execute([$uid, $name, $division, $mask, $uid_old]);

    header("Location: dashboard.php");
    exit;
}
