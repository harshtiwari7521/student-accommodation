```php
<?php

session_start();
include "db.php";


// ===============================
// ONLY ADMIN CAN DELETE
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
// CHECK ROOM ID
// ===============================

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

    echo "<script>
            alert('Invalid Room ID');
            window.location='rooms.php';
          </script>";

    exit();
}

$id = intval($_GET['id']);


// ===============================
// GET ROOM IMAGE
// ===============================

$sql = "SELECT image FROM rooms WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {

    mysqli_stmt_close($stmt);

    echo "<script>
            alert('Room not found');
            window.location='rooms.php';
          </script>";

    exit();
}

$room = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


// ===============================
// DELETE ROOM
// ===============================

$delete_sql = "DELETE FROM rooms WHERE id = ?";

$delete_stmt = mysqli_prepare($conn, $delete_sql);

mysqli_stmt_bind_param($delete_stmt, "i", $id);

if (mysqli_stmt_execute($delete_stmt)) {

    // Delete image from images folder
    if (!empty($room['image'])) {

        $image_path = "images/" . basename($room['image']);

        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    mysqli_stmt_close($delete_stmt);

    echo "<script>
            alert('Room Deleted Successfully');
            window.location='rooms.php';
          </script>";

} else {

    mysqli_stmt_close($delete_stmt);

    echo "<script>
            alert('Error Deleting Room');
            window.location='rooms.php';
          </script>";
}

?>
```
