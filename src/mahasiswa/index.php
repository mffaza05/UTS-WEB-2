<?php
require_once '../config/koneksi.php';

$message = '';
$messageType = '';

// Handle DELETE
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    if ($conn->query("DELETE FROM mahasiswa WHERE id = $id")) {
        $message = "Mahasiswa berhasil dihapus!";
        $messageType = "success";
    } else {
        $message = "Gagal menghapus mahasiswa: " . $conn->error;
        $messageType = "danger";
    }
}

// Fetch all mahasiswa with prodi name (JOIN)
$result = $conn->query("
    SELECT m.*, p.nama_prodi, p.kode_prodi 
    FROM mahasiswa m 
    JOIN prodi p ON m.id_prodi = p.id 
    ORDER BY m.nama ASC
");
$mahasiswa_list = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
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
        <h2 class="page-title">Data Mahasiswa</h2>

        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="btn-container">
            <a href="tambah.php" class="btn btn-primary">➕ Tambah Mahasiswa</a>
        </div>

        <?php if (count($mahasiswa_list) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>NPM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Program Studi</th>
                        <th>Email</th>
                        <th>No. Telp</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mahasiswa_list as $index => $m): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($m['npm']); ?></td>
                            <td><?php echo htmlspecialchars($m['nama']); ?></td>
                            <td><?php echo htmlspecialchars($m['nama_prodi']); ?></td>
                            <td><?php echo htmlspecialchars($m['email'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($m['no_telp'] ?? '-'); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="edit.php?id=<?php echo $m['id']; ?>" class="btn btn-warning">✏️ Edit</a>
                                    <a href="index.php?hapus=<?php echo $m['id']; ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?')">🗑️ Hapus</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-message">
                📭 Belum ada data mahasiswa
            </div>
        <?php endif; ?>

        <div class="footer">
            <p>MUHAMMAD HAFIZH FAZA QODAMA (2410010546) - UTS Pemrograman Web 2</p>
        </div>
    </div>
</body>
</html>
