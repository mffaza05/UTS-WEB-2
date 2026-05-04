<?php
require_once '../config/koneksi.php';

$message = '';
$messageType = '';

// Handle DELETE
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    if ($conn->query("DELETE FROM krs WHERE id = $id")) {
        $message = "KRS berhasil dihapus!";
        $messageType = "success";
    } else {
        $message = "Gagal menghapus KRS: " . $conn->error;
        $messageType = "danger";
    }
}

// Fetch all KRS with mahasiswa name (JOIN)
$result = $conn->query("
    SELECT k.*, m.npm, m.nama as nama_mahasiswa 
    FROM krs k 
    JOIN mahasiswa m ON k.id_mahasiswa = m.id 
    ORDER BY m.nama ASC, k.semester ASC
");
$krs_list = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data KRS</title>
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
        <h2 class="page-title">Data KRS (Kartu Rencana Studi)</h2>

        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="btn-container">
            <a href="tambah.php" class="btn btn-primary">➕ Tambah KRS</a>
        </div>

        <?php if (count($krs_list) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>NPM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Kode MK</th>
                        <th>Mata Kuliah</th>
                        <th>Semester</th>
                        <th>SKS</th>
                        <th>Nilai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($krs_list as $index => $k): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($k['npm']); ?></td>
                            <td><?php echo htmlspecialchars($k['nama_mahasiswa']); ?></td>
                            <td><?php echo htmlspecialchars($k['kode_mk']); ?></td>
                            <td><?php echo htmlspecialchars($k['mata_kuliah']); ?></td>
                            <td><?php echo $k['semester']; ?></td>
                            <td><?php echo $k['sks']; ?></td>
                            <td><?php echo htmlspecialchars($k['nilai'] ?? '-'); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="edit.php?id=<?php echo $k['id']; ?>" class="btn btn-warning">✏️ Edit</a>
                                    <a href="index.php?hapus=<?php echo $k['id']; ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?')">🗑️ Hapus</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-message">
                📭 Belum ada data KRS
            </div>
        <?php endif; ?>

        <div class="footer">
            <p>MUHAMMAD HAFIZH FAZA QODAMA (2410010546) - UTS Pemrograman Web 2</p>
        </div>
    </div>
</body>
</html>
