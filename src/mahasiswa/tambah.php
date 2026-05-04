<?php
require_once '../config/koneksi.php';

$message = '';
$messageType = '';

// Handle Submit
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
        // Check if NPM already exists
        $check = $conn->query("SELECT id FROM mahasiswa WHERE npm = '$npm'");
        if ($check && $check->num_rows > 0) {
            $message = "NPM sudah terdaftar!";
            $messageType = "danger";
        } else {
            $npm_esc = $conn->real_escape_string($npm);
            $nama_esc = $conn->real_escape_string($nama);
            $email_esc = $conn->real_escape_string($email);
            $no_telp_esc = $conn->real_escape_string($no_telp);
            $alamat_esc = $conn->real_escape_string($alamat);

            if ($conn->query("INSERT INTO mahasiswa (npm, nama, email, no_telp, alamat, id_prodi) VALUES ('$npm_esc', '$nama_esc', '$email_esc', '$no_telp_esc', '$alamat_esc', $id_prodi)")) {
                $message = "Mahasiswa berhasil ditambahkan!";
                $messageType = "success";
                header("refresh:1.5;url=index.php");
            } else {
                $message = "Gagal menambahkan data: " . $conn->error;
                $messageType = "danger";
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
    <title>Tambah Mahasiswa</title>
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
        <h2 class="page-title">➕ Tambah Mahasiswa</h2>

        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <form method="POST">
                <div class="form-group">
                    <label for="npm">NPM (Nomor Pokok Mahasiswa) *</label>
                    <input type="text" id="npm" name="npm" required maxlength="20">
                </div>

                <div class="form-group">
                    <label for="nama">Nama Lengkap *</label>
                    <input type="text" id="nama" name="nama" required>
                </div>

                <div class="form-group">
                    <label for="id_prodi">Program Studi *</label>
                    <select id="id_prodi" name="id_prodi" required>
                        <option value="">-- Pilih Program Studi --</option>
                        <?php foreach ($prodi_list as $p): ?>
                            <option value="<?php echo $p['id']; ?>">
                                <?php echo htmlspecialchars($p['nama_prodi'] . ' (' . $p['kode_prodi'] . ')'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email">
                </div>

                <div class="form-group">
                    <label for="no_telp">Nomor Telepon</label>
                    <input type="text" id="no_telp" name="no_telp" maxlength="15">
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="4"></textarea>
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
