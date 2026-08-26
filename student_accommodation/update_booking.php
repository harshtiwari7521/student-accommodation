<?php
session_start();
include "db.php";

// Check admin login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Check booking ID and status
if (!isset($_GET['id']) || !isset($_GET['status'])) {
    echo "Invalid request.";
    exit();
}

$booking_id = $_GET['id'];
$status = $_GET['status'];

// Allow only these statuses
if ($status != 'Approved' && $status != 'Rejected') {
    echo "Invalid status.";
    exit();
}

// Update booking
$sql = "UPDATE bookings 
        SET status='$status' 
        WHERE id='$booking_id'";

if (mysqli_query($conn, $sql)) {
    header("Location: admin_bookings.php");
    exit();
} else {
    echo "Booking update failed: " . mysqli_error($conn);
}
?>