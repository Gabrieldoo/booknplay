<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$id = $_GET['id'];
$status = $_GET['status'];

mysqli_query($conn, "UPDATE bookings SET status='$status' WHERE id='$id'");

header("Location: dashboard.php");
