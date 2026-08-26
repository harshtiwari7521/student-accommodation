<?php

session_start();
include "db.php";


// ===============================
// CHECK LOGIN
// ===============================

if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");
    exit();

}

$user_id = intval($_SESSION['user_id']);


// ===============================
// GET USER BOOKINGS SECURELY
// ===============================

$sql = "
    SELECT
        bookings.id,
        bookings.booking_date,
        bookings.status,
        rooms.room_name,
        rooms.location,
        rooms.rent,
        rooms.image
    FROM bookings

    INNER JOIN rooms
        ON bookings.room_id = rooms.id

    WHERE bookings.user_id = ?

    ORDER BY bookings.id DESC
";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $user_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>My Bookings</title>


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
           CONTAINER
        ================================ */

        .container {

            width: 90%;

            max-width: 1100px;

            margin: 40px auto;

        }


        h1 {

            text-align: center;

            color: #222;

            margin-bottom: 10px;

        }


        .subtitle {

            text-align: center;

            color: #666;

            margin-bottom: 35px;

        }


        /* ===============================
           BOOKING CARD
        ================================ */

        .booking-card {

            background: white;

            border-radius: 12px;

            margin-bottom: 25px;

            padding: 20px;

            display: flex;

            gap: 25px;

            box-shadow: 0 4px 15px rgba(0,0,0,0.10);

            transition: 0.3s;

        }


        .booking-card:hover {

            transform: translateY(-3px);

            box-shadow: 0 7px 20px rgba(0,0,0,0.15);

        }


        .booking-card img {

            width: 230px;

            height: 170px;

            object-fit: cover;

            border-radius: 10px;

        }


        .booking-info {

            flex: 1;

        }


        .booking-info h2 {

            margin-top: 0;

            color: #222;

        }


        .booking-info p {

            color: #555;

            margin: 10px 0;

        }


        .rent {

            color: #0d6efd !important;

            font-size: 20px;

            font-weight: bold;

        }


        /* ===============================
           STATUS
        ================================ */

        .status {

            display: inline-block;

            padding: 7px 14px;

            border-radius: 20px;

            font-weight: bold;

            font-size: 14px;

        }


        .pending {

            background: #fff3cd;

            color: #856404;

        }


        .approved {

            background: #d1e7dd;

            color: #0f5132;

        }


        .rejected {

            background: #f8d7da;

            color: #842029;

        }


        /* ===============================
           EMPTY
        ================================ */

        .empty {

            text-align: center;

            background: white;

            padding: 40px;

            border-radius: 12px;

            box-shadow: 0 4px 15px rgba(0,0,0,0.08);

        }


        .empty p {

            color: #666;

            font-size: 18px;

        }


        .rooms-btn {

            display: inline-block;

            margin-top: 15px;

            padding: 11px 20px;

            background: #0d6efd;

            color: white;

            text-decoration: none;

            border-radius: 6px;

        }


        .rooms-btn:hover {

            background: #0b5ed7;

        }


        /* ===============================
           MOBILE
        ================================ */

        @media (max-width: 700px) {

            .booking-card {

                flex-direction: column;

            }


            .booking-card img {

                width: 100%;

                height: 220px;

            }


            .container {

                width: 95%;

            }

        }

    </style>

</head>


<body>


<?php include "navbar.php"; ?>


<div class="container">


    <h1>📋 My Bookings</h1>


    <p class="subtitle">

        View and track all your room bookings

    </p>


    <?php

    if (mysqli_num_rows($result) > 0) {

        while ($booking = mysqli_fetch_assoc($result)) {

            $status = strtolower(
                trim($booking['status'])
            );


            // Image fallback

            $image = !empty($booking['image'])
                ? $booking['image']
                : 'default-room.jpg';

    ?>


        <div class="booking-card">


            <!-- ===============================
                 ROOM IMAGE
            ================================ -->

            <img
                src="images/<?php echo htmlspecialchars($image); ?>"
                alt="Room Image"
            >


            <!-- ===============================
                 BOOKING INFORMATION
            ================================ -->

            <div class="booking-info">


                <h2>

                    <?php

                    echo htmlspecialchars(
                        $booking['room_name']
                    );

                    ?>

                </h2>


                <p>

                    📍

                    <strong>
                        Location:
                    </strong>

                    <?php

                    echo htmlspecialchars(
                        $booking['location']
                    );

                    ?>

                </p>


                <p class="rent">

                    ₹<?php

                    echo htmlspecialchars(
                        $booking['rent']
                    );

                    ?>/month

                </p>


                <p>

                    📅

                    <strong>
                        Booking Date:
                    </strong>

                    <?php

                    echo htmlspecialchars(
                        $booking['booking_date']
                    );

                    ?>

                </p>


                <p>

                    <strong>
                        Status:
                    </strong>


                    <?php

                    if ($status == "pending") {

                    ?>

                        <span class="status pending">

                            🟡 Pending

                        </span>

                    <?php

                    } elseif ($status == "approved") {

                    ?>

                        <span class="status approved">

                            🟢 Approved

                        </span>

                    <?php

                    } elseif ($status == "rejected") {

                    ?>

                        <span class="status rejected">

                            🔴 Rejected

                        </span>

                    <?php

                    } else {

                    ?>

                        <span class="status">

                            <?php

                            echo htmlspecialchars(
                                $booking['status']
                            );

                            ?>

                        </span>

                    <?php

                    }

                    ?>

                </p>


            </div>

        </div>


    <?php

        }

    } else {

    ?>


        <!-- ===============================
             NO BOOKINGS
        ================================ -->

        <div class="empty">


            <h2>
                No Bookings Yet 🏠
            </h2>


            <p>

                You have not booked any room yet.

            </p>


            <a
                class="rooms-btn"
                href="rooms.php"
            >

                Explore Rooms

            </a>


        </div>


    <?php

    }

    ?>


</div>


</body>

</html>


<?php

mysqli_stmt_close($stmt);

?>