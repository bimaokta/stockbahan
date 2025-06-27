<?php

//koneksi ke database
$conn = mysqli_connect("localhost","root","","stokbahan");
 
//menambah barang baru
if(isset($_POST['addnewbarang'])){
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $kategori = $_POST['kategori'];

    $addtotable = mysqli_query($conn, "insert into stock (namabarang, deskripsi, stock, kategori) values('$namabarang', '$deskripsi', '$stock', '$kategori')");
    if($addtotable){
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
    }
        
};

// Menambah barang masuk
if(isset($_POST['addbarangmasuk'])){
    $barangnya = $_POST['barangnya'];
    $pegawai = $_POST['pegawai'];
    $qty = $_POST['qty'];
    $satuan = $_POST['satuan'];
    $tanggal = isset($_POST['tanggal']) && $_POST['tanggal'] ? $_POST['tanggal'] : date('Y-m-d'); // Tanggal hari ini, atau bisa dari $_POST jika diinput manual

    $cekstocksekarang = mysqli_query($conn, "select * from stock where idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    $tambahkanstocksekarangdenganquantity = $stocksekarang+$qty;

    // Tambahkan field tanggal pada query insert
    $addtomasuk = mysqli_query($conn, "insert into masuk (idbarang, pegawai, qty, satuan, tanggal) values ('$barangnya', '$pegawai', '$qty', '$satuan', '$tanggal')");
    $updatestockmasuk = mysqli_query($conn, "update stock set stock='$tambahkanstocksekarangdenganquantity' where idbarang='$barangnya'");

    if($addtomasuk&&$updatestockmasuk){
        header('location:barang masuk.php');
    } else {
        echo 'Gagal';
        header('location:barang masuk.php');
    }
};

// Menambah barang keluar
if(isset($_POST['addbarangkeluar'])){
    $barangnya = $_POST['barangnya'];
    $satuan = $_POST['satuan'];
    $qty = $_POST['qty'];
    $pegawai = $_POST['pegawai'];
    $tanggal = isset($_POST['tanggal']) && $_POST['tanggal'] ? $_POST['tanggal'] : date('Y-m-d'); // Tanggal hari ini, atau bisa dari $_POST jika diinput manual

    
    $cekstocksekarang = mysqli_query($conn, "select * from stock where idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    $tambahkanstocksekarangdenganquantity = $stocksekarang-$qty;


    $addtokeluar = mysqli_query($conn, "insert into keluar (idbarang, pegawai, qty, satuan, tanggal) values ('$barangnya', '$pegawai', '$qty', '$satuan', '$tanggal')");
    $updatestockmasuk = mysqli_query($conn, "update stock set stock='$tambahkanstocksekarangdenganquantity' where idbarang='$barangnya'");

    if($addtokeluar&&$updatestockmasuk){
        header('location:barang keluar.php');
    } else {
        echo 'Gagal';
        header('location:barang keluar.php');
    }
};

// metode WMA
function getBahanList() {
    global $conn;
    $query = mysqli_query($conn, "SELECT * FROM stock");
    $result = [];
    while ($row = mysqli_fetch_assoc($query)) {
        $result[] = $row;
    }
    return $result;
}

function getDataKeluarWMA($idbarang) {
    global $conn;
    $query = mysqli_query($conn, "
        SELECT DATE_FORMAT(tanggal, '%m-%Y') AS bulan_tahun, SUM(qty) AS jumlah
        FROM keluar
        WHERE idbarang = '$idbarang'
        GROUP BY YEAR(tanggal), MONTH(tanggal)
        ORDER BY tanggal DESC
        LIMIT 3
    ");
    $data = [];
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }
    return array_reverse($data); // urut dari terlama ke terbaru
}

function hitungWMA($data, $weights = [0.5, 0.3, 0.2]) {
    if (count($data) < 3) return null;

    $jumlah = 0;
    for ($i = 0; $i < 3; $i++) {
        $jumlah += $data[$i]['jumlah'] * $weights[$i];
    }
    return round($jumlah);
}

//simpan hasil
function simpanHasilPeramalan($conn, $idbarang, $bulanTahun, $hasil) {
    $stmt = $conn->prepare("INSERT INTO hasil_peramalan (idbarang, bulan_tahun, hasil) VALUES (?, ?, ?)");
    $stmt->bind_param("isd", $idbarang, $bulanTahun, $hasil);
    return $stmt->execute();
}
  

?>