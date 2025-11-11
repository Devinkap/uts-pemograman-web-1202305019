<?php
// penempatan edit php
// edit.php
require_once 'config.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'];

// Get current data
$stmt = $pdo->prepare("SELECT * FROM alat_kesehatan WHERE id = ?");
$stmt->execute([$id]);
$alat = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$alat) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_alat = $_POST['nama_alat'];
    $merk = $_POST['merk'];
    $nomor_seri = $_POST['nomor_seri'];
    $lokasi_alat = $_POST['lokasi_alat'];
    $tanggal_masuk = $_POST['tanggal_masuk'];
    $kondisi = $_POST['kondisi'];
    $catatan = $_POST['catatan'];

    try {
        // Check if serial number already exists (excluding current record)
        $check_stmt = $pdo->prepare("SELECT id FROM alat_kesehatan WHERE nomor_seri = ? AND id != ?");
        $check_stmt->execute([$nomor_seri, $id]);
        
        if ($check_stmt->rowCount() > 0) {
            $error = "Nomor seri sudah terdaftar!";
        } else {
            $stmt = $pdo->prepare("UPDATE alat_kesehatan SET nama_alat = ?, merk = ?, nomor_seri = ?, lokasi_alat = ?, tanggal_masuk = ?, kondisi = ?, catatan = ? WHERE id = ?");
            
            if ($stmt->execute([$nama_alat, $merk, $nomor_seri, $lokasi_alat, $tanggal_masuk, $kondisi, $catatan, $id])) {
                header('Location: index.php?message=Data berhasil diupdate');
                exit;
            } else {
                $error = "Gagal mengupdate data!";
            }
        }
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Alat Kesehatan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">Inventaris Alat Kesehatan</a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Alat Kesehatan</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_alat" class="form-label">Nama Alat *</label>
                                <input type="text" class="form-control" id="nama_alat" name="nama_alat" value="<?= htmlspecialchars($alat['nama_alat']) ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="merk" class="form-label">Merk *</label>
                                <input type="text" class="form-control" id="merk" name="merk" value="<?= htmlspecialchars($alat['merk']) ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nomor_seri" class="form-label">Nomor Seri *</label>
                                <input type="text" class="form-control" id="nomor_seri" name="nomor_seri" value="<?= htmlspecialchars($alat['nomor_seri']) ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="lokasi_alat" class="form-label">Lokasi Alat *</label>
                                <input type="text" class="form-control" id="lokasi_alat" name="lokasi_alat" value="<?= htmlspecialchars($alat['lokasi_alat']) ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal_masuk" class="form-label">Tanggal Masuk *</label>
                                <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" value="<?= $alat['tanggal_masuk'] ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kondisi" class="form-label">Kondisi *</label>
                                <select class="form-select" id="kondisi" name="kondisi" required>
                                    <option value="Baik" <?= $alat['kondisi'] == 'Baik' ? 'selected' : '' ?>>Baik</option>
                                    <option value="Rusak" <?= $alat['kondisi'] == 'Rusak' ? 'selected' : '' ?>>Rusak</option>
                                    <option value="Perbaikan" <?= $alat['kondisi'] == 'Perbaikan' ? 'selected' : '' ?>>Perbaikan</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="catatan" class="form-label">Catatan</label>
                        <textarea class="form-control" id="catatan" name="catatan" rows="3"><?= htmlspecialchars($alat['catatan']) ?></textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>