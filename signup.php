<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    header("location: ./profile.php");
}
?>

<?php
$showAlert = false;
$showerror = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require('./dbconnect.php');
    $username = $_POST['username'];
    $useremail = $_POST['useremail'];
    $userphonenumber = $_POST['userphonenumber'];
    $adrs = $_POST['adrs'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $exists = false;
    $existSql = "SELECT * FROM `users` WHERE email='$useremail'";
    $result = mysqli_query($conn, $existSql);
    $numExistRows = mysqli_num_rows($result);
    if ($numExistRows > 0) {
        // $exists = true;
        $showerror = "Email alresy exists, try logging in!";
    } else {
        // $exists = false;
        if (($password == $cpassword)) {
            $sql = "INSERT INTO `users` (`name`, `email`, `phone`, `address`, `password`) 
            VALUES ('$username', '$useremail', '$userphonenumber', '$adrs', '$password')";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $showAlert = true;
                header("location: ./signin.php");
            }
        } else {
            $showerror = "Passwords do not match :/";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en" id="page" style="overflow:auto">

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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .green-button {
            background-color: green;
            color: white;
        }
    </style>

</head>

<body>

    <?php include('./header.php'); ?>


    <section class="section swatch-secondary" id="login">
        <div class="container">
            <header id="contactId" class="section-header">
                <h1 class="headline super hairline">Sign Up</h1>
            </header>
            <div class="row m-0">

                <div class="col align-middle" style="width: 40%;margin-left: 30%;">
                    <form class="contact-form align-middle" id="contactForm" novalidate="" method="POST" action="./signup.php">
                        <div class="form-group form-icon-group">
                            <input class="form-control" id="username" name="username" style="background-color: #dedede;" placeholder="Your name *" type="text">
                            <i class="fa fa-user" style="color: black;"></i>
                        </div>
                        <div class="form-group form-icon-group">
                            <input class="form-control" id="useremail" name="useremail" style="background-color: #dedede;" placeholder="Your email *" type="text">
                            <i class="fa fa-envelope" style="color: black;"></i>
                        </div>
                        <div class="form-group form-icon-group">
                            <input class="form-control" id="userphonenumber" name="userphonenumber" style="background-color: #dedede;" placeholder="Your phone no *" type="text">
                            <i class="fa fa-phone" style="color: black;"></i>
                        </div>
                        <div class="form-group form-icon-group">
                            <textarea class="form-control" id="adrs" name="adrs" style="background-color: #dedede;" placeholder="Your address *" rows="4" style="height: 118px;resize: none;"></textarea>
                            <i class="fa fa-address-book" style="color: black;"></i>
                        </div>
                        <div class="form-group form-icon-group">
                            <input class="form-control" id="password" name="password" style="background-color: #dedede;" placeholder="password" type="password">
                            <i class="fa fa-key" style="color: black;"></i>
                        </div>
                        <div class="form-group form-icon-group">
                            <input class="form-control" id="cpassword" name="cpassword" style="background-color: #dedede;" placeholder="confirm password" type="password">
                            <i class="fa fa-key" style="color: black;"></i>
                        </div>
                        <div class="form-group form-icon-group">
                            <a href="./signin.php">Already have an account? Sign In</a>
                        </div>
                        <div class="form-group text-center">
                            <button class="btn btn-primary btn-icon " type="submit" style="margin-top: 4%;">
                                Sign Up
                            </button>
                        </div>
                        <div id="messages"></div>
                    </form>
                </div>
            </div>
        </div>

    </section>

    <script src="assets/js/packages.js"></script>
    <script src="assets/js/theme.js"></script>
    <script src="../cdnjs.cloudflare.com/ajax/libs/jquery-scrollTo/2.1.2/jquery.scrollTo.js"></script>
    <script src="assets/js/contact.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>