<?php
// export-print.php
require_once 'barangmanager.php';

$barangManager = new BarangManager();
$barangList = $barangManager->getBarang();

// Hitung total
$totalNilaiKeseluruhan = 0;
foreach($barangList as $barang) {
    $totalNilaiKeseluruhan += ($barang['harga'] * $barang['stok']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Inventaris Barang</title>
    <!-- Tambahkan html2pdf library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        @media print {
            @page {
                size: A4;
                margin: 1cm;
            }
            
            .no-print {
                display: none !important;
            }
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: white;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 14px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-row {
            font-weight: bold;
            background-color: #f2f2f2;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }

        .button-container {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
        }

        .action-button {
            padding: 10px 20px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .print-button {
            background-color: #4CAF50;
        }

        .download-button {
            background-color: #2196F3;
        }

        .action-button:hover {
            opacity: 0.9;
        }

        @media print {
            .button-container {
                display: none;
            }
            
            th, .total-row {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="button-container no-print">
        <button onclick="window.print()" class="action-button print-button">Print PDF</button>
        <button onclick="downloadPDF()" class="action-button download-button">Download PDF</button>
    </div>

    <div id="content-to-pdf">
        <div class="header">
            <div class="title">LAPORAN INVENTARIS BARANG</div>
            <div class="subtitle">Tanggal Cetak: <?= date('d-m-Y H:i:s') ?></div>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width: 5%">No</th>
                    <th style="width: 35%">Nama Barang</th>
                    <th class="text-right" style="width: 20%">Harga (Rp)</th>
                    <th class="text-center" style="width: 15%">Stok</th>
                    <th class="text-right" style="width: 25%">Total Nilai (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                foreach($barangList as $barang): 
                    $totalNilai = $barang['harga'] * $barang['stok'];
                ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= htmlspecialchars($barang['nama']) ?></td>
                    <td class="text-right"><?= number_format($barang['harga']) ?></td>
                    <td class="text-center"><?= number_format($barang['stok']) ?></td>
                    <td class="text-right"><?= number_format($totalNilai) ?></td>
                </tr>
                <?php endforeach; ?>
                
                <tr class="total-row">
                    <td colspan="4" class="text-center">Total Nilai Keseluruhan</td>
                    <td class="text-right"><?= number_format($totalNilaiKeseluruhan) ?></td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <p>Laporan ini digenerate secara otomatis pada <?= date('d-m-Y H:i:s') ?></p>
        </div>
    </div>

    <script>
        function downloadPDF() {
            // Konfigurasi untuk html2pdf
            const opt = {
                margin: 1,
                filename: 'Laporan_Inventaris_<?= date("Y-m-d_H-i-s") ?>.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'cm', format: 'a4', orientation: 'portrait' }
            };

            // Get elemen yang akan di-convert
            const element = document.getElementById('content-to-pdf');

            // Generate PDF
            html2pdf().set(opt).from(element).save();
        }
    </script>
</body>
</html>