<?php
session_start();
include '../config/db.php';

// pastikan sudah login & admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// ambil semua booking
$query = "SELECT bookings.*, users.nama, courts.nama_lapangan 
          FROM bookings 
          JOIN users ON bookings.user_id = users.id
          JOIN courts ON bookings.court_id = courts.id
          ORDER BY bookings.tanggal DESC";

$result = mysqli_query($conn, $query);
?>

<h2>Dashboard Admin - Data Booking</h2>

<table border="1" cellpadding="5">
  <tr>
    <th>No</th>
    <th>Nama User</th>
    <th>Lapangan</th>
    <th>Tanggal</th>
    <th>Jam</th>
    <th>Status</th>
    <th>Aksi</th>
  </tr>

  <?php $no=1; while ($b = mysqli_fetch_assoc($result)) { ?>
  <tr>
    <td><?php echo $no++; ?></td>
    <td><?php echo $b['nama']; ?></td>
    <td><?php echo $b['nama_lapangan']; ?></td>
    <td><?php echo $b['tanggal']; ?></td>
    <td><?php echo $b['jam_mulai']; ?> - <?php echo $b['jam_selesai']; ?></td>
    <td><?php echo $b['status']; ?></td>
    <td>
      <a href="update_status.php?id=<?php echo $b['id']; ?>&status=approved">Approve</a> |
      <a href="update_status.php?id=<?php echo $b['id']; ?>&status=rejected">Reject</a>
    </td>
  </tr>
  <?php } ?>
</table>


<br>
<a href="../logout.php">Logout</a>
