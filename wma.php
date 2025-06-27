<?php
require 'function.php';



$hasil = $_GET['hasil'] ?? null;
$error = null;
$namabarang = "";
$bulanTahun = $_GET['bulanTahun'] ?? date('m-Y', strtotime('+1 month'));

if (isset($_POST['proses'])) {
    $idbarang = $_POST['idbarang'];

    // Ambil 3 bulan terakhir
    $query = "SELECT DATE_FORMAT(tanggal, '%m-%Y') as bulan, SUM(qty) as total
              FROM keluar 
              WHERE idbarang = '$idbarang' 
              GROUP BY bulan 
              ORDER BY STR_TO_DATE(CONCAT('01-', bulan), '%d-%m-%Y') DESC 
              LIMIT 3";
    
    $result = mysqli_query($conn, $query);
    $jumlah = [];

    while($row = mysqli_fetch_assoc($result)){
        $jumlah[] = $row['total'];
    }

    if(count($jumlah) == 3){
        // Hitung WMA
        $wma = ($jumlah[0]*0.5) + ($jumlah[1]*0.3) + ($jumlah[2]*0.2);
        $hasil = (float)$wma; 

        // Simpan ke database
        simpanHasilPeramalan($conn, $idbarang, $bulanTahun, $hasil);

        // Redirect agar tidak double submit
        header("Location: wma.php?sukses=1&idbarang=$idbarang&hasil=" . number_format((float)$hasil, 1, '.', '') . "&bulanTahun=$bulanTahun");
        exit;
    } else {
        // Redirect dengan pesan error
        header("Location: wma.php?error=1");
        exit;
    }
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
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Peramalan Stok Bahan</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">KEDAI SOEGENG RAWOEH</li>
                    </ol>

                    <div class="container mt-5">
                        <h2>Peramalan Stok Bahan Baku - Metode WMA</h2>
                        <form method="post">
                            <label for="idbarang">Pilih Bahan:</label>
                            <select name="idbarang" class="form-control" required>
                                <option value="">-- Pilih Bahan --</option>
                                <?php
                                $barang = mysqli_query($conn, "SELECT * FROM stock");
                                while($b = mysqli_fetch_assoc($barang)) {
                                    echo "<option value='{$b['idbarang']}'>{$b['namabarang']}</option>";
                                }
                                ?>
                            </select>
                            <br>
                            <button type="submit" name="proses" class="btn btn-primary">Lihat & Simpan Peramalan</button>
                        </form>
                        <br>

                        <?php if(isset($_GET['sukses'])): ?>
                        <div class="alert alert-success">
                            Hasil peramalan untuk <strong><?= $namabarang ?></strong> pada bulan <strong><?= $bulanTahun ?></strong> adalah <strong><?= number_format((float)$hasil, 1, '.', '') ?></strong> Kg.
                        </div>
                        <?php elseif(isset($_GET['error'])): ?>
                            <div class="alert alert-danger">Data tidak cukup untuk peramalan (butuh 3 bulan terakhir).</div>
                        <?php endif; ?>

                        <hr>

                        <h4>Riwayat Hasil Peramalan</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Bulan</th>
                                    <th>Nama Bahan</th>
                                    <th>Hasil Peramalan</th>
                                    <th>Stok saat ini</th>
                                    <th>Satuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $riwayat = mysqli_query($conn, "SELECT h.*, s.namabarang, s.stock, s.deskripsi FROM hasil_peramalan h JOIN stock s ON h.idbarang = s.idbarang ORDER BY h.tanggal_input DESC");
                                while($row = mysqli_fetch_assoc($riwayat)) {
                                    echo "<tr>
                                        <td>{$row['bulan_tahun']}</td>
                                        <td>{$row['namabarang']}</td>
                                        <td>{$row['hasil']}</td>
                                        <td>{$row['stock']}</td>
                                        <td>{$row['deskripsi']}</td>
                                
                                        
                                    </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid">
                    
                </div>
            </footer>
        </div>
    </div>
</body>
</html>
