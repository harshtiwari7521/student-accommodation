
<?php

session_start();
include "db.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Student Accommodation</title>

    <link rel="stylesheet" href="style.css">

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f7fb;
        }


        /* =========================
           HERO SECTION
        ========================== */

        .hero {
            text-align: center;
            padding: 90px 20px;
            background: linear-gradient(
                135deg,
                #007bff,
                #0056b3
            );
            color: white;
        }

        .hero h1 {
            font-size: 42px;
            margin: 0 0 15px;
        }

        .hero p {
            font-size: 20px;
            margin: 0 auto 30px;
            max-width: 700px;
            line-height: 1.6;
        }

        .hero-btn {
            display: inline-block;
            padding: 13px 28px;
            background: white;
            color: #007bff;
            text-decoration: none;
            border-radius: 7px;
            font-weight: bold;
            font-size: 16px;
            transition: 0.3s;
        }

        .hero-btn:hover {
            background: #f0f0f0;
            transform: translateY(-2px);
        }


        /* =========================
           WELCOME MESSAGE
        ========================== */

        .welcome {
            text-align: center;
            padding: 35px 20px 10px;
        }

        .welcome h2 {
            margin: 0;
            color: #222;
        }

        .welcome p {
            color: #666;
        }


        /* =========================
           FEATURES
        ========================== */

        .features {
            display: flex;
            justify-content: center;
            gap: 25px;
            flex-wrap: wrap;
            padding: 50px 20px;
        }

        .feature-box {
            width: 300px;
            background: white;
            padding: 30px;
            text-align: center;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.10);
            transition: 0.3s;
        }

        .feature-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 7px 20px rgba(0,0,0,0.15);
        }

        .feature-icon {
            font-size: 40px;
            margin-bottom: 10px;
        }

        .feature-box h2 {
            color: #007bff;
            margin-bottom: 12px;
        }

        .feature-box p {
            color: #555;
            line-height: 1.6;
            margin: 0;
        }


        /* =========================
           HOW IT WORKS
        ========================== */

        .how-it-works {
            text-align: center;
            padding: 30px 20px 60px;
        }

        .how-it-works h2 {
            color: #222;
            font-size: 30px;
            margin-bottom: 30px;
        }

        .steps {
            display: flex;
            justify-content: center;
            gap: 25px;
            flex-wrap: wrap;
        }

        .step {
            width: 250px;
            padding: 25px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
        }

        .step-number {
            width: 40px;
            height: 40px;
            line-height: 40px;
            margin: 0 auto 15px;
            background: #007bff;
            color: white;
            border-radius: 50%;
            font-weight: bold;
            font-size: 18px;
        }

        .step h3 {
            color: #222;
        }

        .step p {
            color: #666;
            line-height: 1.5;
        }


        /* =========================
           MOBILE
        ========================== */

        @media (max-width: 600px) {

            .hero {
                padding: 60px 20px;
            }

            .hero h1 {
                font-size: 30px;
            }

            .hero p {
                font-size: 17px;
            }

            .feature-box {
                width: 100%;
                max-width: 350px;
            }

            .step {
                width: 100%;
                max-width: 350px;
            }

        }

    </style>

</head>


<body>


<!-- =========================
     NAVBAR
========================== -->

<?php include "navbar.php"; ?>


<!-- =========================
     WELCOME
========================== -->

<?php if (isset($_SESSION['user_id'])): ?>

    <section class="welcome">

        <h2>
            Welcome back! 👋
        </h2>

        <p>
            Find a comfortable place to stay near your college.
        </p>

    </section>

<?php endif; ?>


<!-- =========================
     HERO
========================== -->

<section class="hero">

    <h1>
        Student Accommodation 🏠
    </h1>

    <p>
        Find the best PGs, Hostels and Rooms
        near your college.
    </p>

    <a
        class="hero-btn"
        href="rooms.php"
    >
        🔍 Explore Rooms
    </a>

</section>


<!-- =========================
     FEATURES
========================== -->

<section class="features">


    <div class="feature-box">

        <div class="feature-icon">
            🏠
        </div>

        <h2>
            Find Rooms
        </h2>

        <p>
            Easily search and explore available
            rooms, PGs and hostels.
        </p>

    </div>


    <div class="feature-box">

        <div class="feature-icon">
            📍
        </div>

        <h2>
            Best Locations
        </h2>

        <p>
            Find accommodation at convenient
            locations near your college.
        </p>

    </div>


    <div class="feature-box">

        <div class="feature-icon">
            📋
        </div>

        <h2>
            Easy Booking
        </h2>

        <p>
            Book your preferred room and
            manage your bookings online.
        </p>

    </div>


</section>


<!-- =========================
     HOW IT WORKS
========================== -->

<section class="how-it-works">

    <h2>
        How It Works
    </h2>


    <div class="steps">


        <div class="step">

            <div class="step-number">
                1
            </div>

            <h3>
                Search
            </h3>

            <p>
                Explore rooms and search by
                location or room name.
            </p>

        </div>


        <div class="step">

            <div class="step-number">
                2
            </div>

            <h3>
                Choose
            </h3>

            <p>
                Check room details, rent,
                location and availability.
            </p>

        </div>


        <div class="step">

            <div class="step-number">
                3
            </div>

            <h3>
                Book
            </h3>

            <p>
                Submit your booking request
                and wait for admin approval.
            </p>

        </div>


    </div>

</section>


<!-- =========================
     FOOTER
========================== -->

<?php include "footer.php"; ?>


</body>

</html>

