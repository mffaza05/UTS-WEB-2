<?php
require_once '../config/koneksi.php';

$message = '';
$messageType = '';
$mahasiswa = null;

if (!isset($_GET['id'])) {
    $message = "ID tidak ditemukan!";
    $messageType = "danger";
} else {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM mahasiswa WHERE id = $id");
    
    if (!$result || $result->num_rows == 0) {
        $message = "Data tidak ditemukan!";
        $messageType = "danger";
    } else {
        $mahasiswa = $result->fetch_assoc();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $npm = trim($_POST['npm']);
            $nama = trim($_POST['nama']);
            $email = trim($_POST['email']);
            $no_telp = trim($_POST['no_telp']);
            $alamat = trim($_POST['alamat']);
            $id_prodi = intval($_POST['id_prodi']);

            if (empty($npm) || empty($nama) || $id_prodi == 0) {
                $message = "NPM, Nama, dan Program Studi harus diisi!";
                $messageType = "danger";
            } else {
                // Check if NPM already exists (but not for this record)
                $check = $conn->query("SELECT id FROM mahasiswa WHERE npm = '$npm' AND id != $id");
                if ($check && $check->num_rows > 0) {
                    $message = "NPM sudah terdaftar!";
                    $messageType = "danger";
                } else {
                    $npm_esc = $conn->real_escape_string($npm);
                    $nama_esc = $conn->real_escape_string($nama);
                    $email_esc = $conn->real_escape_string($email);
                    $no_telp_esc = $conn->real_escape_string($no_telp);
                    $alamat_esc = $conn->real_escape_string($alamat);

                    if ($conn->query("UPDATE mahasiswa SET npm = '$npm_esc', nama = '$nama_esc', email = '$email_esc', no_telp = '$no_telp_esc', alamat = '$alamat_esc', id_prodi = $id_prodi WHERE id = $id")) {
                        $message = "Mahasiswa berhasil diubah!";
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
}

// Fetch prodi for dropdown
$prodi_result = $conn->query("SELECT id, nama_prodi, kode_prodi FROM prodi ORDER BY nama_prodi ASC");
$prodi_list = $prodi_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa</title>
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
        <h2 class="page-title">✏️ Edit Mahasiswa</h2>

        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <?php if ($messageType != "danger" && isset($mahasiswa)): ?>
            <div class="form-container">
                <form method="POST">
                    <div class="form-group">
                        <label for="npm">NPM (Nomor Pokok Mahasiswa) *</label>
                        <input type="text" id="npm" name="npm" required maxlength="20"
                            value="<?php echo htmlspecialchars($mahasiswa['npm']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="nama">Nama Lengkap *</label>
                        <input type="text" id="nama" name="nama" required
                            value="<?php echo htmlspecialchars($mahasiswa['nama']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="id_prodi">Program Studi *</label>
                        <select id="id_prodi" name="id_prodi" required>
                            <option value="">-- Pilih Program Studi --</option>
                            <?php foreach ($prodi_list as $p): ?>
                                <option value="<?php echo $p['id']; ?>" 
                                    <?php echo $p['id'] == $mahasiswa['id_prodi'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($p['nama_prodi'] . ' (' . $p['kode_prodi'] . ')'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email"
                            value="<?php echo htmlspecialchars($mahasiswa['email'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="no_telp">Nomor Telepon</label>
                        <input type="text" id="no_telp" name="no_telp" maxlength="15"
                            value="<?php echo htmlspecialchars($mahasiswa['no_telp'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea id="alamat" name="alamat" rows="4"><?php echo htmlspecialchars($mahasiswa['alamat'] ?? ''); ?></textarea>
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
