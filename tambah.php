<?php
// tambah.php
require_once 'config.php';

$error = '';
$column_names = [];

// Debug: Cek struktur tabel
try {
    // Cek apakah tabel ada
    $test_query = $pdo->query("SHOW TABLES LIKE 'alat_kesehatan'");
    $table_exists = $test_query->rowCount() > 0;
    
    if (!$table_exists) {
        $error = "Tabel 'alat_kesehatan' tidak ditemukan! Silakan buat database terlebih dahulu.";
    } else {
        // Dapatkan nama kolom yang sebenarnya
        $columns_query = $pdo->query("SHOW COLUMNS FROM alat_kesehatan");
        $columns = $columns_query->fetchAll();
        $column_names = array_column($columns, 'Field');
        
        // Tampilkan info debug (akan muncul di log error)
        error_log("Kolom yang tersedia di database: " . implode(', ', $column_names));
        
        // Cek kolom nomor_seri ada
        if (!in_array('nomor_seri', $column_names)) {
            $error = "Kolom 'nomor_seri' tidak ditemukan. Kolom yang tersedia: " . implode(', ', $column_names);
        }
    }
} catch (PDOException $e) {
    $error = "Error koneksi database: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($error)) {
    $nama_alat = trim($_POST['nama_alat']);
    $merk = trim($_POST['merk']);
    $nomor_seri = trim($_POST['nomor_seri']);
    $lokasi_alat = trim($_POST['lokasi_alat']);
    $tanggal_masuk = $_POST['tanggal_masuk'];
    $kondisi = $_POST['kondisi'];
    $catatan = trim($_POST['catatan']);

    // input database
    if (empty($nama_alat) || empty($merk) || empty($nomor_seri) || empty($lokasi_alat) || empty($tanggal_masuk) || empty($kondisi)) {
        $error = "Semua field bertanda * harus diisi!";
    } else {
        try {
            // Check if serial number already exists
            $check_stmt = $pdo->prepare("SELECT id FROM alat_kesehatan WHERE nomor_seri = ?");
            $check_stmt->execute([$nomor_seri]);
            
            if ($check_stmt->rowCount() > 0) {
                $error = "Nomor seri '$nomor_seri' sudah terdaftar!";
            } else {
                $stmt = $pdo->prepare("INSERT INTO alat_kesehatan (nama_alat, merk, nomor_seri, lokasi_alat, tanggal_masuk, kondisi, catatan) VALUES (?, ?, ?, ?, ?, ?, ?)");
                
                if ($stmt->execute([$nama_alat, $merk, $nomor_seri, $lokasi_alat, $tanggal_masuk, $kondisi, $catatan])) {
                    header('Location: index.php?message=Data berhasil ditambahkan');
                    exit;
                } else {
                    $error = "Gagal menambahkan data!";
                }
            }
        } catch (PDOException $e) {
            $error = "Error database: " . $e->getMessage() . 
                    "<br><small>Pastikan struktur database sudah sesuai dengan yang diharapkan.</small>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Alat Kesehatan</title>
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
                <h5 class="mb-0">Tambah Alat Kesehatan</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger">
                        <strong>Error:</strong> 
                        <div><?= $error ?></div>
                        <?php if (!empty($column_names)): ?>
                            <div class="mt-2">
                                <small><strong>Kolom yang terdeteksi:</strong> <?= implode(', ', $column_names) ?></small>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if (empty($error) || strpos($error, 'kolom') === false): ?>
                <form method="POST" id="formTambah">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_alat" class="form-label">Nama Alat <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_alat" name="nama_alat" required 
                                       value="<?= isset($_POST['nama_alat']) ? htmlspecialchars($_POST['nama_alat']) : '' ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="merk" class="form-label">Merk <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="merk" name="merk" required
                                       value="<?= isset($_POST['merk']) ? htmlspecialchars($_POST['merk']) : '' ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nomor_seri" class="form-label">Nomor Seri <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nomor_seri" name="nomor_seri" required
                                       value="<?= isset($_POST['nomor_seri']) ? htmlspecialchars($_POST['nomor_seri']) : '' ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="lokasi_alat" class="form-label">Lokasi Alat <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="lokasi_alat" name="lokasi_alat" required
                                       value="<?= isset($_POST['lokasi_alat']) ? htmlspecialchars($_POST['lokasi_alat']) : '' ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal_masuk" class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" required
                                       value="<?= isset($_POST['tanggal_masuk']) ? htmlspecialchars($_POST['tanggal_masuk']) : '' ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kondisi" class="form-label">Kondisi <span class="text-danger">*</span></label>
                                <select class="form-select" id="kondisi" name="kondisi" required>
                                    <option value="">Pilih Kondisi</option>
                                    <option value="Baik" <?= (isset($_POST['kondisi']) && $_POST['kondisi'] == 'Baik') ? 'selected' : '' ?>>Baik</option>
                                    <option value="Rusak" <?= (isset($_POST['kondisi']) && $_POST['kondisi'] == 'Rusak') ? 'selected' : '' ?>>Rusak</option>
                                    <option value="Perbaikan" <?= (isset($_POST['kondisi']) && $_POST['kondisi'] == 'Perbaikan') ? 'selected' : '' ?>>Perbaikan</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="catatan" class="form-label">Catatan</label>
                        <textarea class="form-control" id="catatan" name="catatan" rows="3"><?= isset($_POST['catatan']) ? htmlspecialchars($_POST['catatan']) : '' ?></textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <p>Silakan perbaiki struktur database terlebih dahulu sebelum menambah data.</p>
                        <a href="index.php" class="btn btn-secondary">Kembali ke Beranda</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>