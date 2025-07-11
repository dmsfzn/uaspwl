<?php
include 'koneksi.php';

$nis = '';
$nama = '';
$kelas = '';
$alamat = '';
$tgl_masuk = '';
$is_edit = false;

// CREATE & UPDATE
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nis = $_POST['nis'];
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $alamat = $_POST['alamat'];
    $tgl_masuk = $_POST['tgl_masuk'];

    if (isset($_POST['update'])) {
        // UPDATE
        $sql = "UPDATE Siswa SET nama='$nama', kelas='$kelas', alamat='$alamat', tgl_masuk='$tgl_masuk' WHERE nis='$nis'";
    } else {
        // CREATE
        $sql = "INSERT INTO Siswa (nis, nama, kelas, alamat, tgl_masuk) VALUES ('$nis', '$nama', '$kelas', '$alamat', '$tgl_masuk')";
    }
    $koneksi->query($sql);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// DELETE
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    $nis_hapus = $_GET['nis'];
    $koneksi->query("DELETE FROM Siswa WHERE nis='$nis_hapus'");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// READ (untuk form edit)
if (isset($_GET['aksi']) && $_GET['aksi'] == 'edit') {
    $is_edit = true;
    $nis_edit = $_GET['nis'];
    $result = $koneksi->query("SELECT * FROM Siswa WHERE nis='$nis_edit'");
    $data = $result->fetch_assoc();
    if ($data) {
        $nis = $data['nis'];
        $nama = $data['nama'];
        $kelas = $data['kelas'];
        $alamat = $data['alamat'];
        $tgl_masuk = $data['tgl_masuk'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Siswa</title>
</head>
<body>

<h3><?php echo $is_edit ? 'Edit' : 'Tambah'; ?> Data Siswa</h3>
<form action="" method="POST">
    <table cellpadding="4">
        <tr>
            <td>NIS</td>
            <td><input type="text" name="nis" value="<?php echo $nis; ?>" <?php echo $is_edit ? 'readonly' : 'required'; ?>></td>
        </tr>
        <tr>
            <td>Nama</td>
            <td><input type="text" name="nama" value="<?php echo $nama; ?>" required></td>
        </tr>
        <tr>
            <td>Kelas</td>
            <td><input type="text" name="kelas" value="<?php echo $kelas; ?>"></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td><input type="text" name="alamat" value="<?php echo $alamat; ?>"></td>
        </tr>
        <tr>
            <td>Tgl Masuk</td>
            <td><input type="date" name="tgl_masuk" value="<?php echo $tgl_masuk; ?>" required></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <?php if ($is_edit): ?>
                    <button type="submit" name="update">Update</button>
                <?php else: ?>
                    <button type="submit" name="simpan">Simpan</button>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</form>

<hr>

<h3>Daftar Siswa</h3>
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>NIS</th>
            <th>Nama</th>
            <th>Kelas</th>
            <th>Alamat</th>
            <th>Tgl Masuk</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $result = $koneksi->query("SELECT * FROM Siswa ORDER BY nama ASC");
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?php echo $row['nis']; ?></td>
            <td><?php echo $row['nama']; ?></td>
            <td><?php echo $row['kelas']; ?></td>
            <td><?php echo $row['alamat']; ?></td>
            <td><?php echo $row['tgl_masuk']; ?></td>
            <td>
                <a href="?aksi=edit&nis=<?php echo $row['nis']; ?>">Edit</a> |
                <a href="?aksi=hapus&nis=<?php echo $row['nis']; ?>" onclick="return confirm('Yakin?');">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php $koneksi->close(); ?>

</body>
</html>
