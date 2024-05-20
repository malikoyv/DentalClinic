<?php
    include 'database.php';
    
    $my_email = $_POST['email']; 
    $my_password = $_POST['password']; 
    $my_captcha = $_POST['captcha'];

    isvalid($my_email, $my_password, $my_captcha); 
?>