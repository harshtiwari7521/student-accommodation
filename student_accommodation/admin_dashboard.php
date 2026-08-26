```php
<?php
session_start();
include "db.php";

// ===============================
// CHECK ADMIN LOGIN
// ===============================

if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['role']) ||
    $_SESSION['role'] != 'admin'
) {
    header("Location: login.php");
    exit();
}


// ===============================
// DASHBOARD STATISTICS
// ===============================

// Total Students
$sql = "SELECT COUNT(*) AS total FROM users WHERE role = 'student'";
$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($result);
$total_students = $data['total'];


// Total Rooms
$sql = "SELECT COUNT(*) AS total FROM rooms";
$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($result);
$total_rooms = $data['total'];


// Total Bookings
$sql = "SELECT COUNT(*) AS total FROM bookings";
$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($result);
$total_bookings = $data['total'];


// Pending Bookings
$sql = "SELECT COUNT(*) AS total FROM bookings WHERE status = 'Pending'";
$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($result);
$pending = $data['total'];


// Approved Bookings
$sql = "SELECT COUNT(*) AS total FROM bookings WHERE status = 'Approved'";
$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($result);
$approved = $data['total'];


// Rejected Bookings
$sql = "SELECT COUNT(*) AS total FROM bookings WHERE status = 'Rejected'";
$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($result);
$rejected = $data['total'];

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Admin Dashboard - Student Accommodation</title>


    <style>

        * {
            box-sizing: border-box;
        }


        body {

            margin: 0;

            font-family: Arial, sans-serif;

            background: #f4f7fb;

        }


        /* ===============================
           NAVBAR
        ================================ */

        .navbar {

            background: #0d6efd;

            padding: 18px 40px;

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


        /* ===============================
           CONTAINER
        ================================ */

        .container {

            width: 90%;

            max-width: 1200px;

            margin: 35px auto;

        }


        .welcome {

            background: white;

            padding: 25px;

            border-radius: 12px;

            box-shadow: 0 4px 15px rgba(0,0,0,0.08);

            margin-bottom: 30px;

        }


        .welcome h1 {

            margin-top: 0;

            color: #222;

        }


        .welcome p {

            color: #666;

        }


        /* ===============================
           STATISTICS
        ================================ */

        .dashboard {

            display: grid;

            grid-template-columns: repeat(3, 1fr);

            gap: 20px;

        }


        .card {

            background: white;

            padding: 25px;

            text-align: center;

            border-radius: 12px;

            box-shadow: 0 4px 15px rgba(0,0,0,0.08);

            transition: 0.3s;

        }


        .card:hover {

            transform: translateY(-4px);

        }


        .card h3 {

            margin-top: 0;

            color: #555;

        }


        .card p {

            font-size: 32px;

            font-weight: bold;

            color: #0d6efd;

            margin: 10px 0 0;

        }


        /* ===============================
           OPTIONS
        ================================ */

        .options {

            display: grid;

            grid-template-columns: repeat(3, 1fr);

            gap: 20px;

            margin-top: 30px;

        }


        .option-card {

            background: white;

            padding: 25px;

            border-radius: 12px;

            text-align: center;

            box-shadow: 0 4px 15px rgba(0,0,0,0.08);

        }


        .option-card h3 {

            margin-top: 0;

        }


        .option-card p {

            color: #666;

            line-height: 1.5;

        }


        .btn {

            display: inline-block;

            margin-top: 10px;

            padding: 10px 18px;

            background: #0d6efd;

            color: white;

            text-decoration: none;

            border-radius: 6px;

            font-weight: bold;

        }


        .btn:hover {

            background: #0b5ed7;

        }


        /* ===============================
           FOOTER
        ================================ */

        footer {

            margin-top: 50px;

            padding: 20px;

            text-align: center;

            background: #222;

            color: white;

        }


        /* ===============================
           MOBILE
        ================================ */

        @media (max-width: 800px) {

            .dashboard {

                grid-template-columns: repeat(2, 1fr);

            }


            .options {

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


            .dashboard {

                grid-template-columns: 1fr;

            }


            .container {

                width: 95%;

            }

        }

    </style>

</head>


<body>


<!-- ===============================
     NAVBAR
================================ -->

<nav class="navbar">

    <div class="logo">

        🏠 Student Accommodation

    </div>


    <div class="nav-links">

        <a href="admin_dashboard.php">
            Dashboard
        </a>


        <a href="add_room.php">
            Add Room
        </a>


        <a href="admin_bookings.php">
            Bookings
        </a>


        <a href="rooms.php">
            Rooms
        </a>


        <a
            href="logout.php"
            class="logout"
        >
            Logout
        </a>

    </div>

</nav>


<!-- ===============================
     MAIN
================================ -->

<div class="container">


    <!-- WELCOME -->

    <div class="welcome">

        <h1>
            Welcome, Admin! 👋
        </h1>

        <p>
            Manage students, rooms and bookings
            from your admin dashboard.
        </p>

    </div>


    <!-- ===============================
         STATISTICS
    ================================ -->

    <h2>
        📊 Dashboard Summary
    </h2>


    <div class="dashboard">


        <div class="card">

            <h3>
                👨‍🎓 Total Students
            </h3>

            <p>
                <?php echo $total_students; ?>
            </p>

        </div>


        <div class="card">

            <h3>
                🏠 Total Rooms
            </h3>

            <p>
                <?php echo $total_rooms; ?>
            </p>

        </div>


        <div class="card">

            <h3>
                📋 Total Bookings
            </h3>

            <p>
                <?php echo $total_bookings; ?>
            </p>

        </div>


        <div class="card">

            <h3>
                🟡 Pending
            </h3>

            <p>
                <?php echo $pending; ?>
            </p>

        </div>


        <div class="card">

            <h3>
                🟢 Approved
            </h3>

            <p>
                <?php echo $approved; ?>
            </p>

        </div>


        <div class="card">

            <h3>
                🔴 Rejected
            </h3>

            <p>
                <?php echo $rejected; ?>
            </p>

        </div>

    </div>


    <!-- ===============================
         ADMIN OPTIONS
    ================================ -->

    <h2 style="margin-top:40px;">
        ⚙️ Admin Options
    </h2>


    <div class="options">


        <div class="option-card">

            <h3>
                🏠 Add Room
            </h3>

            <p>
                Add a new PG, hostel or room
                to the accommodation system.
            </p>

            <a
                href="add_room.php"
                class="btn"
            >
                Add Room
            </a>

        </div>


        <div class="option-card">

            <h3>
                📋 Manage Bookings
            </h3>

            <p>
                Approve or reject student
                room booking requests.
            </p>

            <a
                href="admin_bookings.php"
                class="btn"
            >
                Manage Bookings
            </a>

        </div>


        <div class="option-card">

            <h3>
                🏘️ Manage Rooms
            </h3>

            <p>
                View, edit and delete rooms
                from the accommodation system.
            </p>

            <a
                href="rooms.php"
                class="btn"
            >
                View Rooms
            </a>

        </div>


        <div class="option-card">

            <h3>
                🌐 Website
            </h3>

            <p>
                Visit the main student
                accommodation website.
            </p>

            <a
                href="index.php"
                class="btn"
            >
                Visit Website
            </a>

        </div>


        <div class="option-card">

            <h3>
                🚪 Logout
            </h3>

            <p>
                Securely logout from
                the admin account.
            </p>

            <a
                href="logout.php"
                class="btn"
            >
                Logout
            </a>

        </div>


    </div>


</div>


<!-- ===============================
     FOOTER
================================ -->

<footer>

    © 2026 Student Accommodation Website

</footer>


</body>

</html>
```
