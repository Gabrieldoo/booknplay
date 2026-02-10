<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
?>

<h2>Selamat datang, <?php echo $_SESSION['nama']; ?></h2>

<ul>
  <li><a href="booking.php">Booking Lapangan</a></li>
  <li><a href="riwayat.php">Riwayat Booking</a></li>
  <li><a href="chatbot.php">Chatbot Jadwal</a></li>
  <li><a href="../logout.php">Logout</a></li>
</ul>

<p>Ini dashboard user.</p>
<a href="../logout.php">Logout</a>

