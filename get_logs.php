<?php
require 'connect.php';

$logs = $pdo->query("
  SELECT r.uid, c.name, c.division, r.action, r.relays, r.created_at
  FROM rfid_logs r
  LEFT JOIN cards c ON r.uid = c.uid
  ORDER BY r.id DESC
  LIMIT 20
")->fetchAll(PDO::FETCH_ASSOC);

foreach ($logs as $l): ?>
  <tr>
    <td><?= htmlspecialchars($l['uid']) ?></td>
    <td><?= htmlspecialchars($l['name']) ?></td>
    <td><?= htmlspecialchars($l['division']) ?></td>
    <td>
      <?php if ($l['action'] === 'GRANTED'): ?>
        <span class="badge bg-success">Granted</span>
      <?php elseif ($l['action'] === 'DENIED'): ?>
        <span class="badge bg-danger">Denied</span>
      <?php else: ?>
        <span class="badge bg-secondary"><?= htmlspecialchars($l['action']) ?></span>
      <?php endif; ?>
    </td>
    <td><?= htmlspecialchars($l['relays']) ?></td>
    <td><?= htmlspecialchars($l['created_at']) ?></td>
  </tr>
<?php endforeach; ?>
