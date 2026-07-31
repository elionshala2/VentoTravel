<?php
include "config.php";

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flutura Reisen - Ne jemi te paret...</title>
    <link rel="stylesheet" type="text/css" href="css/ballina.css">

    <style>
        /*stili ekstra*/
        .popup {
            display: none;
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #f8f8f8;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .popup p {
            margin: 0;
        }

        .popup a {
            color: #3498db;
            text-decoration: underline;
        }

        .close {
            position: absolute;
            top: 5px;
            right: 10px;
            font-size: 20px;
            cursor: pointer;
        }

    </style>
</head>

<body style="background-image: url(img/bck.jpg);">
    <header>
        <h1>Flutura Reisen</h1>
        <h3>Miresevjen, <?php echo $_SESSION['username']; ?>!</h3>
    </header>

    <nav>
        <ul>
            <li><a href="ballina.php" class="active">Reth Nesh</a></li>
            <li><a href="destinacionet.php">Destinacionet</a></li>
            <li><a href="rezervo.php">Rezervo</a></li>
            <li><a href="feedback.php">Feedback</a></li>
            <li><a href="faq.php">FAQ</a></li>
            <li><a href="historiku.php">Historiku</a></li>
            <li><a href="logout.php">Dil</a></li>
        </ul>
    </nav>

    <!--Pop up per zbritje-->
    <div id="popup" class="popup">
        <span class="close" onclick="closePopup()">&times;</span>
        <p>🎉 Oferta e vitit te ri! 5% Zbritje ne te gjitha destinacionet qe rezervohen para 31 Dhjetorit! <a href="rezervo.php">Rezervo</a> dhe eksploro boten!</p>
    </div>

    <h2 class="title">Rreth Nesh</h2>

    <div class="text">
        <p>Flutura Reisen është një agjensi udhëtimesh me një histori të pasur dhe një vizion të fuqishëm për të ofruar eksperiencat më të mahnitshme të udhëtimit. Me një angazhim të palëkundur ndaj shërbimit të klientit dhe një ekip të pasionuar të specialistëve të udhëtimit, Flutura Reisen ka arritur të bëhet një referencë e njohur në botën e udhëtimeve dhe turizmit.</p>
    </div>
    <div class="text">
        <p>Fokusohemi në ofrimin e një spektri të gjerë të shërbimeve të udhëtimit, duke përfshirë paketa të personalizuara, pushime në destinacione eksotike, udhëtime të organizuara, dhe shërbime të tjera të lidhura me udhëtimet. Me një rrjet të gjërë partnerësh dhe bashkëpunëtorësh, ne mundësojmë klientëve tanë të shijojnë udhëtimet e tyre pa stres dhe mundime.</p>
        <p>Ne po zhvillojmë gjithmonë dhe po shtojmë vlerë në platformën tonë. Mund të besoni në angazhimin tonë për përmirësimin e çdo aspekti të faqes sonë dhe për të siguruar një përvojë të jashtëzakonshme për ju. Ju ftojmë të na ndiqni për të mbetur në dijeni të çdo zhvillimi dhe inovacioni të ardhshëm</p>
        <p>Një nga elementët kryesorë të identitetit tonë është përqëndrimi në kualitetin dhe sigurinë. Ne kemi një ekip të specializuar në kontrollin e cilësisë dhe sigurisë së çdo udhëtimi që ofrojmë. Kujdesi ndaj detajeve dhe përpikmëria në planifikim është ajo që bën udhëtimet me Flutura Reisen të njëjta me aventura të paharrueshme.</p>
    </div>

    <div class="text" style="margin-bottom: 50px;">
        <p>Flutura Reisen është e përkushtuar për të ofruar jo vetëm udhëtime, por edhe një eksperiencë të plotë udhëtimi që tejkalon pritshmëritë. Për ne, çdo udhëtim është një ngjarje unike dhe e pazëvendësueshme, dhe ne punojmë për të bërë secilin moment të paharrueshëm për klientët tanë.</p>
        <p>Në qendër të misionit tonë është respekti ndaj kulturave dhe mjediseve të cilat vizitojmë. Ne inkurajojmë udhëmarrjen e të gjithë klientëve tanë për të eksploruar, për të mësuar nga përvojat e reja, dhe për të ndarë momentet e bukura me bashkëudhëtarët e tyre.</p>
        <p>Me një vizion të gjatë të përparimit dhe diversifikimit të shërbimeve tona, Flutura Reisen është në një rrugë të qëndrueshme drejt ardhmërisë së suksesshme. Ndihemi krenarë për arritjet tona deri më tani dhe entuziaste për udhëtimet e reja dhe aventurat që na presin në të ardhmen. Bashkohuni me ne në këtë rrugë të emocionuar të udhëtimit dhe zbuloni botën me Flutura Reisen!</p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showPopup();
        });

        function showPopup() {
            var popup = document.getElementById('popup');
            popup.style.display = 'block';
        }

        function closePopup() {
            var popup = document.getElementById('popup');
            popup.style.display = 'none';
        }

    </script>
</body>

</html>
