<?php

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
    <title>Flutura Reisen - Destinacionet</title>
    <link rel="stylesheet" type="text/css" href="css/ballina.css">
    <style>
        .destination-container2 {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .destination-container:hover {
            width: 550px;

        }

        .destination-container {
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-radius: 20px;
            padding: 20px;
            margin: 20px;
            border: solid 3px blue;
            width: 500px;
            background-color: #d0d0d0;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-right: 20px;
            transition: 1s;
        }

        .destination-container img {
            max-width: 100%;
            border-radius: 10px;
            margin-bottom: 10px;
        }

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

        #sort-options {
            background-color: #2835af;
            width: 300px;
            justify-content: center;
            text-align: center;
            padding: 10px;
            color: white;
            border-radius: 20px;
        }

        #sort-options select {
            background: none;
            border: solid 3px #4361ee;
            border-radius: 10px;
            color: white;
            padding: 3px;
            outline: none;
        }
        
        .justify-right {
            justify-content: right;
            align-content: right;
            align-items: right;
            margin-left: 69%; 
        }
        
        #sort-select option {
            color: black;
        }

        .price {
            color: #a5a5a5;
        }
    </style>
</head>

<body style="background-image: url(img/bck.jpg);">

    <header>
        <h1>Flutura Reisen</h1>
        <h3>Miresevjen, <?php echo $_SESSION['username'];?>!</h3>
    </header>

    <nav>
        <ul>
            <li><a href="ballina.php">Reth Nesh</a></li>
            <li><a href="destinacionet.php" class="active">Destinacionet</a></li>
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

    <h2 class="title">Destinacionet</h2>
    <div class="justify-right">
    <div id="sort-options">
        <label for="sort-select">Rendit sipas:</label>
        <select id="sort-select" onchange="sortDestinations()">
            <option value="pricelow">Cmimit - Lire - Shtrenjte</option>
            <option value="pricehigh">Cmimit - Shtrenjte - Lire</option>
            <option value="newest">Dates - Me te rejat</option>
            <option value="oldest">Dates - Me te vjetrat</option>
        </select>
    </div>
        </div>

    <div class='destination-container2'>

        <?php
            include 'config.php';

            // selektimi nga tabela destinations
            $sql = "SELECT * FROM destinations";
            $result = mysqli_query($conn, $sql);

            if ($result->num_rows > 0) {
                while ($destination = mysqli_fetch_assoc($result)) {
                    $zbritja = $destination['price'] / 100 * 5;
                    $cmimi = $destination['price'] - $zbritja;
                    echo "<div class='destination-container' data-price='{$destination['price']}' data-id='{$destination['id']}'>";
                    echo "<section class='destination-details'>";
                    echo "<img src='{$destination['photo_url']}' alt='{$destination['title']}' style='width: 600px; border-radius: 10px;'>";
                    echo "<b style='font-weight: 1000;'><h2>{$destination['title']}</h2></b>";
                    echo "<p class='small-description'>{$destination['small_description']}</p>";
                    echo "<p class='big-description'>{$destination['big_description']}</p>";
                    echo "<p class='price'>Cmimi: <b style='font-size: 18px; color='#a5a5a5'>{$destination['price']} Euro</p><p>Vetem {$cmimi} Euro!</p></b>";
                    echo "<p class='id'>ID: {$destination['id']}</p>";
                    echo "</section>";
                    echo "</div>";
            }
            } else {
                echo "<h4 class='destnukugjet'>Nuk ka destinacion në dispozicion për momentin, të lutem provo më vonë!<h4>";
            }
        ?>
    </div>

    <script>
        //per sort
        function sortDestinations() {
            var sortSelect = document.getElementById('sort-select');
            var sortOption = sortSelect.value;
            var destinationContainer = document.querySelector('.destination-container2');
            var destinationArray = Array.from(destinationContainer.children); // per me kriju array nga femijte (objektet brenda) destinationContainer

            destinationArray.sort(function(a, b) {
                var aValue, bValue;

                switch (sortOption) { 
                    case 'pricehigh': // kur opsioni i selektum eshte me i shtrenjti
                        aValue = parseFloat(a.dataset.price);
                        bValue = parseFloat(b.dataset.price);
                        break;
                    case 'newest': // kur opsioni i selektum eshte me i riu
                        aValue = parseInt(a.dataset.id);
                        bValue = parseInt(b.dataset.id);
                        break;
                    default:
                        return 0;
                    case 'pricelow': // kur opsioni i selektum eshte me i liri
                        aValue = parseFloat(b.dataset.price);
                        bValue = parseFloat(a.dataset.price);
                        break;
                    case 'oldest': // kur opsioni i selektum eshte me i liri
                        aValue = parseInt(b.dataset.id);
                        bValue = parseInt(a.dataset.id);
                        // case default nuk eshte pasi nuk ka ndonje gje 
                }

                return bValue - aValue;
            });

            destinationContainer.innerHTML = '';

            destinationArray.forEach(function(destination) {
                destinationContainer.appendChild(destination);
            });
        }
        
        sortDestinations();

        // per popup   
        document.addEventListener('DOMContentLoaded', function() { // kur faqja loadohet, me thir funksionin showpopup per me bo display block (me shfaq)
            showPopup();
        });

        function showPopup() { // funksioni per popup, per ta hapur
            var popup = document.getElementById('popup');
            popup.style.display = 'block';
        }

        function closePopup() { // funksioni per popup, per ta mbyllur
            var popup = document.getElementById('popup');
            popup.style.display = 'none';
        }

    </script>

</body>

</html>
