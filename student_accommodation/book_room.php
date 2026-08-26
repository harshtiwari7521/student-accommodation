
<?php

session_start();
include "db.php";


// ===============================
// CHECK STUDENT LOGIN
// ===============================

if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['role']) ||
    $_SESSION['role'] != 'student'
) {

    header("Location: login.php");
    exit();

}

$user_id = $_SESSION['user_id'];


// ===============================
// CHECK ROOM ID
// ===============================

if (!isset($_GET['id'])) {

    echo "Room ID is missing.";
    exit();

}

$room_id = intval($_GET['id']);


// ===============================
// CHECK ROOM EXISTS
// ===============================

$room_sql = "SELECT id, room_name FROM rooms WHERE id = ?";

$room_stmt = mysqli_prepare($conn, $room_sql);

mysqli_stmt_bind_param(
    $room_stmt,
    "i",
    $room_id
);

mysqli_stmt_execute($room_stmt);

$room_result = mysqli_stmt_get_result($room_stmt);


if (mysqli_num_rows($room_result) == 0) {

    echo "Room not found.";
    exit();

}

$room = mysqli_fetch_assoc($room_result);

mysqli_stmt_close($room_stmt);


// ===============================
// CHECK APPROVED BOOKING
// ===============================

$approved_sql = "
    SELECT id
    FROM bookings
    WHERE room_id = ?
    AND status = 'Approved'
    LIMIT 1
";

$approved_stmt = mysqli_prepare(
    $conn,
    $approved_sql
);

mysqli_stmt_bind_param(
    $approved_stmt,
    "i",
    $room_id
);

mysqli_stmt_execute($approved_stmt);

$approved_result = mysqli_stmt_get_result(
    $approved_stmt
);


if (mysqli_num_rows($approved_result) > 0) {

    mysqli_stmt_close($approved_stmt);

    echo "<script>
            alert('Sorry! This room is already occupied.');
            window.location='rooms.php';
          </script>";

    exit();

}

mysqli_stmt_close($approved_stmt);


// ===============================
// CHECK DUPLICATE PENDING BOOKING
// ===============================

$pending_sql = "
    SELECT id
    FROM bookings
    WHERE user_id = ?
    AND room_id = ?
    AND status = 'Pending'
    LIMIT 1
";

$pending_stmt = mysqli_prepare(
    $conn,
    $pending_sql
);

mysqli_stmt_bind_param(
    $pending_stmt,
    "ii",
    $user_id,
    $room_id
);

mysqli_stmt_execute($pending_stmt);

$pending_result = mysqli_stmt_get_result(
    $pending_stmt
);


if (mysqli_num_rows($pending_result) > 0) {

    mysqli_stmt_close($pending_stmt);

    echo "<script>
            alert('You have already requested this room.');
            window.location='my_bookings.php';
          </script>";

    exit();

}

mysqli_stmt_close($pending_stmt);


// ===============================
// CREATE BOOKING
// ===============================

$insert_sql = "
    INSERT INTO bookings
    (user_id, room_id, status)
    VALUES (?, ?, 'Pending')
";

$insert_stmt = mysqli_prepare(
    $conn,
    $insert_sql
);

mysqli_stmt_bind_param(
    $insert_stmt,
    "ii",
    $user_id,
    $room_id
);


if (mysqli_stmt_execute($insert_stmt)) {

    mysqli_stmt_close($insert_stmt);

    echo "<script>
            alert('Room booking request submitted successfully!');
            window.location='my_bookings.php';
          </script>";

} else {

    echo "<script>
            alert('Booking failed. Please try again.');
            window.location='rooms.php';
          </script>";

}

?>
```
