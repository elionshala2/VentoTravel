<?php

include "config.php";

session_start();

$sql = "SELECT * FROM faq";
$result = mysqli_query($conn, $sql);

$questions = [];
if ($result) { // me shiku nese ka lidhje te suksesshme
    while ($row = mysqli_fetch_assoc($result)) {
        $questions[] = $row;
    }
} else {
    echo "Dicka shkoi gabim!";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VentoTravel - FAQ</title>
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

        .question {
            background-color: #4361ee;
            border: solid 3px #2835af;
            border-radius: 20px;
            margin: 20px;
            padding: 20px;
            color: white;
            transition: 0.5s;
        }

        .question:hover {
            background-color: #2935af;
        }

        .title {
            font-size: 30px;
            margin-top: 30px;
            margin-left: 40px;
            margin-bottom: 30px;
            color: white;
        }

    </style>
</head>

<body style="background-image: url(img/bck.jpg)">
    <header>
        <h1>VentoTravel</h1>
        <h3>Miresevjen, <?php echo $_SESSION['username']; ?>!</h3>
    </header>

    <nav>
        <ul>
            <li><a href="ballina.php">Reth Nesh</a></li>
            <li><a href="destinacionet.php">Destinacionet</a></li>
            <li><a href="rezervo.php">Rezervo</a></li>
            <li><a href="feedback.php">Feedback</a></li>
            <li><a href="faq.php" class="active">FAQ</a></li>
            <li><a href="historiku.php">Historiku</a></li>
            <li><a href="logout.php">Dil</a></li>
        </ul>
    </nav>

    <!--Pop up per zbritje-->
    <div id="popup" class="popup">
        <span class="close" onclick="closePopup()">&times;</span>
        <p>🎉 Oferta e vitit te ri! 5% Zbritje ne te gjitha destinacionet qe rezervohen para 31 Dhjetorit! <a href="rezervo.php">Rezervo</a> dhe eksploro boten!</p>
    </div>

    <h2 class="title">Frequently Asked Questions (Pyetjet me te shpeshta)</h2>

    <?php
    foreach ($questions as $question) {
        echo "<div class='question'>";
        echo "<h3>{$question['question']}</h3>";
        echo "<p>{$question['answer']}</p>";
        echo "</div>";
    }
    ?>

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
