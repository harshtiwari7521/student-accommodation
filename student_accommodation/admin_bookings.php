<?php

session_start();
include "db.php";


// =====================================
// CHECK ADMIN LOGIN
// =====================================

if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'admin'
) {
    header("Location: login.php");
    exit();
}


// =====================================
// APPROVE BOOKING
// =====================================

if (isset($_GET['approve'])) {

    $booking_id = intval($_GET['approve']);

    if ($booking_id <= 0) {
        echo "<script>
                alert('Invalid booking ID.');
                window.location='admin_bookings.php';
              </script>";
        exit();
    }


    // Get pending booking
    $sql = "
        SELECT room_id
        FROM bookings
        WHERE id = ?
        AND status = 'Pending'
        LIMIT 1
    ";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $booking_id
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {

        mysqli_stmt_close($stmt);

        echo "<script>
                alert('Booking not found or it is no longer pending.');
                window.location='admin_bookings.php';
              </script>";
        exit();
    }


    $booking = mysqli_fetch_assoc($result);

    $room_id = intval($booking['room_id']);

    mysqli_stmt_close($stmt);


    // =====================================
    // CHECK ROOM ALREADY OCCUPIED
    // =====================================

    $room_sql = "
        SELECT id
        FROM bookings
        WHERE room_id = ?
        AND status = 'Approved'
        LIMIT 1
    ";

    $room_stmt = mysqli_prepare($conn, $room_sql);

    mysqli_stmt_bind_param(
        $room_stmt,
        "i",
        $room_id
    );

    mysqli_stmt_execute($room_stmt);

    $room_result = mysqli_stmt_get_result($room_stmt);

    if (mysqli_num_rows($room_result) > 0) {

        mysqli_stmt_close($room_stmt);

        echo "<script>
                alert('This room is already occupied.');
                window.location='admin_bookings.php';
              </script>";
        exit();
    }

    mysqli_stmt_close($room_stmt);


    // =====================================
    // APPROVE BOOKING
    // =====================================

    $update_sql = "
        UPDATE bookings
        SET status = 'Approved'
        WHERE id = ?
        AND status = 'Pending'
    ";

    $update_stmt = mysqli_prepare(
        $conn,
        $update_sql
    );

    mysqli_stmt_bind_param(
        $update_stmt,
        "i",
        $booking_id
    );

    mysqli_stmt_execute($update_stmt);

    if (mysqli_stmt_affected_rows($update_stmt) > 0) {

        mysqli_stmt_close($update_stmt);

        echo "<script>
                alert('Booking Approved Successfully!');
                window.location='admin_bookings.php';
              </script>";
        exit();

    } else {

        mysqli_stmt_close($update_stmt);

        echo "<script>
                alert('Booking could not be approved.');
                window.location='admin_bookings.php';
              </script>";
        exit();
    }
}


// =====================================
// REJECT BOOKING
// =====================================

if (isset($_GET['reject'])) {

    $booking_id = intval($_GET['reject']);

    if ($booking_id <= 0) {

        echo "<script>
                alert('Invalid booking ID.');
                window.location='admin_bookings.php';
              </script>";
        exit();
    }


    $sql = "
        UPDATE bookings
        SET status = 'Rejected'
        WHERE id = ?
        AND status = 'Pending'
    ";

    $stmt = mysqli_prepare(
        $conn,
        $sql
    );

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $booking_id
    );

    mysqli_stmt_execute($stmt);


    if (mysqli_stmt_affected_rows($stmt) > 0) {

        mysqli_stmt_close($stmt);

        echo "<script>
                alert('Booking Rejected Successfully!');
                window.location='admin_bookings.php';
              </script>";
        exit();

    } else {

        mysqli_stmt_close($stmt);

        echo "<script>
                alert('Booking not found or it is no longer pending.');
                window.location='admin_bookings.php';
              </script>";
        exit();
    }
}


// =====================================
// GET ALL BOOKINGS
// =====================================

$sql = "
    SELECT
        bookings.id,
        users.fullname,
        users.email,
        rooms.room_name,
        rooms.location,
        rooms.rent,
        bookings.booking_date,
        bookings.status

    FROM bookings

    INNER JOIN users
        ON bookings.user_id = users.id

    INNER JOIN rooms
        ON bookings.room_id = rooms.id

    ORDER BY bookings.id DESC
