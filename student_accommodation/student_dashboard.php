<?php
include "db.php";
session_start();

// Check student login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get student details
$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

// Get total bookings
$booking_sql = "SELECT COUNT(*) AS total FROM bookings WHERE user_id='$user_id'";
$booking_result = mysqli_query($conn, $booking_sql);
$booking_data = mysqli_fetch_assoc($booking_result);

$total_bookings = $booking_data['total'];

// Get booking status counts
$pending_sql = "SELECT COUNT(*) AS total FROM bookings 
                WHERE user_id='$user_id' AND status='Pending'";
$pending_result = mysqli_query($conn, $pending_sql);
$pending_data = mysqli_fetch_assoc($pending_result);

$pending_bookings = $pending_data['total'];

$approved_sql = "SELECT COUNT(*) AS total FROM bookings 
                 WHERE user_id='$user_id' AND status='Approved'";
$approved_result = mysqli_query($conn, $approved_sql);
$approved_data = mysqli_fetch_assoc($approved_result);

$approved_bookings = $approved_data['total'];

$rejected_sql = "SELECT COUNT(*) AS total FROM bookings 
                 WHERE user_id='$user_id' AND status='Rejected'";
$rejected_result = mysqli_query($conn, $rejected_sql);
$rejected_data = mysqli_fetch_assoc($rejected_result);

$rejected_bookings = $rejected_data['total'];
?>

<!DOCTYPE html>
<html>

<head>

    <title>Student Dashboard</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f7fb;
        }

        /* Navbar */

        .navbar {
            background: #0d6efd;
            padding: 18px 50px;

            display: flex;
            justify-content: space-between;
            align-items: center;

            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .logo {
            color: white;
            font-size: 22px;
            font-weight: bold;
        }

        .nav-links {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .nav-links a:hover {
            text-decoration: underline;
        }

        .logout {
            background: #dc3545;
            padding: 8px 14px;
            border-radius: 6px;
        }

        .logout:hover {
            text-decoration: none !important;
        }

        /* Container */

        .container {
            width: 90%;
            max-width: 1150px;
            margin: 35px auto;
        }

        /* Welcome */

        .welcome {
            background: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 25px;

            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .welcome h1 {
            margin-top: 0;
            color: #222;
        }

        .welcome p {
            color: #666;
        }

        /* Stats */

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;

            text-align: center;

            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .stat-card h2 {
            margin: 5px 0;
            font-size: 32px;
            color: #0d6efd;
        }

        .stat-card p {
            margin: 0;
            color: #666;
        }

        /* Main Cards */

        .cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;

            box-shadow: 0 4px 15px rgba(0,0,0,0.08);

            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-4px);
        }

        .card h2 {
            margin-top: 0;
        }

        .card p {
            color: #666;
            line-height: 1.5;
        }

        .btn {
            display: inline-block;

            background: #0d6efd;
            color: white;

            text-decoration: none;

            padding: 10px 18px;

            border-radius: 6px;

            margin-top: 10px;

            font-weight: bold;
        }

        .btn:hover {
            background: #0b5ed7;
        }

        /* Profile */

        .profile {
            background: white;

            padding: 30px;

            border-radius: 12px;

            margin-top: 30px;

            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .profile h2 {
            margin-top: 0;
        }

        .profile p {
            color: #555;
        }

        /* Footer */

        footer {
            margin-top: 50px;

            padding: 20px;

            text-align: center;

            background: #222;

            color: white;
        }

        /* Responsive */

        @media (max-width: 900px) {

            .stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .cards {
                grid-template-columns: 1fr;
            }

        }

        @media (max-width: 600px) {

            .navbar {
                padding: 15px 20px;
                flex-direction: column;
                gap: 12px;
            }

            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
            }

            .stats {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>

<body>


<!-- NAVBAR -->

<nav class="navbar">

    <div class="logo">
        🏠 Student Accommodation
    </div>

    <div class="nav-links">

        <a href="index.php">Home</a>

        <a href="rooms.php">Rooms</a>

        <a href="my_bookings.php">My Bookings</a>

        <a href="logout.php" class="logout">Logout</a>

    </div>

</nav>


<!-- MAIN -->

<div class="container">


    <!-- Welcome -->

    <div class="welcome">

        <h1>
            Welcome, <?php echo htmlspecialchars($user['fullname']); ?>! 👋
        </h1>

        <p>
            Welcome to your Student Accommodation Dashboard.
            Find your perfect room and manage your bookings easily.
        </p>

    </div>


    <!-- STATISTICS -->

    <div class="stats">


        <div class="stat-card">

            <h2>
                <?php echo $total_bookings; ?>
            </h2>

            <p>Total Bookings</p>

        </div>


        <div class="stat-card">

            <h2>
                <?php echo $pending_bookings; ?>
            </h2>

            <p>Pending</p>

        </div>


        <div class="stat-card">

            <h2>
                <?php echo $approved_bookings; ?>
            </h2>

            <p>Approved</p>

        </div>


        <div class="stat-card">

            <h2>
                <?php echo $rejected_bookings; ?>
            </h2>

            <p>Rejected</p>

        </div>


    </div>


    <!-- OPTIONS -->

    <div class="cards">


        <div class="card">

            <h2>🏠 Find Rooms</h2>

            <p>
                Explore available PGs, hostels and rooms.
                Find a suitable accommodation according to your needs.
            </p>

            <a href="rooms.php" class="btn">
                View Rooms
            </a>

        </div>


        <div class="card">

            <h2>📋 My Bookings</h2>

            <p>
                Check your booking history and see whether
                your booking is Pending, Approved or Rejected.
            </p>

            <a href="my_bookings.php" class="btn">
                View Bookings
            </a>

        </div>


        <div class="card">

            <h2>👤 My Profile</h2>

            <p>
                View your registered account information.
            </p>

            <a href="#profile" class="btn">
                View Profile
            </a>

        </div>


    </div>


    <!-- PROFILE -->

    <div class="profile" id="profile">

        <h2>👤 My Profile</h2>

        <p>
            <strong>Name:</strong>
            <?php echo htmlspecialchars($user['fullname']); ?>
        </p>

        <p>
            <strong>Email:</strong>
            <?php echo htmlspecialchars($user['email']); ?>
        </p>

        <p>
            <strong>Phone:</strong>
            <?php echo htmlspecialchars($user['phone']); ?>
        </p>

        <p>
            <strong>Role:</strong>
            Student
        </p>

    </div>


</div>


<!-- FOOTER -->

<footer>

    © 2026 Student Accommodation Website

</footer>


</body>

</html>