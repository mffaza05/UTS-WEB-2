<?php
require_once '../config/koneksi.php';

$message = '';
$messageType = '';

// Handle DELETE
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    
    $result = $conn->query("SELECT COUNT(*) as count FROM mahasiswa WHERE id_prodi = $id");
    $row = $result->fetch_assoc();
    
    if ($row['count'] > 0) {
        $message = "Tidak dapat menghapus prodi yang masih memiliki mahasiswa!";
        $messageType = "danger";
    } else {
        if ($conn->query("DELETE FROM prodi WHERE id = $id")) {
            $message = "Prodi berhasil dihapus!";
            $messageType = "success";
        } else {
            $message = "Gagal menghapus prodi: " . $conn->error;
            $messageType = "danger";
        }
    }
}

// Fetch all prodi
$result = $conn->query("SELECT * FROM prodi ORDER BY nama_prodi ASC");
$prodi_list = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Program Studi</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="navbar">
        <h1>📚 Sistem Manajemen Akademik</h1>
        
        <nav>
            <a href="../index.php">🏠 Home</a>
            <a href="../mahasiswa/index.php">👨‍🎓 Mahasiswa</a>
            <a href="../prodi/index.php">📖 Program Studi</a>
            <a href="../krs/index.php">📋 KRS</a>
        </nav>
    </div>

    <div class="container">
        <h2 class="page-title">Data Program Studi</h2>

        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="btn-container">
            <a href="tambah.php" class="btn btn-primary">➕ Tambah Program Studi</a>
        </div>

        <?php if (count($prodi_list) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kode Prodi</th>
                        <th>Nama Program Studi</th>
                        <th>Jumlah Mahasiswa</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prodi_list as $index => $p): ?>
                        <?php
                        $mahasiswa_result = $conn->query("SELECT COUNT(*) as count FROM mahasiswa WHERE id_prodi = {$p['id']}");
                        $mahasiswa_count = $mahasiswa_result->fetch_assoc()['count'];
                        ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($p['kode_prodi']); ?></td>
                            <td><?php echo htmlspecialchars($p['nama_prodi']); ?></td>
                            <td><?php echo $mahasiswa_count; ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="edit.php?id=<?php echo $p['id']; ?>" class="btn btn-warning">✏️ Edit</a>
                                    <a href="index.php?hapus=<?php echo $p['id']; ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?')">🗑️ Hapus</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-message">
                📭 Belum ada data program studi
            </div>
        <?php endif; ?>

        <div class="footer">
            <p>MUHAMMAD HAFIZH FAZA QODAMA (2410010546) - UTS Pemrograman Web 2</p>
        </div>
    </div>
</body>
</html>
