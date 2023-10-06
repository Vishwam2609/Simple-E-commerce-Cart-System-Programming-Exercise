<?php

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if(isset($_POST["send"])){
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'pansuriyashivam@gmail.com';                 // SMTP username
    $mail->Password = 'xnaoqldxrljxleeq';                           
    $mail->SMTPSecure = 'ssl';  
    $mail->Port = 465;
    
    $mail->setFrom($_POST["email"]);

    $mail->addAddress('info@happycreations.co.in');  //$_POST["email"]

    $mail->isHTML(true);
    $mail->FromName = $_POST["email"];
    $mail->Subject = "Idea Submission by " . $_POST["username"];
    $mail->Body = $_POST['message'];

    $mail->send();

    echo"
        <script>
            alert('Message Sent');
        </script>;
    ";
    header("location: ./index.php#contact");
}
