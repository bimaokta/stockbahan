<?php
require 'function.php';

// Ambil semua data barang dan kategorinya
$allBarang = [];
$ambilsemuadatanya = mysqli_query($conn, "SELECT * FROM stock");
while($fetcharray = mysqli_fetch_array($ambilsemuadatanya)){
    $allBarang[] = [
        'id' => $fetcharray['idbarang'],
        'nama' => $fetcharray['namabarang'],
        'kategori' => $fetcharray['kategori']
    ];
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
        <title>Stok Keluar</title>
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
                        <h1 class="mt-4">Stok Keluar</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">KEDAI SOEGENG RAWOEH</li>
                        </ol>

                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table mr-1"></i>
                                Stok Bahan Baku
                            </div>
                            <!-- Button to Open the Modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                Input Data Stok Keluar
                            </button>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Bahan Baku</th>
                                                <th>Kategori</th>
                                                <th>Jumlah</th>
                                                <th>Satuan</th>
                                                
                                            </tr>
                                        </thead>
                                    
                                        <tbody>
                                            
                                            <?php
                                            $ambilsemuadatastock = mysqli_query($conn,"select * from keluar k, stock s where s.idbarang = k.idbarang");
                                            while($data=mysqli_fetch_array($ambilsemuadatastock)){
                                                $tanggal = $data['tanggal'];
                                                $namabarang = $data['namabarang'];
                                                $kategori = $data['kategori'];
                                                $qty = $data['qty'];
                                                $satuan = $data['satuan'];
                                            ?>
                                            <tr>
                                                <td><?=$tanggal;?></td>
                                                <td><?=$namabarang;?></td>
                                                <td><?=$data['kategori'];?></td>
                                                <td><?=$qty;?></td>
                                                <td><?=$satuan;?></td>
                                                
                                            </tr>
                                            <?php
                                            }
                                            ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                       
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/datatables-demo.js"></script>
    </body>
<!-- The Modal -->
  <div class="modal fade" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
            <!-- Modal Header -->
            <div class="modal-header">
            <h4 class="modal-title">Tambah Stok Keluar</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
        
            <!-- Modal body -->
            <form method="post">
            <div class="modal-body">
            <select name="kategori" class="form-control" id="kategoriSelect">
                <option value="">-- Pilih Kategori --</option>
                <?php
                // Ambil semua kategori unik
                $kategoriList = [];
                foreach($allBarang as $barang){
                    $kategoriList[$barang['kategori']] = true;
                }
                foreach(array_keys($kategoriList) as $kategori){
                    echo "<option value=\"$kategori\">$kategori</option>";
                }
                ?>
            </select>
            <br>
            <select name="barangnya" class="form-control" id="barangSelect">
                <option value="">-- Pilih Bahan --</option>
                <!-- Opsi barang akan diisi oleh JS -->
            </select>
            <script>
                // Data barang dari PHP ke JS
                const allBarang = <?php echo json_encode($allBarang); ?>;
                const barangSelect = document.getElementById('barangSelect');
                const kategoriSelect = document.getElementById('kategoriSelect');

                kategoriSelect.addEventListener('change', function() {
                    const kategori = this.value;
                    // Kosongkan opsi barang
                    barangSelect.innerHTML = '<option value="">-- Pilih Bahan --</option>';
                    // Filter dan tambahkan opsi barang sesuai kategori
                    allBarang.forEach(function(barang) {
                        if (barang.kategori === kategori) {
                            const opt = document.createElement('option');
                            opt.value = barang.id;
                            opt.textContent = barang.nama;
                            barangSelect.appendChild(opt);
                        }
                    });
                });
            </script>
            <br>
            <input type="date" name="tanggal" class="form-control" placeholder="Tanggal" required>
            <br>
            <input type="number" name="qty" class="form-control" placeholder="Quantity" required>
            <br>
            <select name="satuan" class="form-control" required>
                <option value="kg">kg</option>
                <option value="kotak">kotak</option>
            </select>
            <br>
            <button type="submit" class="btn btn-primary" name="addbarangkeluar">Submit</button>
            </form>
        
        </div>
        </div>
  </div>

</html>
