
<?php

session_start();
include "db.php";

$message = "";
$message_type = "";


// ===============================
// REGISTRATION
// ===============================

if (isset($_POST['register'])) {

    // Get and clean input
    $fullname = trim($_POST['fullname'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';


    // ===============================
    // SERVER-SIDE VALIDATION
    // ===============================

    if ($fullname === '' || $email === '' || $phone === '' || $password === '') {

        $message = "Please fill all required fields.";
        $message_type = "error";

    } elseif (strlen($fullname) < 2) {

        $message = "Please enter a valid full name.";
        $message_type = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $message_type = "error";

    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {

        $message = "Phone number must contain exactly 10 digits.";
        $message_type = "error";

    } elseif (strlen($password) < 6) {

        $message = "Password must be at least 6 characters.";
        $message_type = "error";

    } elseif ($password !== $confirm_password) {

        $message = "Passwords do not match.";
        $message_type = "error";

    } else {


        // ===============================
        // CHECK DUPLICATE EMAIL
        // ===============================

        $check_sql = "SELECT id FROM users WHERE email = ? LIMIT 1";

        $check_stmt = mysqli_prepare($conn, $check_sql);

        if (!$check_stmt) {

            $message = "Database error. Please try again.";
            $message_type = "error";

        } else {

            mysqli_stmt_bind_param(
                $check_stmt,
                "s",
                $email
            );

            mysqli_stmt_execute($check_stmt);

            mysqli_stmt_store_result($check_stmt);


            if (mysqli_stmt_num_rows($check_stmt) > 0) {

                $message = "This email is already registered. Please login.";
                $message_type = "error";

            } else {


                // ===============================
                // HASH PASSWORD
                // ===============================

                $hashed_password = password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );


                // ===============================
                // DEFAULT ROLE
                // ===============================

                // IMPORTANT:
                // User can only register as student.
                // Admin role must be created separately.

                $role = "student";


                // ===============================
                // INSERT USER
                // ===============================

                $sql = "
                    INSERT INTO users
                    (fullname, email, phone, password, role)
                    VALUES (?, ?, ?, ?, ?)
                ";

                $stmt = mysqli_prepare($conn, $sql);


                if (!$stmt) {

                    $message = "Registration failed. Please try again.";
                    $message_type = "error";

                } else {

                    mysqli_stmt_bind_param(
                        $stmt,
                        "sssss",
                        $fullname,
                        $email,
                        $phone,
                        $hashed_password,
                        $role
                    );


                    if (mysqli_stmt_execute($stmt)) {

                        mysqli_stmt_close($stmt);
                        mysqli_stmt_close($check_stmt);

                        echo "
                            <script>
                                alert('Registration Successful! Please login.');
                                window.location='login.php';
                            </script>
                        ";

                        exit();

                    } else {

                        $message = "Registration failed. Please try again.";
                        $message_type = "error";

                        mysqli_stmt_close($stmt);
                    }
                }
            }

            mysqli_stmt_close($check_stmt);
        }
    }
}

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
        Register - Student Accommodation
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

            padding: 16px 40px;

            display: flex;

            justify-content: space-between;

            align-items: center;

            box-shadow: 0 2px 8px rgba(0,0,0,0.15);

        }


        .logo {

            color: white;

            text-decoration: none;

            font-size: 22px;

            font-weight: bold;

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


        /* ===============================
           REGISTER CONTAINER
        ================================ */

        .register-container {

            min-height: 80vh;

            display: flex;

            justify-content: center;

            align-items: center;

            padding: 40px 20px;

        }


        .register-box {

            width: 440px;

            max-width: 100%;

            background: white;

            padding: 35px;

            border-radius: 12px;

            box-shadow: 0 5px 20px rgba(0,0,0,0.12);

        }


        .register-box h1 {

            text-align: center;

            color: #222;

            margin: 0 0 8px;

        }


        .subtitle {

            text-align: center;

            color: #777;

            margin-bottom: 25px;

        }


        /* ===============================
           MESSAGE
        ================================ */

        .message {

            padding: 12px;

            border-radius: 6px;

            margin-bottom: 20px;

            text-align: center;

            font-weight: bold;

            font-size: 14px;

        }


        .error {

            background: #f8d7da;

            color: #842029;

        }


        /* ===============================
           FORM
        ================================ */

        label {

            display: block;

            font-weight: bold;

            color: #333;

            margin-bottom: 7px;

        }


        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="password"] {

            width: 100%;

            padding: 12px;

            margin-bottom: 18px;

            border: 1px solid #ccc;

            border-radius: 6px;

            font-size: 15px;

        }


        input:focus {

            outline: none;

            border-color: #0d6efd;

            box-shadow: 0 0 4px rgba(13,110,253,0.3);

        }


        .register-btn {

            width: 100%;

            padding: 12px;

            border: none;

            border-radius: 6px;

            background: #0d6efd;

            color: white;

            font-size: 16px;

            font-weight: bold;

            cursor: pointer;

        }


        .register-btn:hover {

            background: #0b5ed7;

        }


        /* ===============================
           LOGIN LINK
        ================================ */

        .login-link {

            text-align: center;

            margin-top: 20px;

            color: #666;

        }


        .login-link a {

            color: #0d6efd;

            text-decoration: none;

            font-weight: bold;

        }


        .login-link a:hover {

            text-decoration: underline;

        }


        /* ===============================
           FOOTER
        ================================ */

        footer {

            text-align: center;

            padding: 18px;

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

                gap: 12px;

            }


            .logo {

                font-size: 19px;

            }


            .nav-links {

                gap: 12px;

            }


            .nav-links a {

                font-size: 14px;

            }


            .register-container {

                padding: 25px 15px;

            }


            .register-box {

                padding: 25px 20px;

            }

        }

    </style>

