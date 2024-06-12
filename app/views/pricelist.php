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
            <h2>diagnostyka</h2>

            <div class="grid-section">
                <div class="grid-item">
                    <h3>Konsultacja specjalistyczna, badanie</h3>
                    <p>150-200 PLN</p>
                </div>
                <div class="grid-item">
                    <h3>RTG zęba</h3>
                    <p>40 PLN</p>
                </div>
                <div class="grid-item">
                    <h3>Cyfrowe zdjęcie pantomograficzne RTG</h3>
                    <p>110 PLN</p>
                </div>
                <div class="grid-item">
                    <h3>DSD - Cyfrowe projektowanie uśmiechu</h3>
                    <p>1500-2000 PLN</p>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>stomatologia zachowawcza</h2>
            <div class="item">
                <span class="description">Higienizacja (scaling, piaskowanie, polishing, fluoryzacja, instruktaż higieny)</span>
                <span class="price">350-450 PLN</span>
            </div>
            <div class="item">
                <span class="description">Wybielanie gabinetowe</span>
                <span class="price">1400 PLN</span>
            </div>
            <div class="item">
                <span class="description">Wybielanie nakładkowe</span>
                <span class="price">1200 PLN</span>
            </div>
            <div class="item">
                <span class="description">Wypełnienie</span>
                <span class="price">350-550 PLN</span>
            </div>
            <div class="item">
                <span class="description">Odbudowa zęba na włóknie szklanym</span>
                <span class="price">450-650 PLN</span>
            </div>
            <div class="item">
                <span class="description">Bonding</span>
                <span class="price">od 700 PLN</span>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
