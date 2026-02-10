<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// ambil data lapangan
$courts = mysqli_query($conn, "SELECT * FROM courts");

if (isset($_POST['booking'])) {
    $user_id = $_SESSION['user_id'];
    $court_id = $_POST['court_id'];
    $tanggal = $_POST['tanggal'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];

    // cek bentrok jadwal
    $cek = "SELECT * FROM bookings 
            WHERE court_id='$court_id' 
            AND tanggal='$tanggal' 
            AND jam_mulai < '$jam_selesai' 
            AND jam_selesai > '$jam_mulai'";

    $result = mysqli_query($conn, $cek);

    if (mysqli_num_rows($result) > 0) {
        $pesan = "Jadwal sudah dibooking, pilih waktu lain.";
    } else {
        $simpan = "INSERT INTO bookings (user_id, court_id, tanggal, jam_mulai, jam_selesai, status)
                   VALUES ('$user_id', '$court_id', '$tanggal', '$jam_mulai', '$jam_selesai', 'pending')";
        mysqli_query($conn, $simpan);
        $pesan = "Booking berhasil disimpan.";
    }
}
?>

<h2>Booking Lapangan</h2>

<?php if (isset($pesan)) echo "<p>$pesan</p>"; ?>

<form method="post">
  <label>Pilih Lapangan</label><br>
  <select name="court_id" required>
    <option value="">-- Pilih Lapangan --</option>
    <?php while ($c = mysqli_fetch_assoc($courts)) { ?>
      <option value="<?php echo $c['id']; ?>">
        <?php echo $c['nama_lapangan']; ?> 
        (<?php echo $c['jenis_olahraga']; ?> - Rp<?php echo $c['harga_per_jam']; ?>/jam)
      </option>
    <?php } ?>
  </select><br><br>

  <label>Tanggal</label><br>
  <input type="date" name="tanggal" required><br><br>

  <label>Jam Mulai</label><br>
  <input type="time" name="jam_mulai" required><br><br>

  <label>Jam Selesai</label><br>
  <input type="time" name="jam_selesai" required><br><br>

  <button name="booking">Booking</button>
</form>

<br>
<a href="dashboard.php">Kembali ke Dashboard</a>
