<?php
include '../config/db.php';

if (isset($_POST['daftar'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO users (nama, email, password) VALUES ('$nama', '$email', '$password')";
    mysqli_query($conn, $query);

    header("Location: login.php");
}
?>

<form method="post">
  <h2>Register BookNPlay</h2>
  <input type="text" name="nama" placeholder="Nama" required><br>
  <input type="email" name="email" placeholder="Email" required><br>
  <input type="password" name="password" placeholder="Password" required><br>
  <button name="daftar">Daftar</button>
</form>