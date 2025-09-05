<?php
require 'connect.php';

// ambil data cards
$cards = $pdo->query("SELECT * FROM cards")->fetchAll(PDO::FETCH_ASSOC);

// ambil data logs terbaru
$logs = $pdo->query("
  SELECT r.uid, c.name, c.division, r.action, r.relays, r.created_at
  FROM rfid_logs r
  LEFT JOIN cards c ON r.uid = c.uid
  ORDER BY r.id DESC
  LIMIT 20
")->fetchAll(PDO::FETCH_ASSOC);

// hitung statistik
$totalCards = count($cards);
$totalGranted = $pdo->query("SELECT COUNT(*) FROM rfid_logs WHERE action='GRANTED'")->fetchColumn();
$totalDenied = $pdo->query("SELECT COUNT(*) FROM rfid_logs WHERE action='DENIED'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Access Control</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f8f9fa;
      overflow-x: hidden;
    }
    /* Sidebar */
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
      width: 250px;
      background: #343a40;
      color: #fff;
      transition: all 0.3s;
      overflow-y: auto; /* biar sidebar bisa scroll sendiri */
      z-index: 1030;
    }
    .sidebar a {
      display: block;
      padding: 12px 20px;
      color: #ddd;
      text-decoration: none;
      transition: background 0.2s;
    }
    .sidebar a:hover {
      background: #495057;
      color: #fff;
    }
    .sidebar.active {
      margin-left: -250px;
    }
    /* Content */
    .content {
      margin-left: 250px;
      transition: all 0.3s;
    }
    .content.active {
      margin-left: 0;
    }
    .navbar {
      z-index: 1040;
    }
    .card-stats {
      border-left: 5px solid #0d6efd;
    }
    .card-stats.granted {
      border-left-color: #28a745;
    }
    .card-stats.denied {
      border-left-color: #dc3545;
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar active" id="sidebar">
    <h5 class="text-center py-3 border-bottom border-secondary">Menu</h5>
    <a href="#"><i class="fa fa-gauge"></i> Dashboard</a>
    <a href="#cards"><i class="fa fa-id-card"></i> Daftar Kartu</a>
    <a href="division/division.php"><i class="fa fa-building"></i> Division</a>
    <a href="#logs"><i class="fa fa-clock"></i> Log Akses</a>
    <a href="#"><i class="fa fa-microchip"></i> Device</a>
    <a href="#"><i class="fa fa-gear"></i> Setting</a>
  </div>

  <!-- Content -->
  <div class="content active" id="content">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
      <div class="container-fluid">
        <button class="btn btn-outline-primary me-2" id="toggleSidebar">
          <i class="fa fa-bars"></i>
        </button>
        <a class="navbar-brand" href="#">Dashboard Access Control</a>
      </div>
    </nav>

    <div class="container-fluid py-4">
      <!-- Statistik -->
      <div class="row mb-4">
        <div class="col-md-4">
          <div class="card card-stats shadow-sm">
            <div class="card-body">
              <h6>Total Card</h6>
              <h3><?= $totalCards ?></h3>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card card-stats granted shadow-sm">
            <div class="card-body">
              <h6>Access Granted</h6>
              <h3><?= $totalGranted ?></h3>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card card-stats denied shadow-sm">
            <div class="card-body">
              <h6>Akses Denied</h6>
              <h3><?= $totalDenied ?></h3>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabel Kartu -->
      <div class="card mb-4 shadow-sm" id="cards">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span><i class="fa fa-id-card"></i> List Card</span>
          <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addCardModal"><i class="fa fa-plus"></i> Add Card</button>
        </div>
        <div class="card-body table-responsive">
          <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>UID</th>
                <th>Name</th>
                <th>Division</th>
                <th>Floor Akses</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($cards as $c): ?>
              <tr>
                <td><?= htmlspecialchars($c['uid']) ?></td>
                <td><?= htmlspecialchars($c['name']) ?></td>
                <td><?= htmlspecialchars($c['division']) ?></td>
                <td>
                  <?php
                  $mask = intval($c['mask']);
                  $relays = [];
                  for ($i=0; $i<8; $i++) {
                      if ($mask & (1<<$i)) $relays[] = "LT".($i+1);
                  }
                  echo $relays ? implode(", ", $relays) : "-";
                  ?>
                </td>
                <td>
                  <!-- Tombol Edit -->
                  <button type="button" 
                          class="btn btn-outline-primary btn-sm btn-edit"
                          data-uid="<?= htmlspecialchars($c['uid']) ?>"
                          data-name="<?= htmlspecialchars($c['name']) ?>"
                          data-division="<?= htmlspecialchars($c['division']) ?>"
                          data-mask="<?= intval($c['mask']) ?>">
                    <i class="fas fa-edit"></i>
                  </button>
                  <!-- Tombol Hapus -->
                  <form method="post" action="remove_card.php" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kartu ini?');">
                    <input type="hidden" name="uid" value="<?= htmlspecialchars($c['uid']) ?>">
                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus">
                      <i class="fas fa-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Tabel Log -->
      <div class="card shadow-sm" id="logs">
        <div class="card-header"><i class="fa fa-clock"></i> Log Access (20 new)</div>
        <div class="card-body table-responsive">
          <table class="table table-striped table-hover" id="logsTable">
            <thead>
              <tr>
                <th>UID</th>
                <th>Nama</th>
                <th>Division</th>
                <th>Action</th>
                <th>Floor</th>
                <th>Time</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($logs as $l): ?>
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
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Tambah -->
  <div class="modal fade" id="addCardModal" tabindex="-1">
    <div class="modal-dialog">
      <form method="post" action="add_card.php" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Kartu</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="text" name="uid" class="form-control mb-2" placeholder="UID" required>
          <input type="text" name="name" class="form-control mb-2" placeholder="Nama" required>
          <input type="text" name="division" class="form-control mb-2" placeholder="Divisi" required>
          <label><strong>Pilih Relay Akses:</strong></label><br>
          <?php for ($i=1;$i<=8;$i++): ?>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" name="relays[]" value="LT<?= $i ?>" id="lt<?= $i ?>">
              <label class="form-check-label" for="lt<?= $i ?>">LT<?= $i ?></label>
            </div>
          <?php endfor; ?>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button class="btn btn-primary" type="submit">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Edit -->
  <div class="modal fade" id="editCardModal" tabindex="-1">
    <div class="modal-dialog">
      <form method="post" action="edit_card.php" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Kartu</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="uid_old" id="edit_uid_old">
          <div class="mb-2">
            <label>UID</label>
            <input type="text" name="uid" id="edit_uid" class="form-control" required>
          </div>
          <div class="mb-2">
            <label>Name</label>
            <input type="text" name="name" id="edit_name" class="form-control" required>
          </div>
          <div class="mb-2">
            <label>Divisi</label>
            <input type="text" name="division" id="edit_division" class="form-control" required>
          </div>
          <label><strong>Floor Access:</strong></label><br>
          <?php for ($i=1;$i<=8;$i++): ?>
            <div class="form-check form-check-inline">
              <input class="form-check-input relay-checkbox" type="checkbox" name="relays[]" value="LT<?= $i ?>" id="edit_lt<?= $i ?>">
              <label class="form-check-label" for="edit_lt<?= $i ?>">LT<?= $i ?></label>
            </div>
          <?php endfor; ?>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button class="btn btn-primary" type="submit">Update</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Sidebar toggle
    document.getElementById('toggleSidebar').addEventListener('click', function(){
      document.getElementById('sidebar').classList.toggle('active');
      document.getElementById('content').classList.toggle('active');
    });

    // ketika tombol edit diklik
    $(document).on('click', '.btn-edit', function(){
      let uid = $(this).data('uid');
      let name = $(this).data('name');
      let division = $(this).data('division');
      let mask = parseInt($(this).data('mask'));

      $('#edit_uid_old').val(uid);
      $('#edit_uid').val(uid);
      $('#edit_name').val(name);
      $('#edit_division').val(division);

      // reset checkbox dulu
      $('.relay-checkbox').prop('checked', false);
      for(let i=0; i<8; i++){
        if(mask & (1<<i)){
          $('#edit_lt'+(i+1)).prop('checked', true);
        }
      }

      let modal = new bootstrap.Modal(document.getElementById('editCardModal'));
      modal.show();
    });

    

    // AUTO REFRESH LOGS
    function loadLogs() {
      $.get('get_logs.php', function(data) {
        $("#logsTable tbody").html(data);
      });
    }
    setInterval(loadLogs, 5000); // refresh tiap 5 detik
  </script>
</body>
</html>
