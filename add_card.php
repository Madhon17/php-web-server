<?php
require 'connect.php';

function calculateBitmask($selected_relays) {
    $bitmask = 0;
    foreach ($selected_relays as $relay) {
        $index = intval(substr($relay, 2)) - 1; // LT1 → bit 0
        if ($index >= 0 && $index < 8) {
            $bitmask |= (1 << $index);
        }
    }
    return $bitmask;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uid = $_POST['uid'];
    $name = $_POST['name'];
    $division = $_POST['division'];
    $relays = isset($_POST['relays']) ? $_POST['relays'] : [];

    $mask = calculateBitmask($relays);

    $stmt = $pdo->prepare("INSERT INTO cards (uid, name, division, mask) VALUES (?, ?, ?, ?)");
    $stmt->execute([$uid, $name, $division, $mask]);

    header("Location: dashboard.php");
    exit;
}
