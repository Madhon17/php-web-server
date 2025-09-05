<?php
function calculateBitmask($selected_relays) {
    $bitmask = 0;
    foreach ($selected_relays as $relay) {
        // LT1 → bit 0, LT2 → bit 1, ..., LT8 → bit 7
        $index = intval(substr($relay, 2)) - 1;
        if ($index >= 0 && $index < 8) {
            $bitmask |= (1 << $index);
        }
    }
    return $bitmask;
}

// Proses form
$bitmask = 0;
$selected = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected = isset($_POST['relays']) ? $_POST['relays'] : [];
    $bitmask = calculateBitmask($selected);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bitmask LT1 - LT8</title>
</head>
<body>
    <h2>Pilih Relay (LT1 - LT8)</h2>
    <form method="post">
        <?php
        for ($i = 1; $i <= 8; $i++) {
            $lt = "LT$i";
            $checked = in_array($lt, $selected) ? 'checked' : '';
            echo "<label><input type='checkbox' name='relays[]' value='$lt' $checked> $lt</label><br>";
        }
        ?>
        <br>
        <button type="submit">Hitung Bitmask</button>
    </form>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <h3>Hasil:</h3>
        <p><strong>Dipilih:</strong> <?php echo implode(', ', $selected); ?></p>
        <p><strong>Bitmask Desimal:</strong> <?php echo $bitmask; ?></p>
        <p><strong>Bitmask Biner:</strong> <?php echo str_pad(decbin($bitmask), 8, '0', STR_PAD_LEFT); ?></p>
    <?php endif; ?>
</body>
</html>
