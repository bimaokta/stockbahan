<?php
require 'function.php'; // koneksi database

// Ambil daftar bahan baku
$barang = mysqli_query($conn, "SELECT * FROM stock");

// Fungsi untuk menghitung MAPE
function hitungMAPE($aktual, $peramalan) {
    $n = count($aktual);
    $sum = 0;
    for ($i = 0; $i < $n; $i++) {
        if ($aktual[$i] != 0) {
            $sum += abs(($aktual[$i] - $peramalan[$i]) / $aktual[$i]);
        }
    }
    return ($n > 0) ? round(($sum / $n) * 100, 2) : 0;
}
?>
<!DOCTYPE html>
<html lang="en"> 
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>SOEGENG RAWOEH</title>
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">VIAIPI TEAM</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
        
        <!-- Navbar-->
        <ul class="navbar-nav ml-auto ml-md-20">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="logout.php">Logout</a>
                </div>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                            <div class="sb-sidenav-menu-heading">Menu Utama</div>

                            <a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? ' active' : '' ?>" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
                                Stok Bahan Tersedia
                            </a>

                            <a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'barang masuk.php' ? ' active' : '' ?>" href="barang masuk.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-arrow-down"></i></div>
                                Stok Bahan Masuk
                            </a>

                            <a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'barang keluar.php' ? ' active' : '' ?>" href="barang keluar.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-arrow-up"></i></div>
                                Stok Bahan Keluar
                            </a>

                            <div class="sb-sidenav-menu-heading">Forcasting</div>

                            
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseForecast" aria-expanded="false" aria-controls="collapseForecast">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                                Peramalan
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>

                            <div class="collapse<?= in_array(basename($_SERVER['PHP_SELF']), ['wma.php', 'cek_mape.php']) ? ' show' : '' ?>" id="collapseForecast" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'wma.php' ? ' active' : '' ?>" href="wma.php">Perkiraan Stok Bulan Depan</a>
                                    <a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'cek_mape.php' ? ' active' : '' ?>" href="cek_mape.php">Cek Akurasi Peramalan (MAPE)</a>
                                </nav>
                            </div>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Konten Utama -->
        <div id="layoutSidenav_content">
            <main class="container-fluid p-4">
                 <h1 class="mt-4">Cek MAPE</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">KEDAI SOEGENG RAWOEH</li>
                        </ol>
                

                <?php while($b = mysqli_fetch_assoc($barang)): ?>
                    <h3><?= $b['namabarang']; ?></h3>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Bulan</th>
                                <th>Data Aktual</th>
                                <th>Hasil Peramalan</th>
                                <th>MAPE (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $data_aktual = [];
                        $query_aktual = mysqli_query($conn, "
                            SELECT DATE_FORMAT(tanggal, '%m-%Y') AS bulan, SUM(qty) AS total
                            FROM keluar
                            WHERE idbarang = '{$b['idbarang']}'
                            GROUP BY bulan
                            ORDER BY STR_TO_DATE(CONCAT('01-', bulan), '%d-%m-%Y')
                        ");
                        while($row = mysqli_fetch_assoc($query_aktual)) {
                            $data_aktual[$row['bulan']] = $row['total'];
                        }

                        $data_peramalan = [];
                        $query_peramalan = mysqli_query($conn, "
                            SELECT bulan_tahun AS bulan, hasil
                            FROM hasil_peramalan
                            WHERE idbarang = '{$b['idbarang']}'
                            ORDER BY STR_TO_DATE(CONCAT('01-', bulan_tahun), '%d-%m-%Y')
                        ");
                        while($row = mysqli_fetch_assoc($query_peramalan)) {
                            $data_peramalan[$row['bulan']] = $row['hasil'];
                        }

                        $bulan_semua = array_unique(array_merge(array_keys($data_aktual), array_keys($data_peramalan)));
                        // Urutkan dari yang terlama ke terbaru
                        usort($bulan_semua, function($a, $b) {
                            $dateA = DateTime::createFromFormat('m-Y', $a);
                            $dateB = DateTime::createFromFormat('m-Y', $b);
                            return $dateA <=> $dateB;
                        });

                        $aktual_values = [];
                        $peramalan_values = [];

                        foreach($bulan_semua as $bulan) {
                            $aktual = isset($data_aktual[$bulan]) ? $data_aktual[$bulan] : 0;
                            $peramalan = isset($data_peramalan[$bulan]) ? $data_peramalan[$bulan] : 0;
                            $mape = ($aktual != 0) ? round(abs(($aktual - $peramalan) / $aktual) * 100, 2) : 0;

                            $aktual_values[] = $aktual;
                            $peramalan_values[] = $peramalan;
                            ?>
                            <tr>
                                <td><?= $bulan; ?></td>
                                <td><?= $aktual; ?></td>
                                <td><?= $peramalan; ?></td>
                                <td><?= $mape; ?>%</td>
                            </tr>
                        <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3">MAPE Rata-rata</th>
                                <th><?= hitungMAPE($aktual_values, $peramalan_values); ?>%</th>
                            </tr>
                        </tfoot>
                    </table>
                <?php endwhile; ?>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
