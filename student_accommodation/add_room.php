<?php
session_start();
include "db.php";

// Only admin can add rooms
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_POST['add_room'])) {

    $room_name = $_POST['room_name'];
    $location = $_POST['location'];
    $rent = $_POST['rent'];
    $description = $_POST['description'];

    $image = $_FILES['image']['name'];
    $temp = $_FILES['image']['tmp_name'];

    move_uploaded_file($temp, "images/".$image);

    $sql = "INSERT INTO rooms(room_name, location, rent, description, image)
            VALUES('$room_name','$location','$rent','$description','$image')";

    if(mysqli_query($conn,$sql)){
        echo "<script>alert('Room Added Successfully');</script>";
    } else {
        echo "<script>alert('Error');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Room</title>
</head>
<body>

<h2>Add New Room</h2>

<form action="" method="POST" enctype="multipart/form-data">

Room Name:<br>
<input type="text" name="room_name" required><br><br>

Location:<br>
<input type="text" name="location" required><br><br>

Rent:<br>
<input type="number" name="rent" required><br><br>

Description:<br>
<textarea name="description" required></textarea><br><br>

Image:<br>
<input type="file" name="image" required><br><br>

<input type="submit" name="add_room" value="Add Room">

</form>

</body>
</html>