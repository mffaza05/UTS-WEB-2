<?php
require_once '../config/koneksi.php';

$message = '';
$messageType = '';
$prodi = null;

// Check if editing
$is_edit = isset($_GET['id']);
if ($is_edit) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM prodi WHERE id = $id");
    if ($result && $result->num_rows > 0) {
        $prodi = $result->fetch_assoc();
    } else {
        $message = "Data tidak ditemukan!";
        $messageType = "danger";
    }
}

// Handle Submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_prodi = trim($_POST['nama_prodi']);
    $kode_prodi = trim($_POST['kode_prodi']);

    if (empty($nama_prodi) || empty($kode_prodi)) {
        $message = "Semua field harus diisi!";
        $messageType = "danger";
    } else {
        if ($is_edit) {
            $id = intval($_GET['id']);
            $check = $conn->query("SELECT id FROM prodi WHERE (nama_prodi = '$nama_prodi' OR kode_prodi = '$kode_prodi') AND id != $id");
            if ($check && $check->num_rows > 0) {
                $message = "Nama atau kode prodi sudah terdaftar!";
                $messageType = "danger";
            } else {
                $nama_prodi_esc = $conn->real_escape_string($nama_prodi);
                $kode_prodi_esc = $conn->real_escape_string($kode_prodi);
                if ($conn->query("UPDATE prodi SET nama_prodi = '$nama_prodi_esc', kode_prodi = '$kode_prodi_esc' WHERE id = $id")) {
                    $message = "Program studi berhasil diubah!";
                    $messageType = "success";
                    header("refresh:1.5;url=index.php");
                } else {
                    $message = "Gagal mengubah data: " . $conn->error;
                    $messageType = "danger";
                }
            }
        } else {
            $check = $conn->query("SELECT id FROM prodi WHERE nama_prodi = '$nama_prodi' OR kode_prodi = '$kode_prodi'");
            if ($check && $check->num_rows > 0) {
                $message = "Nama atau kode prodi sudah terdaftar!";
                $messageType = "danger";
            } else {
                $nama_prodi_esc = $conn->real_escape_string($nama_prodi);
                $kode_prodi_esc = $conn->real_escape_string($kode_prodi);
                if ($conn->query("INSERT INTO prodi (nama_prodi, kode_prodi) VALUES ('$nama_prodi_esc', '$kode_prodi_esc')")) {
                    $message = "Program studi berhasil ditambahkan!";
                    $messageType = "success";
                    header("refresh:1.5;url=index.php");
                } else {
                    $message = "Gagal menambahkan data: " . $conn->error;
                    $messageType = "danger";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $is_edit ? 'Edit' : 'Tambah'; ?> Program Studi</title>
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
        <h2 class="page-title"><?php echo $is_edit ? '✏️ Edit' : '➕ Tambah'; ?> Program Studi</h2>

        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <form method="POST">
                <div class="form-group">
                    <label for="nama_prodi">Nama Program Studi *</label>
                    <input type="text" id="nama_prodi" name="nama_prodi" required 
                        value="<?php echo $prodi ? htmlspecialchars($prodi['nama_prodi']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="kode_prodi">Kode Program Studi *</label>
                    <input type="text" id="kode_prodi" name="kode_prodi" required maxlength="20"
                        value="<?php echo $prodi ? htmlspecialchars($prodi['kode_prodi']) : ''; ?>">
                </div>

                <div class="form-group" style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-success"><?php echo $is_edit ? '💾 Ubah' : '💾 Simpan'; ?></button>
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
