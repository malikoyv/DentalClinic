<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projekt WPRG</title>
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
            <h1 class="text-center">Projekt</h1>
            <h2 class="text-center">Aplikacja typu zarządzanie poradnią lekarską</h2>
            <br>
            <div class="col-lg-5 p-4">
                <h2>Zarejestruj się w naszym gabinecie i zarezerwuj swoją pierwszą wizytę!</h2>
                <br>
                <a href="patient_register.php" class="btn btn-lg btn-primary m-2">Zarejestruj się teraz</a>
                <a href="patient_login.php" class="btn btn-lg btn-secondary m-2">Zaloguj się</a>
            </div>
            <div class=" col-lg-7 p-4">
                <video autoplay muted loop id="myVideo" class="w-100">
                    <source src="../../public/videos/start_video2.mp4" type="video/mp4">
                    Twoja przeglądarka nie obsługuje tagu video.
                </video>
            </div>
        </div>
        <div class="row">
            <h2 class="text-center mt-5">Nasi Lekarze</h2>
            <div class="col-md-4 doctor-card">
                <img src="https://www.pinnacledentalgroupmi.com/wp-content/uploads/2023/11/general-dentistry-img.jpeg" alt="Dr. Kyrylo Krainiuk">
                <h3>Dr. Kyrylo Krainiuk</h3>
                <p>Specjalizacja: Dentysta-Chirurg</p>
                <p>Biografia: Dr. Kyrylo Krainiuk ukończył studia medyczne na Uniwersytecie Medycznym w Gdańsku</p>
            </div>
            <div class="col-md-4 doctor-card">
                <img src="https://www.west10thdental.com/wp-content/uploads/iStock-1147578995-920x614.jpg" alt="Dr. Jan Nowak">
                <h3>Dr. Jan Nowak</h3>
                <p>Specjalizacja: Protetyk stomatologiczny</p>
                <p>Biografia: Dr. Jan Nowak specjalizuje się w protetyk stomatologiczny z wieloletnim doświadczeniem.</p>
            </div>
            <div class="col-md-4 doctor-card">
                <img src="https://www.shutterstock.com/image-photo/european-mid-dentist-woman-smiling-600nw-1938573190.jpg" alt="Dr. Maria Wiśniewska">
                <h3>Dr. Maria Wiśniewska</h3>
                <p>Specjalizacja: Ortodont</p>
                <p>Biografia: Dr. Maria Wiśniewska jest wybitnym specjalistą w dziedzinie ortodoncji.</p>
            </div>
        </div>
        <div class="row mt-5">
            <h2 class="text-center">Lokalizacja Poradni</h2>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d691.3036803453579!2d18.648032373156674!3d54.3524015120588!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46fd7366854c6ad7%3A0xa93b61471c5634f9!2sPJATK%20Gda%C5%84sk%20-%20Wydzia%C5%82%20Informatyki!5e0!3m2!1sen!2spl!4v1716741862361!5m2!1sen!2spl" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <div class="row mt-5">
            <h2 class="text-center">Aktualności</h2>
            <div class="col-md-12">
                <div class="alert alert-info">
                    <h4>Zmiana godzin otwarcia</h4>
                    <p>Informujemy, że od 1 czerwca 2024 poradnia będzie czynna od 8:00 do 18:00.</p>
                </div>
                <div class="alert alert-warning">
                    <h4>Dni wolne od pracy</h4>
                    <p>W dniach 15-17 sierpnia poradnia będzie nieczynna z powodu święta narodowego.</p>
                </div>
                <div class="alert alert-success">
                    <h4>Specjalne wydarzenie</h4>
                    <p>Zapraszamy na bezpłatne konsultacje dermatologiczne 25 maja 2024.</p>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY"></script>
</body>
</html>
