<?php
include 'koneksi.php';

// Cek jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $id_pelanggaran = $_POST['id_pelanggaran'];
    $nis = $_POST['nis'];
    $jenis_pelanggaran = $_POST['jenis_pelanggaran'];
    $tanggal = $_POST['tanggal'];
    $poin = 0;

    // 1. Tentukan poin
    switch ($jenis_pelanggaran) {
        case 'Tidak Berseragam Lengkap': $poin = 5; break;
        case 'Terlambat': $poin = 3; break;
        case 'Merokok': $poin = 10; break;
        case 'Keluar Tanpa Izin': $poin = 7; break;
    }

    // 2. Simpan data ke database
    $sql_insert = "INSERT INTO Pelanggaran VALUES ('$id_pelanggaran', '$nis', '$tanggal', '$jenis_pelanggaran', $poin)";
    
    if ($koneksi->query($sql_insert)) {
        echo "Data pelanggaran berhasil disimpan.<br>";

        // 3. Cek total poin bulan ini
        $bulan = date('m', strtotime($tanggal));
        $tahun = date('Y', strtotime($tanggal));

        $sql_sum = "SELECT SUM(poin) AS total FROM Pelanggaran WHERE nis='$nis' AND MONTH(tanggal)=$bulan AND YEAR(tanggal)=$tahun";
        $result = $koneksi->query($sql_sum);
        $data_poin = $result->fetch_assoc();
        $total_poin = $data_poin['total'];

        if ($total_poin > 15) {
            echo "<b><span style='color:red;'>Peringatan:</span></b> Total poin siswa bulan ini adalah $total_poin (lebih dari 15).<br>";
        }
    } else {
        echo "Gagal menyimpan data: " . $koneksi->error . "<br>";
    }
    echo "<hr>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Input Pelanggaran</title>
</head>
<body>

<h2>Form Input Pelanggaran</h2>
<form action="" method="POST">
    <table>
        <tr>
            <td>ID Pelanggaran</td>
            <td>: <input type="text" name="id_pelanggaran" required></td>
        </tr>
        <tr>
            <td>NIS</td>
            <td>: <input type="text" name="nis" required></td>
        </tr>
        <tr>
            <td>Jenis Pelanggaran</td>
            <td>: 
                <select name="jenis_pelanggaran" required>
                    <option>Tidak Berseragam Lengkap</option>
                    <option>Terlambat</option>
                    <option>Merokok</option>
                    <option>Keluar Tanpa Izin</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: <input type="date" name="tanggal" required></td>
        </tr>
        <tr>
            <td colspan="2"><button type="submit">Simpan</button></td>
        </tr>
    </table>
</form>

</body>
</html>
<?php $koneksi->close(); ?>
