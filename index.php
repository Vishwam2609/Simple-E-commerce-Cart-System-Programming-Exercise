<!DOCTYPE html>
<html lang="en" id="page" style="overflow:auto">

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} ?>

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
        section#home {
            height: 88vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .carousel-container {
            width: 80%;
            height: 88vh;
            overflow: hidden;
        }

        .carousel-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        section#about .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .founder-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: flex-start;
        }

        .card {
            background-color: transparent;
            margin-bottom: 20px;
            margin-right: 20px;
            margin-left: 20px;
            padding: 10px;
            border-radius: 5px;
            box-sizing: border-box;
            border: none;
            width: 35%;
            text-align: justify;
        }

        .card img {
            width: 100%;
            height: auto;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .product-card {
            position: relative;
            width: 350px;
            height: 350px;
            margin: 2px;
            border-radius: 25px;
            overflow: hidden;
            cursor: pointer;
        }

        .product-image {
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            object-fit: cover;
        }

        .product-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .product-card:hover .product-overlay {
            opacity: 1;
        }

        .product-name {
            font-size: 18px;
            color: black;
        }

        .dropdown-menu {
            min-width: 150px;
            padding: 0;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 999;
        }

        .dropdown-menu>li>a {
            display: block;
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .dropdown-menu>li>a:hover {
            background-color: #f5f5f5;
        }

        @media (max-width: 767px) {
            .founder-container {
                height: auto;
                flex-direction: column;
                align-items: center;
            }

            .card {
                width: 100%;
                margin: 10px 0;
                height: auto;
            }

            .card h3 {
                margin-top: 20px;
            }

            .card p {
                max-height: none;
                overflow: hidden;
                font-size: 14px;
            }

            .carousel-container {
                width: 100%;
                height: auto;
                margin: 0;
            }

            .carousel-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                margin: 0;
            }
        }
    </style>

</head>

<body>
    <script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
    <df-messenger chat-title="Happie" agent-id="f16f7a80-216b-4354-9565-eab97f2d8303" language-code="en"></df-messenger>

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
                        <a href="#about">About Us</a>
                    </li>
                    <li class="">
                        <a href="#products">Products</a>
                    </li>
                    <li class="">
                        <a href="#contact">Contact</a>
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

    <section class="section swatch-secondary" id="home">
        <div class="carousel-container">
            <span class="carousel-image">
                <img src="./assets/home/1.jpg" alt="Homepage Image">
            </span>
        </div>
    </section>

    <!-- Founder Information -->
    <section class="section swatch-primary" id="about">
        <div class="container">
            <header class="section-header underline">
                <h1 class="headline super hairline white-color-font">Introduction</h1>
                <p class="white-color-font" style="text-align: centre;">
                    HappyWay is a way of Happy living built on deeply rooted Indian origin retained as well as elevated to meet need of present generation. It brings Food/Design creativity stakeholders to come on one platform to support awareness and consumption of HappyWay Food & Design for Customers/Stakeholders. It also makes product delivery live and on platform.
                </p>
            </header>
        </div>

        <div class="founder-container">
            <div class="card" style="height: 70vh;">
                <div class="box-round flat-shadow box-big">
                    <div class="box-dummy"></div>
                    <figure class="box-inner">
                        <img data-animation="pulse" class="svg-inject" src="./assets/images/founder1.jpg" alt="a clock" />
                    </figure>
                </div>
                <h3 class="text-center">
                    Hetal Ranpura <br>
                    (Founder and CEO)
                </h3>
                <p class="text white-color-font">
                    Hetal is a Design and Food enthusiast and is a passionate promoter of the Happy and Healthy lifestyle. She believes that Creativity is way of happiness in all form. Her first efforts to make her stake holders to eat healthy has resulted in the making 15+ healthy and natural recipes that are now served to Organic events community. She has also designed creative designs for Life style industry.
                    Being from Ahmedabad, Hetal loves to work with children and took up her first role as Food exihibit in various events. This love for children has translated to her now developing healthy recipes that all can enjoy. she set out to start creating healthy recipes that especially all generations can enjoy.
                    Hetal is also very focused-when she develops a recipe, she considers every ingredient. Her creativity comes from her experience designing jewellery at a family jewellery establishment.
                </p>
            </div>

            <div class="card" style="height: 70vh;">
                <div class="box-round flat-shadow box-big">
                    <div class="box-dummy"></div>
                    <figure class="box-inner">
                        <img data-animation="pulse" class="svg-inject" src="./assets/images/founder2.jpg" alt="a clock" />
                    </figure>
                </div>
                <h3 class="text-center">
                    Dhara Bhadiyadra <br>
                    (Chief Food Consultant)
                </h3>
                <p class="text white-color-font">
                    Dhara is a Food enthusiast and is a passionate promoter of the Happy lifestyles for all generations. Her first efforts to make her stake holders to eat healthy has resulted in the making 15+ healthy and natural recipes that are now served to Organic events community.
                    Being from Ahmedabad, Dhara loves to work with children and took up her first role as Food exihibit in various events. Dhara works passionately in converting prototype , idea in to product by applying all his expertise and she is an avid consultant.
                    Dhara also very clear-when she is in Chief Consultant role, as she believes in Strong foundation of product in terms of development and realization. She inherits sound experience from IT industry in applying testing frameworks for complex products. She is Computer Engineer from Gujarat.
                </p>
            </div>
        </div>
    </section>



    <section class="section swatch-secondary" id="products">
        <div class="container">
            <header class="section-header text-center underline">
                <h1 class="headline super hairline black-color-font">Products</h1>
            </header>

            <?php
            require('./dbconnect.php');
            ?>

            <div id="myBtnContainer" style="display: flex; justify-content: center;">
                <ul class="nav products-bar" style="display: flex; font-size: large;">
                    <li class="" style="text-decoration: none; margin-left: 2px;">
                        <a href="#products" onclick="filterSelection('all')"> All</a>
                    </li>
                    <?php
                    $sql = "SELECT DISTINCT category FROM products";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $category = $row['category'];
                            echo '
                            <li class="" style="text-decoration: none; margin-left: 2px;">
                                <a href="#products" onclick="filterSelection(\'' . $category . '\')">' . $category . '</a>
                            </li>
                            ';
                        }
                    }
                    ?>
                </ul>
            </div>

            <div class="container product-grid">
                <?php
                $sql = "SELECT * FROM products";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $cat = "filterDiv " . $row['category'];
                        $img = "./products/" . $row['image'];
                        $name = $row['name'];

                        echo '
                        <div class="' . $cat . ' product-card" onClick="showProductDetails(\'' . $name . '\')">
                            <div class="product-image" style="background-image: url(\'' . $img . '\');"></div>
                            <div class="product-overlay">
                                <p class="product-name">' . $name . '</p>
                            </div>
                        </div>
                        ';
                    }
                }
                ?>
            </div>
        </div>
    </section>



    <footer class="section swatch-primary" id="contact">
        <div class="container">
            <header id="contactId" class="section-header text-center underline">
                <h1 class="headline super hairline white-color-font">Contact</h1>
            </header>
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-10 col-md-offset-contact">
                            <form class="contact-form" id="contactForm" novalidate="" action="./send.php" method="post">
                                <div class="form-group form-icon-group">
                                    <input class="form-control" style="background-color: #dedede; color: black;" id="username" name="username" placeholder="Your name *" type="text">
                                    <i class="fa fa-user" style="color: black;"></i>
                                </div>
                                <div class="form-group form-icon-group">
                                    <input class="form-control" style="background-color: #dedede; color: black;" id="email" name="email" placeholder="Your email *" type="text">
                                    <i class="fa fa-envelope" style="color: black;"></i>
                                </div>
                                <div class="form-group form-icon-group" style="background-color: white;">
                                    <textarea class="form-control" style="background-color: #dedede; color: black;" id="message" name="message" placeholder="Your message *" rows="4" style="height: 118px;resize: none;"></textarea>
                                    <i class="fa fa-comment" style="color: black;"></i>
                                </div>
                                <div class="form-group text-center">
                                    <button class="btn btn-primary btn-icon btn-icon-right" type="submit" id="send" name="send" style="margin-top: 4%;">
                                        Send email
                                    </button>
                                </div>
                                <div id="messages"></div>
                            </form>
                        </div>
                    </div>

                    <div class="alert alert-success col-md-10" id="emailSuccess">
                        <button class="close  close-alert" id="successStatus" type="button">×</button>
                        <div>
                            <div style="text-align: center;">
                                <span style="padding:auto;"><strong>Thank You!</strong></span><br />
                                <span>Email has been sent to Happywaycreations! We will contact you shortly.</span>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-danger col-md-10 col-md-offset-1" id="nameError">
                        <button class="close  close-alert" type="button">×</button>
                        <strong>Please enter your name</strong>
                    </div>
                    <div class="alert alert-danger col-md-10 col-md-offset-1" id="emailError">
                        <button class="close  close-alert" type="button">×</button>
                        <strong>Please enter your email address</strong>
                    </div>
                    <div class="alert alert-danger col-md-10 col-md-offset-1" id="emailInvalidError">
                        <button class="close  close-alert" type="button">×</button>
                        <strong>Please enter a valid email address</strong>
                    </div>
                    <div class="alert alert-danger col-md-10 col-md-offset-1" id="messageError">
                        <button class="close close-alert" type="button">×</button>
                        <strong>Please enter your message</strong>
                    </div>
                </div>

                <div class="col-md-6 top-row top-row-margin-left">
                    <div class="row border_class" style="width: 88%;margin-top: 10px;height: 32px;">
                        <div class="col-xs-8 col-md-offset-content" style="margin-right:-10%;margin-bottom: -1%;">
                            <p style="color: white;text-align: right;">info@happywaycreations.co.in</p>
                        </div>
                        <div class="col-xs-1 col-md-offset-icon pull-right" style="margin-bottom: 8px;">
                            <i class="fa fa-envelope fa-1x" aria-hidden="true" style="color: white;font-size: 15px"></i>
                        </div>
                    </div>

                    <div class="row border_class" style="width: 88%;margin-top: 25px;height: 5%;">
                        <div class="col-xs-8 col-md-offset-content" style="margin-right:-10%;margin-bottom: -3px;">
                            <p style="color: white;text-align: right;font-size:95%;">+91 9427704820</p>
                        </div>
                        <div class="col-xs-1 col-md-offset-icon pull-right" style="margin-bottom: -3px">
                            <i class="fa fa-phone fa-1x" aria-hidden="true" style="color:white;font-size: 19px"></i>
                        </div>
                    </div>

                    <div class="row border_class" style="width: 88%;margin-top: 25px;height: 5%;">
                        <div class="col-xs-8 col-md-offset-content" style="margin-right:-10%;margin-bottom: -3px;">
                            <p style="color:white;text-align: right;line-height: 163%;">
                                Vastrapur,<br>
                                Ahmedabad,<br>
                                <span style="font-size: 95%;">380015 </span>, Gujarat - India
                            </p>
                        </div>
                        <div class="col-xs-1 col-md-offset-icon pull-right">
                            <a href="https://www.google.com/maps/place/23.0384,72.5288" target="popup" onclick="window.open('https://www.google.com/maps/place/23.0384,72.5288','popup','width=1200,height=600'); return false;">
                                <i class="fa fa-map-marker fa-1x" style="color: white;font-size: 19px; background-color: #294c24;" aria-hidden="true"></i>
                            </a>

                        </div>
                    </div>

                    <div class="row" style="margin-top: 30px;">
                        <div class="col-xs-9 col-xs-9-margin-left-small-screen" style="margin-left: 8%;">
                            <div id="swatch_social-2" class="sidebar-widget  widget_swatch_social" style="padding-left: 25%; padding-right: 20%;">
                                <ul class="unstyled inline small-screen-center-icons social-background ul-text-center">
                                    <li class="ul-li-small-screen" style="width: 30%;">
                                        <a class="track_outbound_link" target="_blank" data-outbound-link-name="Happywaycreations - Facebook Profile" href="#">
                                            <i class="fa-brands fa-facebook-f" style="font-size: 22px;color: white;"></i>
                                        </a>
                                    </li>
                                    <li class="ul-li-small-screen" style="width: 30%;">
                                        <a class="track_outbound_link" target="_blank" data-outbound-link-name="Happywaycreations - Instagram Profile" href="#">
                                            <i class="fa-brands fa-instagram" style="font-size: 22px;color: white;"></i>
                                        </a>
                                    </li>
                                    <li class="ul-li-small-screen" style="width: 30%;">
                                        <a class="track_outbound_link" target="_blank" data-outbound-link-name="Happywaycreations - Twitter Profile" href="#">
                                            <i class="fa-brands fa-twitter" style="font-size: 22px;color: white;"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </footer>

    <script src="assets/js/products.js"></script>
    <script src="assets/js/packages.js"></script>
    <script src="assets/js/theme.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        function showProductDetails(name) {
            window.location.href = 'product-details.php?name=' + encodeURIComponent(name);
        }
    </script>
</body>

</html>