<?php
include "db.php";
session_start();


// ===============================
// SECURE SEARCH
// ===============================

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search != '') {

    $sql = "SELECT * FROM rooms
            WHERE location LIKE ?
            OR room_name LIKE ?";

    $stmt = mysqli_prepare($conn, $sql);

    $search_value = "%" . $search . "%";

    mysqli_stmt_bind_param(
        $stmt,
        "ss",
        $search_value,
        $search_value
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    mysqli_stmt_close($stmt);

} else {

    $sql = "SELECT * FROM rooms";

    $result = mysqli_query($conn, $sql);
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Available Rooms</title>

    <style>

        *{
            box-sizing:border-box;
        }

        body{
            margin:0;
            font-family:Arial, sans-serif;
            background:#f4f7fb;
        }


        /* ===============================
           NAVBAR
        =============================== */

        .navbar{
            background:#0d6efd;
            padding:18px 50px;
            display:flex;
            justify-content:space-between;
            align-items:center;
        }

        .navbar h2{
            color:white;
            margin:0;
        }

        .nav-links a{
            color:white;
            text-decoration:none;
            margin-left:20px;
            font-weight:bold;
        }

        .nav-links a:hover{
            text-decoration:underline;
        }


        /* ===============================
           MAIN
        =============================== */

        .container{
            width:90%;
            max-width:1200px;
            margin:auto;
        }

        h1{
            text-align:center;
            margin-top:35px;
            color:#222;
        }

        .subtitle{
            text-align:center;
            color:#666;
            margin-bottom:25px;
        }


        /* ===============================
           SEARCH
        =============================== */

        .search-box{
            text-align:center;
            margin-bottom:35px;
        }

        .search-box input[type="text"]{
            width:300px;
            padding:12px;
            border:1px solid #ccc;
            border-radius:6px;
            font-size:15px;
        }

        .search-box input[type="submit"]{
            padding:12px 20px;
            border:none;
            background:#0d6efd;
            color:white;
            border-radius:6px;
            cursor:pointer;
            font-size:15px;
        }

        .search-box input[type="submit"]:hover{
            background:#0b5ed7;
        }


        /* ===============================
           ROOM GRID
        =============================== */

        .rooms-container{
            display:flex;
            flex-wrap:wrap;
            gap:25px;
            justify-content:center;
        }

        .room{
            width:350px;
            background:white;
            border-radius:12px;
            overflow:hidden;
            box-shadow:0 4px 15px rgba(0,0,0,0.10);
            transition:0.3s;
        }

        .room:hover{
            transform:translateY(-5px);
            box-shadow:0 8px 25px rgba(0,0,0,0.15);
        }

        .room img{
            width:100%;
            height:220px;
            object-fit:cover;
        }

        .room-content{
            padding:20px;
        }

        .room h2{
            margin-top:0;
            color:#222;
        }

        .location{
            color:#555;
        }

        .rent{
            color:#0d6efd;
            font-size:20px;
            font-weight:bold;
        }

        .description{
            color:#666;
            line-height:1.5;
        }


        /* ===============================
           BUTTONS
        =============================== */

        .btn{
            display:inline-block;
            padding:9px 15px;
            border-radius:6px;
            text-decoration:none;
            color:white;
            margin-top:8px;
            margin-right:5px;
            font-size:14px;
        }

        .details{
            background:#0d6efd;
        }

        .book{
            background:#198754;
        }

        .edit{
            background:#ffc107;
            color:#000;
        }

        .delete{
            background:#dc3545;
        }

        .btn:hover{
            opacity:0.85;
        }


        /* ===============================
           AVAILABILITY
        =============================== */

        .available{
            display:inline-block;
            background:#198754;
            color:white;
            padding:7px 12px;
            border-radius:5px;
            margin-top:10px;
            font-weight:bold;
            font-size:13px;
        }

        .occupied{
            display:inline-block;
            background:#dc3545;
            color:white;
            padding:7px 12px;
            border-radius:5px;
            margin-top:10px;
            font-weight:bold;
            font-size:13px;
        }


        /* ===============================
           NO ROOM
        =============================== */

        .no-room{
            text-align:center;
            color:#777;
            font-size:18px;
            margin-top:50px;
        }


        /* ===============================
           FOOTER
        =============================== */

        footer{
            text-align:center;
            margin-top:50px;
            padding:20px;
            background:#222;
            color:white;
        }


        /* ===============================
           MOBILE
        =============================== */

        @media(max-width:600px){

            .navbar{
                padding:15px;
                flex-direction:column;
                gap:15px;
            }

            .nav-links{
                text-align:center;
            }

            .nav-links a{
                margin:5px;
                display:inline-block;
            }

            .search-box input[type="text"]{
                width:80%;
            }

            .room{
                width:100%;
            }

        }

    </style>

</head>


<body>


<!-- ===============================
     NAVBAR
================================ -->

<div class="navbar">

    <h2>Student Accommodation</h2>

    <div class="nav-links">

        <a href="index.php">
            Home
        </a>

        <a href="rooms.php">
            Rooms
        </a>


        <?php

        if(isset($_SESSION['user_id']))
        {

        ?>

            <a href="my_bookings.php">
                My Bookings
            </a>

            <a href="logout.php">
                Logout
            </a>

        <?php

        }
        else
        {

        ?>

            <a href="login.php">
                Login
            </a>

            <a href="register.php">
                Register
            </a>

        <?php

        }

        ?>

    </div>

</div>



<!-- ===============================
     MAIN
================================ -->

<div class="container">

    <h1>
        Available Rooms
    </h1>

    <p class="subtitle">
        Find the best PGs, Hostels and Rooms for your stay
    </p>



    <!-- ===============================
         SEARCH
    ================================ -->

    <div class="search-box">

        <form method="GET">

            <input
                type="text"
                name="search"
                placeholder="Search by location or room name"
                value="<?php echo htmlspecialchars($search); ?>"
            >

            <input
                type="submit"
                value="Search"
            >

        </form>

    </div>



    <!-- ===============================
         ROOMS
    ================================ -->

    <div class="rooms-container">


    <?php

    if(mysqli_num_rows($result) > 0)
    {

        while($row = mysqli_fetch_assoc($result))
        {


            // ===============================
            // ROOM AVAILABILITY
            // ===============================

            $room_id = $row['id'];

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
                $room_id
            );

            mysqli_stmt_execute(
                $availability_stmt
            );

            $availability_result =
                mysqli_stmt_get_result(
                    $availability_stmt
                );

            $is_occupied =
                mysqli_num_rows(
                    $availability_result
                ) > 0;

            mysqli_stmt_close(
                $availability_stmt
            );

    ?>


        <!-- ===============================
             ROOM CARD
        ================================ -->

        <div class="room">


            <!-- ROOM IMAGE -->

            <?php

            $image = !empty($row['image'])
                ? $row['image']
                : 'default-room.jpg';

            ?>

            <img
                src="images/<?php echo htmlspecialchars($image); ?>"
                alt="Room Image"
            >



            <div class="room-content">


                <!-- ROOM NAME -->

                <h2>

                    <?php

                    echo htmlspecialchars(
                        $row['room_name']
                    );

                    ?>

                </h2>



                <!-- LOCATION -->

                <p class="location">

                    📍

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

                    ?>/month

                </p>



                <!-- DESCRIPTION -->

                <p class="description">

                    <?php

                    echo htmlspecialchars(
                        $row['description']
                    );

                    ?>

                </p>



                <!-- ===============================
                     AVAILABILITY
                ================================ -->

                <?php

                if($is_occupied)
                {

                ?>

                    <span class="occupied">

                        🔴 Occupied

                    </span>

                <?php

                }
                else
                {

                ?>

                    <span class="available">

                        🟢 Available

                    </span>

                <?php

                }

                ?>


                <br>



                <!-- ===============================
                     VIEW DETAILS
                ================================ -->

                <a
                    class="btn details"
                    href="room_details.php?id=<?php echo $row['id']; ?>"
                >

                    View Details

                </a>



                <!-- ===============================
                     STUDENT BOOK BUTTON
                ================================ -->

                <?php

                if(
                    isset($_SESSION['user_id']) &&
                    isset($_SESSION['role']) &&
                    $_SESSION['role'] == 'student'
                )
                {

                    if(!$is_occupied)
                    {

                ?>

                        <a
                            class="btn book"
                            href="book_room.php?id=<?php echo $row['id']; ?>"
                        >

                            Book Now

                        </a>

                <?php

                    }
                    else
                    {

                ?>

                        <span class="occupied">

                            Already Booked

                        </span>

                <?php

                    }

                }

                ?>



                <!-- ===============================
                     ADMIN BUTTONS
                ================================ -->

                <?php

                if(
                    isset($_SESSION['role']) &&
                    $_SESSION['role'] == 'admin'
                )
                {

                ?>

                    <br>

                    <a
                        class="btn edit"
                        href="edit_room.php?id=<?php echo $row['id']; ?>"
                    >

                        Edit

                    </a>



                    <a
                        class="btn delete"
                        href="delete_room.php?id=<?php echo $row['id']; ?>"
                        onclick="return confirm('Are you sure you want to delete this room?');"
                    >

                        Delete

                    </a>

                <?php

                }

                ?>


            </div>

        </div>


    <?php

        }

    }
    else
    {

    ?>

        <!-- NO ROOM -->

        <div class="no-room">

            No rooms found.

        </div>

    <?php

    }

    ?>

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