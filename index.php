<?php
// index.php
require_once 'config.php';

// bagian delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM alat_kesehatan WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: index.php?message=Data berhasil dihapus');
        exit;
    } catch (PDOException $e) {
        header('Location: index.php?error=Gagal menghapus data: ' . $e->getMessage());
        exit;
    }
}

// Search functionality
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $stmt = $pdo->prepare("SELECT * FROM alat_kesehatan WHERE nama_alat LIKE ? OR merk LIKE ? OR nomor_seri LIKE ?");
    $stmt->execute(["%$search%", "%$search%", "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM alat_kesehatan ORDER BY created_at DESC");
}
$alat = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- mengedit title awal-->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaris Alat Kesehatan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Inventaris Alat Kesehatan</a>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_GET['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_GET['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Alat Kesehatan</h5>
                <a href="tambah.php" class="btn btn-primary">Tambah Alat</a>
            </div>
            <div class="card-body">
                <!-- Search Form -->
                <form method="GET" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama alat, merk, atau nomor seri..." value="<?= htmlspecialchars($search) ?>">
                        <button class="btn btn-outline-secondary" type="submit">Cari</button>
                        <?php if ($search): ?>
                            <a href="index.php" class="btn btn-outline-danger">Reset</a>
                        <?php endif; ?>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Alat</th>
                                <th>Merk</th>
                                <th>Nomor Seri</th>
                                <th>Lokasi</th>
                                <th>Tanggal Masuk</th>
                                <th>Kondisi</th>
                                <th>Catatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($alat)): ?>
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada data alat kesehatan</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($alat as $index => $item): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($item['nama_alat']) ?></td>
                                    <td><?= htmlspecialchars($item['merk']) ?></td>
                                    <td><?= htmlspecialchars($item['nomor_seri']) ?></td>
                                    <td><?= htmlspecialchars($item['lokasi_alat']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($item['tanggal_masuk'])) ?></td>
                                    <td>
                                        <?php
                                        $status_class = '';
                                        switch($item['kondisi']) {
                                            case 'Baik': $status_class = 'status-baik'; break;
                                            case 'Rusak': $status_class = 'status-rusak'; break;
                                            case 'Perbaikan': $status_class = 'status-perbaikan'; break;
                                        }
                                        ?>
                                        <span class="<?= $status_class ?>"><?= $item['kondisi'] ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($item['catatan']) ?: '-' ?></td>
                                    <td>
                                        <a href="edit.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-warning btn-action">Edit</a>
                                        <a href="index.php?delete=<?= $item['id'] ?>" class="btn btn-sm btn-danger btn-action" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>