<?php
require_once 'barangmanager.php';
$barangManager = new BarangManager();
$barangList = $barangManager->getBarang();

// Hitung total nilai inventaris
$totalNilai = array_reduce($barangList, function($carry, $item) {
    return $carry + ($item['harga'] * $item['stok']);
}, 0);

// Hitung total jenis barang
$totalJenis = count($barangList);

// Hitung total stok
$totalStok = array_reduce($barangList, function($carry, $item) {
    return $carry + $item['stok'];
}, 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Inventaris</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f0f2f5;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            padding: 40px 0;
            background: linear-gradient(135deg, #1e88e5, #1565c0);
            color: white;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 1.2em;
            opacity: 0.9;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card i {
            font-size: 2em;
            margin-bottom: 10px;
            color: #1e88e5;
        }

        .stat-card h3 {
            font-size: 1.8em;
            margin-bottom: 5px;
            color: #333;
        }

        .stat-card p {
            color: #666;
            font-size: 1em;
        }

        .actions-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .action-button {
            background: white;
            border: none;
            padding: 20px;
            border-radius: 10px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .action-button:hover {
            background: #1e88e5;
            color: white;
            transform: translateY(-5px);
        }

        .action-button i {
            font-size: 2em;
            margin-bottom: 10px;
        }

        .recent-items {
            margin-top: 30px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .recent-items h2 {
            margin-bottom: 15px;
            color: #333;
        }

        .item-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }

        .item-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #1e88e5;
        }

        .item-card h4 {
            margin-bottom: 5px;
            color: #333;
        }

        .item-card p {
            color: #666;
            font-size: 0.9em;
            margin: 2px 0;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            
            .header {
                padding: 20px 0;
            }

            .header h1 {
                font-size: 2em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Home - Dashboard Inventaris</h1>
            <p>Sistem Manajemen Barang</p>
        </div>

        <div class="stats-container">
            <div class="stat-card">
                <i class="fas fa-boxes"></i>
                <h3><?= number_format($totalJenis) ?></h3>
                <p>Total Jenis Barang</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-cubes"></i>
                <h3><?= number_format($totalStok) ?></h3>
                <p>Total Stok</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-money-bill-wave"></i>
                <h3>Rp <?= number_format($totalNilai) ?></h3>
                <p>Total Nilai Inventaris</p>
            </div>
        </div>

        <div class="actions-container">
            <a href="table.php" class="action-button">
                <i class="fas fa-table"></i>
                <span>Kelola Barang</span>
            </a>
            <a href="table.php" class="action-button">
                <i class="fas fa-plus-circle"></i>
                <span>Tambah Barang</span>
            </a>
            <a href="export-print.php" class="action-button" target="_blank">
                <i class="fas fa-file-export"></i>
                <span>Export PDF</span>
            </a>
        </div>

        <div class="recent-items">
            <h2>Beberapa Barang Terbaru</h2>
            <div class="item-grid">
                <?php 
                $recentItems = array_slice($barangList, -4);
                foreach ($recentItems as $item): 
                ?>
                <div class="item-card">
                    <h4><?= htmlspecialchars($item['nama']) ?></h4>
                    <p>Stok: <?= htmlspecialchars($item['stok']) ?></p>
                    <p>Harga: Rp <?= number_format($item['harga']) ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>