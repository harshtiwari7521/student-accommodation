
<?php

include "db.php";
session_start();


// ===============================
// CHECK ROOM ID
// ===============================

if (!isset($_GET['id'])) {

    header("Location: rooms.php");
    exit();

}

$id = intval($_GET['id']);


// ===============================
// GET ROOM DETAILS SECURELY
// ===============================

$sql = "SELECT * FROM rooms WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);


// ===============================
// CHECK ROOM EXISTS
// ===============================

if (mysqli_num_rows($result) == 0) {

    echo "Room not found.";
    exit();

}

$row = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


// ===============================
// CHECK ROOM AVAILABILITY
// ===============================

$availability_sql = "
    SELECT id
    FROM bookings
    WHERE room_id = ?
    AND status = 'Approved'
    LIMIT 1
";

$availability_stmt = mysqli_prepare(
    $conn,
    $availability_sql
);

mysqli_stmt_bind_param(
    $availability_stmt,
    "i",
    $id
);

mysqli_stmt_execute(
    $availability_stmt
);

$availability_result = mysqli_stmt_get_result(
    $availability_stmt
);

$is_occupied = mysqli_num_rows(
    $availability_result
) > 0;

mysqli_stmt_close(
    $availability_stmt
);


// ===============================
// IMAGE FALLBACK
// ===============================

$image = !empty($row['image'])
    ? $row['image']
    : 'default-room.jpg';

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?php echo htmlspecialchars($row['room_name']); ?>
        - Room Details
    </title>


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
            padding: 18px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }


        .navbar h2 {
            color: white;
            margin: 0;
        }


        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-weight: bold;
        }


        .nav-links a:hover {
            text-decoration: underline;
        }


        /* ===============================
           MAIN CONTAINER
        ================================ */

        .container {
            width: 90%;
            max-width: 1000px;
            margin: 40px auto;
        }


        .room-details {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.12);
        }


        /* ===============================
           ROOM IMAGE
        ================================ */

        .room-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            display: block;
        }


        /* ===============================
           CONTENT
        ================================ */

        .content {
            padding: 30px;
        }


        .content h1 {
            margin-top: 0;
            color: #222;
            font-size: 32px;
        }


        .location {
            color: #555;
            font-size: 18px;
            margin: 15px 0;
        }


        .rent {
            color: #0d6efd;
            font-size: 25px;
            font-weight: bold;
            margin: 20px 0;
        }


        .description-title {
            color: #222;
            margin-top: 30px;
        }


        .description {
            color: #666;
            line-height: 1.7;
            font-size: 16px;
        }


        /* ===============================
           AVAILABILITY
        ================================ */

        .available {
            display: inline-block;
            background: #198754;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            font-weight: bold;
            margin-top: 10px;
        }


        .occupied {
            display: inline-block;
            background: #dc3545;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            font-weight: bold;
            margin-top: 10px;
        }


        /* ===============================
           BUTTONS
        ================================ */

        .buttons {
            margin-top: 30px;
        }


        .btn {
            display: inline-block;
            padding: 12px 22px;
            border-radius: 7px;
            text-decoration: none;
            color: white;
            font-weight: bold;
            margin-right: 10px;
            margin-bottom: 10px;
        }


        .back {
            background: #6c757d;
        }


        .book {
            background: #198754;
        }


        .btn:hover {
            opacity: 0.85;
        }


        /* ===============================
           FOOTER
        ================================ */

        footer {
            text-align: center;
            margin-top: 50px;
            padding: 20px;
            background: #222;
            color: white;
        }


        /* ===============================
           MOBILE
        ================================ */

        @media (max-width: 600px) {

            .navbar {
                padding: 15px;
                flex-direction: column;
                gap: 15px;
            }


            .nav-links {
                text-align: center;
            }


            .nav-links a {
                margin: 5px;
                font-size: 14px;
            }


            .container {
                width: 95%;
                margin: 25px auto;
            }


            .room-image {
                height: 250px;
            }


            .content {
                padding: 20px;
            }


            .content h1 {
                font-size: 26px;
            }


            .rent {
                font-size: 22px;
            }

        }

    </style>

</head>


<body>


<!-- ===============================
     NAVBAR
================================ -->

<div class="navbar">

    <h2>
        Student Accommodation
    </h2>


    <div class="nav-links">

        <a href="index.php">
            Home
        </a>


        <a href="rooms.php">
            Rooms
        </a>


        <?php if (isset($_SESSION['user_id'])) { ?>

            <a href="my_bookings.php">
                My Bookings
            </a>


            <a href="logout.php">
                Logout
            </a>

        <?php } else { ?>

            <a href="login.php">
                Login
            </a>


            <a href="register.php">
                Register
            </a>

        <?php } ?>

    </div>

</div>



<!-- ===============================
     ROOM DETAILS
================================ -->

<div class="container">

    <div class="room-details">


        <!-- ROOM IMAGE -->

        <img
            class="room-image"
            src="images/<?php echo htmlspecialchars($image); ?>"
            alt="Room Image"
        >


        <div class="content">


            <!-- ROOM NAME -->

            <h1>

                <?php
                echo htmlspecialchars(
                    $row['room_name']
                );
                ?>

            </h1>



            <!-- LOCATION -->

            <p class="location">

                📍

                <strong>
                    Location:
                </strong>

                <?php
                echo htmlspecialchars(
                    $row['location']
                );
                ?>

            </p>



            <!-- RENT -->

            <p class="rent">

                ₹<?php
                echo htmlspecialchars(
                    $row['rent']
                );
                ?>

                <span
                    style="font-size:16px; color:#666;"
                >
                    / month
                </span>

            </p>



            <!-- ===============================
                 AVAILABILITY
            ================================ -->

            <?php if ($is_occupied) { ?>

                <span class="occupied">

                    🔴 Occupied

                </span>

            <?php } else { ?>

                <span class="available">

                    🟢 Available

                </span>

            <?php } ?>


            <!-- ===============================
                 DESCRIPTION
            ================================ -->

            <h2 class="description-title">

                About this Room

            </h2>


            <p class="description">

                <?php

                echo nl2br(
                    htmlspecialchars(
                        $row['description']
                    )
                );

                ?>

            </p>



            <!-- ===============================
                 BUTTONS
            ================================ -->

            <div class="buttons">


                <!-- BACK BUTTON -->

                <a
                    class="btn back"
                    href="rooms.php"
                >

                    ← Back to Rooms

                </a>



                <!-- ===============================
                     STUDENT BOOK BUTTON
                ================================ -->

                <?php

                if (
                    isset($_SESSION['user_id']) &&
                    isset($_SESSION['role']) &&
                    $_SESSION['role'] == 'student'
                ) {

                    if (!$is_occupied) {

                ?>

                        <a
                            class="btn book"
                            href="book_room.php?id=<?php echo $row['id']; ?>"
                        >

                            🏠 Book Now

                        </a>

                <?php

                    } else {

                ?>

                        <span class="occupied">

                            🔴 This Room is Already Booked

                        </span>

                <?php

                    }

                }

                ?>


            </div>

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

