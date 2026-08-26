<?php
session_start();
include "db.php";

// Only admin can edit rooms
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Check room ID
if (!isset($_GET['id'])) {
    echo "Room ID is missing.";
    exit();
}

$id = $_GET['id'];

// Get room details
$sql = "SELECT * FROM rooms WHERE id='$id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    echo "Room not found.";
    exit();
}

$room = mysqli_fetch_assoc($result);


// Update room
if (isset($_POST['update_room'])) {

    $room_name = $_POST['room_name'];
    $location = $_POST['location'];
    $rent = $_POST['rent'];
    $description = $_POST['description'];

    // If new image selected
    if (!empty($_FILES['image']['name'])) {

        $image = $_FILES['image']['name'];
        $temp = $_FILES['image']['tmp_name'];

        move_uploaded_file($temp, "images/" . $image);

        $sql = "UPDATE rooms SET
                room_name='$room_name',
                location='$location',
                rent='$rent',
                description='$description',
                image='$image'
                WHERE id='$id'";

    } else {

        // Keep old image
        $sql = "UPDATE rooms SET
                room_name='$room_name',
                location='$location',
                rent='$rent',
                description='$description'
                WHERE id='$id'";
    }


    if (mysqli_query($conn, $sql)) {

        echo "<script>
                alert('Room Updated Successfully');
                window.location='rooms.php';
              </script>";

    } else {

        echo "<script>
                alert('Error Updating Room');
              </script>";
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Edit Room</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 30px;
        }

        .container {
            width: 450px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
        }

        label {
            font-weight: bold;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            margin-bottom: 18px;
            box-sizing: border-box;
        }

        textarea {
            height: 100px;
        }

        input[type="submit"] {
            background: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-weight: bold;
        }

        input[type="submit"]:hover {
            background: #0056b3;
        }

        .back {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: #007BFF;
        }

        img {
            width: 150px;
            height: 100px;
            object-fit: cover;
            margin-bottom: 15px;
        }

    </style>

</head>

<body>

<div class="container">

    <a class="back" href="rooms.php">
        ← Back to Rooms
    </a>

    <h2>Edit Room</h2>

    <form method="POST" enctype="multipart/form-data">

        <label>Room Name:</label>

        <input
            type="text"
            name="room_name"
            value="<?php echo htmlspecialchars($room['room_name']); ?>"
            required
        >


        <label>Location:</label>

        <input
            type="text"
            name="location"
            value="<?php echo htmlspecialchars($room['location']); ?>"
            required
        >


        <label>Rent:</label>

        <input
            type="number"
            name="rent"
            value="<?php echo htmlspecialchars($room['rent']); ?>"
            required
        >


        <label>Description:</label>

        <textarea
            name="description"
            required
        ><?php echo htmlspecialchars($room['description']); ?></textarea>


        <label>Current Image:</label><br>

        <img
            src="images/<?php echo htmlspecialchars($room['image']); ?>"
            alt="Room Image"
        >

        <br>


        <label>Change Image:</label>

        <input
            type="file"
            name="image"
        >


        <input
            type="submit"
            name="update_room"
            value="Update Room"
        >

    </form>

</div>

</body>

</html>