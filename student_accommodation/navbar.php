
<?php

// Start session only if it is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<style>

    * {
        box-sizing: border-box;
    }

    .navbar {
        background: #0d6efd;
        padding: 16px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    .logo {
        color: white;
        font-size: 22px;
        font-weight: bold;
        text-decoration: none;
    }

    .nav-links {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .nav-links a {
        color: white;
        text-decoration: none;
        font-weight: bold;
        font-size: 15px;
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
        background: #bb2d3b;
    }

    .admin-link {
        background: #ffc107;
        color: #000 !important;
        padding: 8px 12px;
        border-radius: 6px;
    }

    .admin-link:hover {
        text-decoration: none !important;
        background: #ffca2c;
    }

    @media (max-width: 700px) {

        .navbar {
            flex-direction: column;
            gap: 15px;
            padding: 15px;
        }

        .nav-links {
            flex-wrap: wrap;
            justify-content: center;
            gap: 12px;
        }

        .nav-links a {
            font-size: 14px;
        }

        .logo {
            font-size: 19px;
        }
    }

</style>


<nav class="navbar">

    <!-- LOGO -->

    <a href="index.php" class="logo">
        🏠 Student Accommodation
    </a>


    <div class="nav-links">

        <!-- COMMON LINKS -->

        <a href="index.php">
            Home
        </a>

        <a href="rooms.php">
            Rooms
        </a>


        <?php if (isset($_SESSION['user_id'])): ?>


            <!-- =========================
                 STUDENT NAVBAR
            ========================== -->

            <?php if (
                isset($_SESSION['role']) &&
                $_SESSION['role'] == 'student'
            ): ?>

                <a href="my_bookings.php">
                    📋 My Bookings
                </a>

            <?php endif; ?>


            <!-- =========================
                 ADMIN NAVBAR
            ========================== -->

            <?php if (
                isset($_SESSION['role']) &&
                $_SESSION['role'] == 'admin'
            ): ?>

                <a
                    href="admin_dashboard.php"
                    class="admin-link"
                >
                    ⚙️ Admin Dashboard
                </a>

                <a href="admin_bookings.php">
                    📋 Manage Bookings
                </a>

            <?php endif; ?>


            <!-- LOGOUT -->

            <a
                href="logout.php"
                class="logout"
            >
                Logout
            </a>


        <?php else: ?>


            <!-- =========================
                 LOGGED OUT USER
            ========================== -->

            <a href="login.php">
                Login
            </a>

            <a href="register.php">
                Register
            </a>


        <?php endif; ?>

    </div>

</nav>