</head>


<body>


<!-- ===============================
     NAVBAR
================================ -->

<nav class="navbar">


    <a
        href="index.php"
        class="logo"
    >
        🏠 Student Accommodation
    </a>


    <div class="nav-links">

        <a href="index.php">
            Home
        </a>


        <a href="rooms.php">
            Rooms
        </a>


        <a href="login.php">
            Login
        </a>

    </div>

</nav>



<!-- ===============================
     REGISTER
================================ -->

<div class="register-container">

    <div class="register-box">


        <h1>
            Create Account 🏠
        </h1>


        <p class="subtitle">
            Register as a student
        </p>


        <?php if ($message !== ''): ?>

            <div class="message <?php echo $message_type; ?>">

                <?php echo htmlspecialchars($message); ?>

            </div>

        <?php endif; ?>


        <form method="POST">


            <!-- FULL NAME -->

            <label>
                Full Name
            </label>

            <input
                type="text"
                name="fullname"
                placeholder="Enter your full name"
                value="<?php echo htmlspecialchars($_POST['fullname'] ?? ''); ?>"
                maxlength="100"
                required
            >


            <!-- EMAIL -->

            <label>
                Email
            </label>

            <input
                type="email"
                name="email"
                placeholder="Enter your email"
                value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                maxlength="150"
                required
            >


            <!-- PHONE -->

            <label>
                Phone Number
            </label>

            <input
                type="tel"
                name="phone"
                placeholder="Enter 10 digit phone number"
                value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>"
                pattern="[0-9]{10}"
                maxlength="10"
                inputmode="numeric"
                required
            >


            <!-- PASSWORD -->

            <label>
                Password
            </label>

            <input
                type="password"
                name="password"
                placeholder="Create a password"
                minlength="6"
                required
            >


            <!-- CONFIRM PASSWORD -->

            <label>
                Confirm Password
            </label>

            <input
                type="password"
                name="confirm_password"
                placeholder="Confirm your password"
                minlength="6"
                required
            >


            <!-- SUBMIT -->

            <input
                type="submit"
                name="register"
                value="Create Account"
                class="register-btn"
            >

        </form>


        <div class="login-link">

            Already have an account?

            <a href="login.php">
                Login here
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
