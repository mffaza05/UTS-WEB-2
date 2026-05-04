<?php
require_once '../config/koneksi.php';

$message = '';
$messageType = '';
$krs = null;

if (!isset($_GET['id'])) {
    $message = "ID tidak ditemukan!";
    $messageType = "danger";
} else {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM krs WHERE id = $id");
    
    if (!$result || $result->num_rows == 0) {
        $message = "Data tidak ditemukan!";
        $messageType = "danger";
    } else {
        $krs = $result->fetch_assoc();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_mahasiswa = intval($_POST['id_mahasiswa']);
            $mata_kuliah = trim($_POST['mata_kuliah']);
            $kode_mk = trim($_POST['kode_mk']);
            $semester = intval($_POST['semester']);
            $sks = intval($_POST['sks']);
            $nilai = trim($_POST['nilai']);

            if ($id_mahasiswa == 0 || empty($mata_kuliah) || empty($kode_mk) || $semester <= 0 || $sks <= 0) {
                $message = "Semua field (kecuali nilai) harus diisi dengan benar!";
                $messageType = "danger";
            } else {
                $mata_kuliah_esc = $conn->real_escape_string($mata_kuliah);
                $kode_mk_esc = $conn->real_escape_string($kode_mk);
                $nilai_esc = $conn->real_escape_string($nilai);

                if ($conn->query("UPDATE krs SET id_mahasiswa = $id_mahasiswa, mata_kuliah = '$mata_kuliah_esc', kode_mk = '$kode_mk_esc', semester = $semester, sks = $sks, nilai = '" . ($nilai_esc ?: NULL) . "' WHERE id = $id")) {
                    $message = "KRS berhasil diubah!";
                    $messageType = "success";
                    header("refresh:1.5;url=index.php");
                } else {
                    $message = "Gagal mengubah data: " . $conn->error;
                    $messageType = "danger";
                }
            }
        }
    }
}

// Fetch mahasiswa for dropdown
$mahasiswa_result = $conn->query("
    SELECT m.id, m.npm, m.nama, p.nama_prodi 
    FROM mahasiswa m 
    JOIN prodi p ON m.id_prodi = p.id 
    ORDER BY m.nama ASC
");
$mahasiswa_list = $mahasiswa_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit KRS</title>
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
        <h2 class="page-title">✏️ Edit KRS</h2>

        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <?php if ($messageType != "danger" && isset($krs)): ?>
            <div class="form-container">
                <form method="POST">
                    <div class="form-group">
                        <label for="id_mahasiswa">Mahasiswa *</label>
                        <select id="id_mahasiswa" name="id_mahasiswa" required>
                            <option value="">-- Pilih Mahasiswa --</option>
                            <?php foreach ($mahasiswa_list as $m): ?>
                                <option value="<?php echo $m['id']; ?>" 
                                    <?php echo $m['id'] == $krs['id_mahasiswa'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($m['npm'] . ' - ' . $m['nama'] . ' (' . $m['nama_prodi'] . ')'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="mata_kuliah">Mata Kuliah *</label>
                        <input type="text" id="mata_kuliah" name="mata_kuliah" required
                            value="<?php echo htmlspecialchars($krs['mata_kuliah']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="kode_mk">Kode Mata Kuliah *</label>
                        <input type="text" id="kode_mk" name="kode_mk" required maxlength="20"
                            value="<?php echo htmlspecialchars($krs['kode_mk']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="semester">Semester *</label>
                        <input type="number" id="semester" name="semester" required min="1" max="8"
                            value="<?php echo $krs['semester']; ?>">
                    </div>

                    <div class="form-group">
                        <label for="sks">SKS (Satuan Kredit Semester) *</label>
                        <input type="number" id="sks" name="sks" required min="1" max="6"
                            value="<?php echo $krs['sks']; ?>">
                    </div>

                    <div class="form-group">
                        <label for="nilai">Nilai (A, A-, B+, B, B-, C, D, E)</label>
                        <input type="text" id="nilai" name="nilai" maxlength="2" placeholder="Contoh: A, B+"
                            value="<?php echo htmlspecialchars($krs['nilai'] ?? ''); ?>">
                    </div>

                    <div class="form-group" style="display: flex; gap: 10px;">
                        <button type="submit" class="btn btn-success">💾 Ubah</button>
                        <a href="index.php" class="btn btn-secondary">❌ Batal</a>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="form-container">
                <p>Tidak dapat mengakses halaman ini.</p>
                <a href="index.php" class="btn btn-secondary">Kembali ke Daftar</a>
            </div>
        <?php endif; ?>

        <div class="footer">
            <p>MUHAMMAD HAFIZH FAZA QODAMA (2410010546) - UTS Pemrograman Web 2</p>
        </div>
    </div>
</body>
</html>
