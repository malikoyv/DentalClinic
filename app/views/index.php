<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Clinic - Main page</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>
    <link rel="stylesheet" href="../../public/css/styles.css">
    <link rel="stylesheet" href="../../public/css/index.css">
    <style>
        #myVideo {
            filter: brightness(60%);
        }
        .doctor-card img {
            width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <?php include 'shared_navbar.php'; ?>
    <div class="container">
        <div class="row">
            <h1 class="text-center">Project</h1>
            <h2 class="text-center">Dental clinic management system web application</h2>
            <br>
            <div class="col-lg-5 p-4">
                <h2>Sign  in our clinic and book your first visit!</h2>
                <br>
                <a href="patient_register.php" class="btn btn-lg btn-primary m-2">Sign up</a>
                <a href="patient_login.php" class="btn btn-lg btn-secondary m-2">Log in</a>
            </div>
            <div class="col-lg-7 p-4">
                <video autoplay muted loop id="myVideo" class="w-100">
                    <source src="../../public/videos/start_video2.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>
        <div id="our-doctors" class="row">
            <h2 class="text-center mt-5">Our Doctors</h2>
            <div class="col-md-4 doctor-card">
                <img src="https://www.pinnacledentalgroupmi.com/wp-content/uploads/2023/11/general-dentistry-img.jpeg" alt="Dr. Kyrylo Krainiuk">
                <h3>Dr. Kyrylo Krainiuk</h3>
                <p>Specialization: Dentist-Surgeon</p>
                <p>Biography: Dr. Kyrylo Krainiuk graduated from the Medical University of Gdańsk</p>
            </div>
            <div class="col-md-4 doctor-card">
                <img src="https://www.west10thdental.com/wp-content/uploads/iStock-1147578995-920x614.jpg" alt="Dr. Jan Nowak">
                <h3>Dr. Jan Nowak</h3>
                <p>Specialization: Prosthodontist</p>
                <p>Biography: Dr. Jan Nowak specializes in prosthodontics with many years of experience.</p>
            </div>
            <div class="col-md-4 doctor-card">
                <img src="https://www.shutterstock.com/image-photo/european-mid-dentist-woman-smiling-600nw-1938573190.jpg" alt="Dr. Maria Wiśniewska">
                <h3>Dr. Maria Wiśniewska</h3>
                <p>Specialization: Orthodontist</p>
                <p>Biography: Dr. Maria Wiśniewska is an outstanding specialist in orthodontics.</p>
            </div>
        </div>
        <div id="news" class="row mt-5">
            <h2 class="text-center">News</h2>
            <div class="col-md-12">
                <div class="alert alert-info">
                    <h4>Change in Opening Hours</h4>
                    <p>We inform you that from June 1, 2024, the clinic will be open from 8:00 to 18:00.</p>
                </div>
                <div class="alert alert-warning">
                    <h4>Public Holidays</h4>
                    <p>The clinic will be closed from August 15-17 due to a national holiday.</p>
                </div>
                <div class="alert alert-success">
                    <h4>Special Event</h4>
                    <p>We invite you to free dermatological consultations on May 25, 2024.</p>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY"></script>
    <script type="module">
        // Import the functions you need from the SDKs you need
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
        // TODO: Add SDKs for Firebase products that you want to use
        // https://firebase.google.com/docs/web/setup#available-libraries

        // Your web app's Firebase configuration
        const firebaseConfig = {
            apiKey: "AIzaSyAxzVXAIo_8UKc7BZFErEL42Gw8TWmEkXA",
            authDomain: "dentalclinic-wprg.firebaseapp.com",
            projectId: "dentalclinic-wprg",
            storageBucket: "dentalclinic-wprg.appspot.com",
            messagingSenderId: "1039400925600",
            appId: "1:1039400925600:web:9bef5273aa15f72dcc8cc5"
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
    </script>
</body>
</html>