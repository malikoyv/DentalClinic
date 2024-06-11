<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Clinic - Price list</title>
    <link rel="stylesheet" href="../../public/css/pricelist.css">
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <style>
        p {
            color: black;
        }
        span {
            color: black;
        }
    </style>
</head>
<body>
    <?php include 'shared_navbar.php'; ?> <br><br>
    <h1>Location</h1>
    <div class="row mt-5">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d691.3036803453579!2d18.648032373156674!3d54.3524015120588!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46fd7366854c6ad7%3A0xa93b61471c5634f9!2sPJATK%20Gda%C5%84sk%20-%20Wydzia%C5%82%20Informatyki!5e0!3m2!1sen!2spl!4v1716741862361!5m2!1sen!2spl" style="border:0;" width="500" height="400" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
    <h1>Contact information</h1>
    <div>
        <p>Telefon: +48 111 222 333</p>
        <p>Email: <a href="mailto:info@dentalclinic.com">info@dentalclinic.com</a></p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>