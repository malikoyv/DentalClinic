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
</head>
<body>
    <?php include 'shared_navbar.php'; ?> <br><br>
    <h1>Price list</h1>
    <div class="price-list">
        <div class="section">
            <h2>diagnostics</h2>

            <div class="grid-section">
                <div class="grid-item">
                    <h3>Specialist consultation, examination</h3>
                    <p>150-200 PLN</p>
                </div>
                <div class="grid-item">
                    <h3>Dental X-ray</h3>
                    <p>40 PLN</p>
                </div>
                <div class="grid-item">
                    <h3>Digital panoramic X-ray (panorex)</h3>
                    <p>110 PLN</p>
                </div>
                <div class="grid-item">
                    <h3>DSD - Digital Smile Design</h3>
                    <p>1500-2000 PLN</p>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>conservative dentistry</h2>
            <div class="item">
                <span class="description">Hygienization (scaling, sandblasting, polishing, fluoride treatment, hygiene instruction)</span>
                <span class="price">350-450 PLN</span>
            </div>
            <div class="item">
                <span class="description">In-office teeth whitening</span>
                <span class="price">1400 PLN</span>
            </div>
            <div class="item">
                <span class="description">Home teeth whitening kit</span>
                <span class="price">1200 PLN</span>
            </div>
            <div class="item">
                <span class="description">Tooth filling</span>
                <span class="price">350-550 PLN</span>
            </div>
            <div class="item">
                <span class="description">Fiber-reinforced tooth restoration</span>
                <span class="price">450-650 PLN</span>
            </div>
            <div class="item">
                <span class="description">Bonding</span>
                <span class="price">from 700 PLN</span>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
