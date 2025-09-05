<?php
require 'connect.php'; // Pastikan koneksi PDO ada di sini

// Fungsi untuk konversi array LT1–LT8 ke bitmask
function relaysToBitmask($relays) {
    $mapping = [
        'LT1' => 1 << 0, // 1
        'LT2' => 1 << 1, // 2
        'LT3' => 1 << 2, // 4
        'LT4' => 1 << 3, // 8
        'LT5' => 1 << 4, // 16
        'LT6' => 1 << 5, // 32
        'LT7' => 1 << 6, // 64
        'LT8' => 1 << 7  // 128
    ];

    $mask = 0;
    foreach ($relays as $r) {
        if (isset($mapping[$r])) {
            $mask |= $mapping[$r];
        }
    }
    return $mask;
}

// Jika form disubmit via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uid = $_POST['uid'] ?? '';
    $relays = $_POST['relays'] ?? [];
    $updated = date('Y-m-d H:i:s');

    if ($uid) {
        $mask = relaysToBitmask($relays);

        $stmt = $pdo->prepare("INSERT INTO cards (uid, mask, updated_at) 
                               VALUES (?, ?, ?) 
                               ON DUPLICATE KEY UPDATE mask=VALUES(mask), updated_at=VALUES(updated_at)");
        $stmt->execute([$uid, $mask, $updated]);

        $pdo->prepare("INSERT INTO rfid_logs (uid, action, relays) VALUES (?, 'ADD', ?)")
            ->execute([$uid, $mask]);

        $response = [
            "ok" => true,
            "uid" => $uid,
            "mask" => $mask,
            "bin" => str_pad(decbin($mask), 8, '0', STR_PAD_LEFT),
            "selected_relays" => $relays
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    } else {
        $response = ["ok" => false, "err" => "UID tidak boleh kosong"];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Test Add Card</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .relay-box { margin: 10px 0; }
    .relay-box label { margin-right: 10px; }
    #response { margin-top: 20px; white-space: pre; background: #f0f0f0; padding: 10px; }
  </style>
</head>
<body>

<h2>Test Tambah Kartu RFID</h2>

<form method="POST" id="addCardForm">
  <label for="uid">UID:</label>
  <input type="text" id="uid" name="uid" required>
  
  <div class="relay-box">
    <strong>Pilih Relay (LT1–LT8):</strong><br>
    <?php
    for ($i = 1; $i <= 8; $i++) {
        $lt = "LT$i";
        echo "<label><input type='checkbox' name='relays[]' value='$lt'> $lt</label> ";
        if ($i == 4) echo "<br>";
    }
    ?>
  </div>

  <button type="submit">Kirim</button>
</form>

<div id="response"></div>

<script>
document.getElementById('addCardForm').addEventListener('submit', function(e){
  e.preventDefault();

  let form = e.target;
  let formData = new FormData(form);

  fetch(form.action || '', {
    method: form.method,
    body: formData
  }).then(res => res.json())
    .then(data => {
      document.getElementById('response').textContent = JSON.stringify(data, null, 2);
    })
    .catch(err => {
      document.getElementById('response').textContent = "Error: " + err;
    });
});
</script>

</body>
</html>