";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Manage Bookings</title>


    <style>

        * {
            box-sizing: border-box;
        }


        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f7fb;
        }


        /* NAVBAR */

        .navbar {
            background: #0d6efd;
            color: white;
            padding: 18px 40px;

            display: flex;
            justify-content: space-between;
            align-items: center;
        }


        .navbar h2 {
            margin: 0;
        }


        .back {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }


        .back:hover {
            text-decoration: underline;
        }


        /* CONTAINER */

        .container {
            width: 95%;
            max-width: 1300px;
            margin: 35px auto;
        }


        h1 {
            text-align: center;
            color: #222;
        }


        /* TABLE */

        .table-container {
            background: white;
            padding: 20px;
            border-radius: 12px;

            box-shadow:
                0 4px 15px rgba(0,0,0,0.10);

            overflow-x: auto;
        }


        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 950px;
        }


        th {
            background: #0d6efd;
            color: white;
            padding: 12px;
            text-align: left;
        }


        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }


        tr:hover {
            background: #f8f9fa;
        }


        /* STATUS */

        .status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 5px;
            font-weight: bold;
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


        /* BUTTONS */

        .btn {
            display: inline-block;

            padding: 7px 12px;

            border-radius: 5px;

            text-decoration: none;

            color: white;

            font-size: 13px;

            margin: 2px;
        }


        .approve {
            background: #198754;
        }


        .reject {
            background: #dc3545;
        }


        .btn:hover {
            opacity: 0.85;
        }


        .no-action {
            color: #777;
        }


        .no-bookings {
            text-align: center;
            padding: 30px;
            color: #777;
            font-size: 18px;
        }


        /* MOBILE */

        @media (max-width: 600px) {

            .navbar {
                padding: 15px;
            }

            .navbar h2 {
                font-size: 18px;
            }

            .container {
                width: 95%;
            }

        }

    </style>

</head>


<body>


<!-- NAVBAR -->

<div class="navbar">

    <h2>
        Admin - Manage Bookings
    </h2>


    <a
        class="back"
        href="admin_dashboard.php"
    >
        ← Back to Dashboard
    </a>

</div>


<!-- MAIN -->

<div class="container">

    <h1>
        Manage Bookings
    </h1>


    <div class="table-container">


        <?php

        if (
            $result &&
            mysqli_num_rows($result) > 0
        ) {

        ?>


        <table>

            <tr>

                <th>ID</th>

                <th>Student</th>

                <th>Email</th>

                <th>Room</th>

                <th>Location</th>

                <th>Rent</th>

                <th>Booking Date</th>

                <th>Status</th>

                <th>Action</th>

            </tr>


            <?php

            while (
                $booking =
                mysqli_fetch_assoc($result)
            ) {

            ?>


            <tr>


                <!-- ID -->

                <td>

                    <?php

                    echo htmlspecialchars(
                        $booking['id']
                    );

                    ?>

                </td>


                <!-- STUDENT -->

                <td>

                    <?php

                    echo htmlspecialchars(
                        $booking['fullname']
                    );

                    ?>

                </td>


                <!-- EMAIL -->

                <td>

                    <?php

                    echo htmlspecialchars(
                        $booking['email']
                    );

                    ?>

                </td>


                <!-- ROOM -->

                <td>

                    <?php

                    echo htmlspecialchars(
                        $booking['room_name']
                    );

                    ?>

                </td>


                <!-- LOCATION -->

                <td>

                    <?php

                    echo htmlspecialchars(
                        $booking['location']
                    );

                    ?>

                </td>


                <!-- RENT -->

                <td>

                    ₹<?php

                    echo htmlspecialchars(
                        $booking['rent']
                    );

                    ?>

                </td>


                <!-- DATE -->

                <td>

                    <?php

                    echo htmlspecialchars(
                        $booking['booking_date']
                    );

                    ?>

                </td>


                <!-- STATUS -->

                <td>


                    <?php

                    if (
                        $booking['status']
                        === 'Pending'
                    ) {

                    ?>

                        <span class="status pending">
                            Pending
                        </span>

                    <?php

                    } elseif (
                        $booking['status']
                        === 'Approved'
                    ) {

                    ?>

                        <span class="status approved">
                            Approved
                        </span>

                    <?php

                    } else {

                    ?>

                        <span class="status rejected">
                            Rejected
                        </span>

                    <?php

                    }

                    ?>

                </td>


                <!-- ACTION -->

                <td>


                    <?php

                    if (
                        $booking['status']
                        === 'Pending'
                    ) {

                    ?>


                        <!-- APPROVE -->

                        <a
                            class="btn approve"
                            href="admin_bookings.php?approve=<?php echo (int)$booking['id']; ?>"
                            onclick="return confirm('Are you sure you want to approve this booking?');"
                        >
                            Approve
                        </a>


                        <!-- REJECT -->

                        <a
                            class="btn reject"
                            href="admin_bookings.php?reject=<?php echo (int)$booking['id']; ?>"
                            onclick="return confirm('Are you sure you want to reject this booking?');"
                        >
                            Reject
                        </a>


                    <?php

                    } else {

                    ?>

                        <span class="no-action">
                            No Action
                        </span>

                    <?php

                    }


                    ?>

                </td>


            </tr>


            <?php

            }

            ?>


        </table>


        <?php

        } else {

        ?>


            <div class="no-bookings">

                No bookings found.

            </div>


        <?php

        }

        ?>


    </div>

</div>


</body>

</html>