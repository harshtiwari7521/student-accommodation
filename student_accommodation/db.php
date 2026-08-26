<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "student_accommodation";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

// echo "Database Connected Successfully!";
?>