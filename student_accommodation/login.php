
<?php

session_start();
include "db.php";

$message = "";


// ===============================
// LOGIN
// ===============================

if (isset($_POST['login'])) {

    // Get input safely
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';


    // ===============================
    // VALIDATION
    // ===============================

    if ($email === '' || $password === '') {

        $message = "Please enter email and password.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";

    } else {


        // ===============================
        // SECURE DATABASE QUERY
        // ===============================

        $sql = "
            SELECT id, fullname, email, password, role
            FROM users
            WHERE email = ?
            LIMIT 1
        ";

        $stmt = mysqli_prepare($conn, $sql);


        if ($stmt) {

            mysqli_stmt_bind_param(
                $stmt,
                "s",
                $email
            );

            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);


            // ===============================
            // CHECK USER
            // ===============================

            if (mysqli_num_rows($result) === 1) {

                $user = mysqli_fetch_assoc($result);


                // ===============================
                // VERIFY PASSWORD
                // ===============================

                if (
                    isset($user['password']) &&
                    password_verify(
                        $password,
                        $user['password']
                    )
                ) {


                    // ===============================
                    // REGENERATE SESSION ID
                    // ===============================

                    session_regenerate_id(true);


                    // ===============================
                    // CREATE SESSION
                    // ===============================

                    $_SESSION['user_id'] = $user['id'];

                    $_SESSION['fullname'] = $user['fullname'];

                    $_SESSION['email'] = $user['email'];

                    $_SESSION['role'] = $user['role'];


                    // ===============================
                    // ADMIN LOGIN
                    // ===============================

                    if ($user['role'] === 'admin') {

                        mysqli_stmt_close($stmt);

                        echo "
                            <script>
                                alert('Admin Login Successful!');
                                window.location='admin_dashboard.php';
                            </script>
                        ";

                        exit();
                    }


                    // ===============================
                    // STUDENT LOGIN
                    // ===============================

                    if ($user['role'] === 'student') {

                        mysqli_stmt_close($stmt);

                        echo "
                            <script>
                                alert('Login Successful!');
                                window.location='student_dashboard.php';
                            </script>
                        ";

                        exit();
                    }


                    // ===============================
                    // UNKNOWN ROLE
                    // ===============================

                    $message = "Invalid account role.";

                } else {

                    $message = "Invalid Email or Password.";

                }

            } else {

                $message = "Invalid Email or Password.";

            }


            mysqli_stmt_close($stmt);

        } else {

            $message = "Something went wrong. Please try again.";

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
        Login - Student Accommodation
    </title>


    <style>

        * {
            box-sizing: border-box;
        }


        body {

            margin: 0;

            font-family: Arial, sans-serif;

            background: #f4f7fb;

            min-height: 100vh;

            display: flex;

            flex-direction: column;

        }


        /* ===============================
           LOGIN AREA
        ================================ */

        .login-container {

            flex: 1;

            display: flex;

            justify-content: center;

            align-items: center;

            padding: 40px 20px;

        }


        .login-box {

            width: 400px;

            max-width: 100%;

            background: white;

            padding: 35px;

            border-radius: 12px;

            box-shadow: 0 5px 20px rgba(0,0,0,0.12);

        }


        .login-box h1 {

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
           ERROR MESSAGE
        ================================ */

        .message {

            background: #f8d7da;

            color: #842029;

            padding: 12px;

            border-radius: 6px;

            margin-bottom: 20px;

            text-align: center;

            font-size: 14px;

            font-weight: bold;

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


        input[type="email"],
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


        /* ===============================
           LOGIN BUTTON
        ================================ */

        .login-btn {

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


        .login-btn:hover {

            background: #0b5ed7;

        }


        /* ===============================
           REGISTER LINK
        ================================ */

        .register-link {

            text-align: center;

            margin-top: 20px;

            color: #666;

        }


        .register-link a {

            color: #0d6efd;

            text-decoration: none;

            font-weight: bold;

        }


        .register-link a:hover {

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

            .login-container {

                padding: 25px 15px;

            }


            .login-box {

                padding: 25px 20px;

            }

        }

    </style>

</head>


<body>


<!-- ===============================
     NAVBAR
================================ -->

<?php include "navbar.php"; ?>



<!-- ===============================
     LOGIN
================================ -->

<div class="login-container">

    <div class="login-box">


        <h1>
            Student Login 🔐
        </h1>


        <p class="subtitle">
            Login to your account
        </p>


        <!-- ERROR -->

        <?php if ($message !== ''): ?>

            <div class="message">

                <?php
                echo htmlspecialchars($message);
                ?>

            </div>

        <?php endif; ?>


        <!-- LOGIN FORM -->

        <form method="POST">


            <!-- EMAIL -->

            <label>
                Email
            </label>

            <input
                type="email"
                name="email"
                placeholder="Enter your email"
                value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                required
            >


            <!-- PASSWORD -->

            <label>
                Password
            </label>

            <input
                type="password"
                name="password"
                placeholder="Enter your password"
                required
            >


            <!-- LOGIN -->

            <input
                type="submit"
                name="login"
                value="Login"
                class="login-btn"
            >

        </form>


        <!-- REGISTER -->

        <div class="register-link">

            Don't have an account?

            <a href="register.php">
                Register
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

