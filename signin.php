<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    header("location: ./profile.php");
}
?>

<?php
$login = false;
$showerror = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require('./dbconnect.php');
    $useremail = $_POST['useremail'];
    $password = $_POST['password'];

    $sql = "SELECT * from users where email='$useremail' and password='$password'";
    $result = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);
    if ($num == 1) {
        $login = true;
        session_start();
        $_SESSION['loggedin'] = true;
        $_SESSION['useremail'] = $useremail;

        header("location: ./profile.php");
    } else {
        $showerror = "invalid cradentials";
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
        <div class="container align-middle">
            <header id="contactId" class="section-header">
                <h1 class="headline super hairline">Sign In</h1>
            </header>
            <div class="row m-0">
                <div class="col align-middle" style="width: 40%;margin-left: 30%;">
                    <form class="contact-form align-middle" id="contactForm" novalidate="" method="POST" action="./signin.php">
                        <div class="form-group form-icon-group">
                            <input class="form-control" style="background-color: #dedede;" id="useremail" name="useremail" placeholder="Your email *" type="text">
                            <i class="fa fa-envelope" style="color: black;"></i>
                        </div>
                        <div class="form-group form-icon-group">
                            <input class="form-control" style="background-color: #dedede;" id="password" name="password" placeholder="password" type="password">
                            <i class="fa fa-key" style="color: black;"></i>
                        </div>
                        <div class="form-group form-icon-group">
                            <a href="./signup.php">Don't have an account? Sign Up</a>
                        </div>
                        <div class="form-group text-center">
                            <button class="btn btn-primary btn-icon " type="submit" style="margin-top: 4%;">
                                <center>Sign In</center>
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