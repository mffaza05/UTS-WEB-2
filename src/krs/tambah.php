<?php
require_once '../config/koneksi.php';

$message = '';
$messageType = '';

// Handle Submit
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

        if ($conn->query("INSERT INTO krs (id_mahasiswa, mata_kuliah, kode_mk, semester, sks, nilai) VALUES ($id_mahasiswa, '$mata_kuliah_esc', '$kode_mk_esc', $semester, $sks, '" . ($nilai_esc ?: NULL) . "')")) {
            $message = "KRS berhasil ditambahkan!";
            $messageType = "success";
            header("refresh:1.5;url=index.php");
        } else {
            $message = "Gagal menambahkan data: " . $conn->error;
            $messageType = "danger";
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
    <title>Tambah KRS</title>
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
        <h2 class="page-title">➕ Tambah KRS</h2>

        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <form method="POST">
                <div class="form-group">
                    <label for="id_mahasiswa">Mahasiswa *</label>
                    <select id="id_mahasiswa" name="id_mahasiswa" required>
                        <option value="">-- Pilih Mahasiswa --</option>
                        <?php foreach ($mahasiswa_list as $m): ?>
                            <option value="<?php echo $m['id']; ?>">
                                <?php echo htmlspecialchars($m['npm'] . ' - ' . $m['nama'] . ' (' . $m['nama_prodi'] . ')'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="mata_kuliah">Mata Kuliah *</label>
                    <input type="text" id="mata_kuliah" name="mata_kuliah" required>
                </div>

                <div class="form-group">
                    <label for="kode_mk">Kode Mata Kuliah *</label>
                    <input type="text" id="kode_mk" name="kode_mk" required maxlength="20">
                </div>

                <div class="form-group">
                    <label for="semester">Semester *</label>
                    <input type="number" id="semester" name="semester" required min="1" max="8">
                </div>

                <div class="form-group">
                    <label for="sks">SKS (Satuan Kredit Semester) *</label>
                    <input type="number" id="sks" name="sks" required min="1" max="6">
                </div>

                <div class="form-group">
                    <label for="nilai">Nilai (A, A-, B+, B, B-, C, D, E)</label>
                    <input type="text" id="nilai" name="nilai" maxlength="2" placeholder="Contoh: A, B+">
                </div>

                <div class="form-group" style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-success">💾 Simpan</button>
                    <a href="index.php" class="btn btn-secondary">❌ Batal</a>
                </div>
            </form>
        </div>

        <div class="footer">
            <p>MUHAMMAD HAFIZH FAZA QODAMA (2410010546) - UTS Pemrograman Web 2</p>
        </div>
    </div>
</body>
</html>
