<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $feedbackMessage = mysqli_real_escape_string($conn, $_POST['feedbackMessage']);

    // e merr id-n prej sesionit
    $user_id = isset($_SESSION['id']) ? $_SESSION['id'] : 0; // nese dicka shkon gabim, e qet 0

    // per me i insertu n databaz
    $insertFeedback = "INSERT INTO feedbacks (user_id, email, message, submission_date) VALUES ('$user_id', '$email', '$feedbackMessage', NOW())";

    if (mysqli_query($conn, $insertFeedback)) {
        echo "<script>alert('Feedback-u u dorezua me sukses!');</script>";
    } else {
        echo "<script>alert('Dicka shkoi gabim!')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flutura Reisen - Feedback</title>
    <link rel="stylesheet" type="text/css" href="css/ballina.css">
    <style>
        /*stili ekstra*/
        .feedback-form-container {
            margin: 70px auto;
            display: flex;
            flex-direction: column;
            border: solid 4px #2835af;
            width: 340px;
            padding: 40px;
            border-radius: 20px;
            text-align: end;
            background: #cccaca;
        }

        .feedback-form-container input {
            border: solid 4px #2835af;
            border-radius: 20px;
            background-color: #fff;
            outline: none;
            margin: 5px;
            padding: 5px;
            font-size: 20px;
            margin-top: 5px;
            transition: 0.5s;
        }

        .feedback-form-container input:hover {
            background-color: #4361ee;
            color: white;
        }

        .feedback-form-container label {
            font-weight: 700;
        }
        
        .feedback-form-container textarea {
            width: 230px;
            border: solid 4px #2835af;
            border-radius: 20px;
            outline: none;
            padding: 10px;
            font-family: arial;
            font-size: 15px;
            transition: 0.5s;
        }
        
        .feedback-form-container textarea:hover {
            background-color: #4361ee;
            color: white;
        }

        #submitButton {
            width: 260px;
            margin-top: 10px;
            font-weight: 700;
            transition: 0.5s;
        }

        #submitButton:hover {
            background-color: #4361ee;
            color: white;
            font-weight: 700;
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
    </style>
</head>

<body style="background-image: url(img/bck.jpg);">

    <header>
        <h1>Flutura Reisen</h1>
        <h3>Miresevjen, <?php echo $_SESSION['username']; ?>!</h3>
    </header>

    <nav>
        <ul>
            <li><a href="ballina.php">Reth Nesh</a></li>
            <li><a href="destinacionet.php">Destinacionet</a></li>
            <li><a href="rezervo.php">Rezervo</a></li>
            <li><a href="feedback.php" class="active">Feedback</a></li>
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
    
    <h2 class="title">FeedBack</h2>

    <div class="feedback-form-container">
        <form action="feedback.php" method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>

            <label for="feedbackMessage">Mesazhi:</label>
            <textarea id="feedbackMessage" name="feedbackMessage" rows="4" required></textarea>

            <input type="submit" value="Dergo" id="submitButton">
        </form>
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
