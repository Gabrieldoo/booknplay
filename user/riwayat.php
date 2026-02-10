<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$query = "SELECT bookings.*, courts.nama_lapangan 
          FROM bookings 
          JOIN courts ON bookings.court_id = courts.id
          WHERE bookings.user_id='$user_id'
          ORDER BY bookings.tanggal DESC";

$result = mysqli_query($conn, $query);
?>

<h2>Riwayat Booking Saya</h2>

<table border="1" cellpadding="5">
  <tr>
    <th>No</th>
    <th>Lapangan</th>
    <th>Tanggal</th>
    <th>Jam</th>
    <th>Status</th>
  </tr>

  <?php $no=1; while ($b = mysqli_fetch_assoc($result)) { ?>
  <tr>
    <td><?php echo $no++; ?></td>
    <td><?php echo $b['nama_lapangan']; ?></td>
    <td><?php echo $b['tanggal']; ?></td>
    <td><?php echo $b['jam_mulai']; ?> - <?php echo $b['jam_selesai']; ?></td>
    <td><?php echo $b['status']; ?></td>
  </tr>
  <?php } ?>
</table>

<br>
<a href="dashboard.php">Kembali ke Dashboard</a>
