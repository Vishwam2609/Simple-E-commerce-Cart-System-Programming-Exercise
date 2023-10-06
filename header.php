<!DOCTYPE html>
<html lang="en" id="page" style="overflow:auto">

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HAPPYWAY</title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/theme.min.css">
    <link rel="stylesheet" href="assets/css/swatch-primary.css">
    <link rel="stylesheet" href="assets/css/swatch-secondary.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/products.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // Smooth scrolling for navbar links
            $('a[href^="#"]').on('click', function(event) {
                var target = $(this.getAttribute('href'));
                if (target.length) {
                    event.preventDefault();
                    $('html, body').animate({
                        scrollTop: target.offset().top
                    }, 500);
                }
            });
        });
    </script>

    <style>
        .navbar-nav li {
            position: relative;
        }

        .cart-icon {
            position: relative;
            display: inline-block;
        }
    </style>

</head>

<body>
    <header id="masthead" class="navbar navbar-sticky swatch-primary" role="banner">
        <div class="container">
            <div class="navbar-header" style="height: 12vh;">
                <button type="button" id="btn-toggle-menu" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".main-navbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="./index.php" class="navbar-brand" id="logo-nav">
                    <img src="./assets/images/logo.png" alt="Happywaycreations Logo">
                </a>
            </div>
            <nav class="collapse navbar-collapse main-navbar" role="navigation">
                <ul class="nav navbar-nav navbar-right">
                    <li class="active current-menu-item">
                        <a href="./index.php">Home</a>
                    </li>
                    <li class="">
                        <a href="./index.php#about">About Us</a>
                    </li>
                    <li class="">
                        <a href="./index.php#products">Products</a>
                    </li>
                    <li class="">
                        <a href="./index.php#contact">Contact</a>
                    </li>
                    <li class=" ">
                        <?php
                        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                            $loggedin = true;
                            $email = $_SESSION['useremail'];
                            require('./dbconnect.php');

                            // Get the number of items in the user's cart
                            $cartItemsQuery = "SELECT SUM(`qty`) AS totalItems FROM cart WHERE u_email='$email'";
                            $cartItemsResult = mysqli_query($conn, $cartItemsQuery);
                            $totalItems = 0;
                            if ($cartItemsResult && mysqli_num_rows($cartItemsResult) > 0) {
                                $totalItemsData = $cartItemsResult->fetch_assoc();
                                $totalItems = $totalItemsData['totalItems'];
                            }

                            $sql = "SELECT * FROM `users` WHERE email='$email'";
                            $result = mysqli_query($conn, $sql);

                            if ($result && mysqli_num_rows($result) > 0) {
                                // User data found in the database
                                $user = $result->fetch_assoc();
                                $name = $user['name'];
                                echo '<a href="./profile.php">' . $name . '</a>';
                                echo '<li class="cart-icon">
                                        <a href="./my-cart.php">
                                            <i class="fas fa-shopping-cart"></i>
                                            <span class="">(' . $totalItems . ')</span>
                                        </a>
                                      </li>';
                            } else {
                                // User data not found in the database, show login option
                                echo '<a href="./profile.php">Login</a>';
                            }
                        } else {
                            // User is not logged in, show login option
                            $loggedin = false;
                            echo '<a href="./profile.php">Login</a>';
                        }
                        ?>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <script src="./assets/js/main.js"></script>
    <script src="./assets/js/packages.js"></script>
    <script src="./assets/js/theme.js"></script>

</body>

</html>