<?php
require_once 'config/koneksi.php';

// Get statistics
$mahasiswa_result = $conn->query("SELECT COUNT(*) as count FROM mahasiswa");
$mahasiswa_count = $mahasiswa_result->fetch_assoc()['count'];

$prodi_result = $conn->query("SELECT COUNT(*) as count FROM prodi");
$prodi_count = $prodi_result->fetch_assoc()['count'];

$krs_result = $conn->query("SELECT COUNT(*) as count FROM krs");
$krs_count = $krs_result->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Akademik</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .stat-card {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: #1abc9c;
            margin: 10px 0;
        }

        .stat-label {
            font-size: 14px;
            color: #7f8c8d;
        }

        .welcome-section {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            border-left: 5px solid #1abc9c;
        }

        .welcome-section h3 {
            color: #2c3e50;
            margin-bottom: 15px;
        }

        .welcome-section p {
            line-height: 1.6;
            color: #555;
            margin-bottom: 10px;
        }

        .features-section {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-top: 30px;
        }

        .features-section h3 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .features-list {
            list-style: none;
            columns: 2;
            gap: 20px;
        }

        .features-list li {
            background-color: #f9f9f9;
            padding: 12px;
            margin-bottom: 10px;
            border-left: 4px solid #1abc9c;
            border-radius: 4px;
        }

        .features-list li:before {
            content: "✓ ";
            color: #27ae60;
            font-weight: bold;
            margin-right: 8px;
        }

        @media (max-width: 768px) {
            .stats {
                grid-template-columns: 1fr;
            }

            .features-list {
                columns: 1;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>📚 Sistem Manajemen Akademik</h1>
        
        <nav>
            <a href="index.php">🏠 Home</a>
            <a href="mahasiswa/index.php">👨‍🎓 Mahasiswa</a>
            <a href="prodi/index.php">📖 Program Studi</a>
            <a href="krs/index.php">📋 KRS</a>
        </nav>
    </div>

    <div class="container">
        <h2 class="page-title">Selamat Datang di Sistem Manajemen Akademik</h2>

        <div class="welcome-section">
            <p>
                Sistem Manajemen Akademik adalah aplikasi CRUD (Create, Read, Update, Delete) yang dibangun menggunakan 
                <strong>PHP Native</strong> dan <strong>MySQL</strong>. Aplikasi ini dirancang untuk mengelola data akademik 
                meliputi Data Mahasiswa, Program Studi, dan Kartu Rencana Studi (KRS) dengan relasi antar tabel yang terstruktur.
            </p>
            <p>
                <strong>Nama:</strong> MUHAMMAD HAFIZH FAZA QODAMA<br>
                <strong>NPM:</strong> 2410010546<br>
                <strong>Database:</strong> uniska_2026_utsweb2_2410010546
            </p>
        </div>

        <h3 style="color: #2c3e50; margin-top: 30px; margin-bottom: 15px;">Statistik Data</h3>
        <div class="stats">
            <div class="stat-card">
                <div style="font-size: 24px;">👨‍🎓</div>
                <div class="stat-number"><?php echo $mahasiswa_count; ?></div>
                <div class="stat-label">Total Mahasiswa</div>
            </div>
            <div class="stat-card">
                <div style="font-size: 24px;">📖</div>
                <div class="stat-number"><?php echo $prodi_count; ?></div>
                <div class="stat-label">Program Studi</div>
            </div>
            <div class="stat-card">
                <div style="font-size: 24px;">📋</div>
                <div class="stat-number"><?php echo $krs_count; ?></div>
                <div class="stat-label">Total KRS</div>
            </div>
        </div>

        <div class="footer">
            <p>MUHAMMAD HAFIZH FAZA QODAMA (2410010546) - UTS Pemrograman Web 2</p>
        </div>
    </div>
</body>
</html>
