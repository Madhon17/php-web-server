<?php
require 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['uid'])) {
    $uid = $_POST['uid'];

    // ambil data kartu sebelum dihapus
    $stmt = $pdo->prepare("SELECT * FROM cards WHERE uid = ?");
    $stmt->execute([$uid]);
    $card = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($card) {
        // hapus kartu
        $del = $pdo->prepare("DELETE FROM cards WHERE uid = ?");
        $del->execute([$uid]);

        // masukkan ke log
        $log = $pdo->prepare("INSERT INTO rfid_logs (uid, action, relays, created_at) VALUES (?, 'DELETED', ?, NOW())");
        $log->execute([
            $card['uid'],
            $card['mask'] ?? ''
        ]);
    }
}

header("Location: dashboard.php");
exit;
