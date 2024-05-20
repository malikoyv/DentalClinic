<?php 
    include 'database.php';

    $email = $_POST['email'];
    $pass1 = $_POST['password1'];
    $pass2 = $_POST['password2'];

    $usercaptcha = $_POST['captcha'];
    $captcha = $_SESSION['captcha'];
   
    if(strcmp($usercaptcha,$captcha)==0)
    {
        if(strcmp($pass1,$pass2)==0) {
            createUser($email, $pass1);
        }
        else {
            $_SESSION['error'] = "&nbsp; password do not match";
            header('location:newuser.php');
        }
    }
    else {
        $_SESSION['error'] = "&nbsp; Invalid captcha code";
        header('location:newuser.php');
    }
 ?>