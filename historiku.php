<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'config.php';

$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : 0;

$reservationsQuery = "SELECT * FROM rezervimet WHERE user_id = $user_id";
$reservationsResult = mysqli_query($conn, $reservationsQuery);

$feedbacksQuery = "SELECT * FROM feedbacks WHERE user_id = $user_id";
$feedbacksResult = mysqli_query($conn, $feedbacksQuery);

if (isset($_GET['remove_reservation'])) {
    $reservation_id = $_GET['remove_reservation'];

    //qe perdoruesi mos me mujt me fshi rezervimet e tjeter kujt
    $query = "DELETE FROM rezervimet WHERE id=$reservation_id AND user_id=$user_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>alert('Rezervimi u fshi me sukses!')</script>";
    } else {
        echo "<script>alert('Dicka shkoi gabim! Te lutem provo perseri!')</script>";
    }
}

if (isset($_GET['remove_feedback'])) {
    $feedback_id = $_GET['remove_feedback'];

    //qe perdoruesi mos me mujt me fshi feedbackat e tjeter kujt
    $query = "DELETE FROM feedbacks WHERE feedback_id=$feedback_id AND user_id=$user_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>alert('Feedback-u u fshi me sukses!')</script>";
    } else {
        echo "<script>alert('Dicka shkoi gabim! Te lutem provo perseri!')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flutura Reisen - Historiku</title>
    <link rel="stylesheet" type="text/css" href="css/ballina.css">
    <style>
        .rezervim {
            background-color: #4361ee;
            border: solid 3px #2835af;
            border-radius: 20px;
            padding: 40px;
            margin: 40px;
            margin-left: 80px;
            width: 700px;
            font-family: arial;
            font-size: 20px;
            color: white;
            transition: 0.5s;
        }

        .rezervimet-section h3 {
            padding-left: 60px;
            color: white;
            font-size: 25px;
        }

        .rezervim:hover {
            background-color: #2935af;
        }

        .nukkarezervime {
            font-size: 25px;
            color: white;
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
            <li><a href="feedback.php">Feedback</a></li>
            <li><a href="faq.php">FAQ</a></li>
            <li><a href="historiku.php" class="active">Historiku</a></li>
            <li><a href="logout.php">Dil</a></li>
        </ul>
    </nav>

    <h2 class="title">Historiku</h2>

    <div class="historiku-container">
        <!-- Reservation Section -->
        <section class="rezervimet-section">
            <h3>Rezervimet</h3>
            <?php
            if (mysqli_num_rows($reservationsResult) > 0) {
                while ($reservation = mysqli_fetch_assoc($reservationsResult)) {
            echo "<div class='rezervim'>";
            echo "<p>ID e Rezervimit: {$reservation['id']}</p>";
            echo "<p>Emri dhe Mbiemri: {$reservation['fullname']}</p>";
            echo "<p>Email: {$reservation['email']}</p>";
            echo "<p>ID-ja e Destinacionit: {$reservation['destination_id']}</p>";
            echo "<p>Gjinia: {$reservation['gender']}</p>";
            echo "<p>Adresa: {$reservation['address']}</p>";
            echo "<p>Data e Rezervimit: {$reservation['reservation_date']}</p>";
            echo "<a href='historiku.php?remove_reservation={$reservation['id']}'><b>Fshij Rezervimin</b></a>";
            echo "<br>";
            echo "<br>";
            echo "</div>";
                }
            } else {
                echo "<div class='rezervim'>";
                echo "<p class='nukkarezervime'>Nuk ka rezervime.</p>";
                echo "</div>";
            }
            ?>
        </section>

        <!-- Feedback Section -->
        <section class="rezervimet-section">
            <h3>Feedback-et</h3>
            <?php
            if (mysqli_num_rows($feedbacksResult) > 0) {
                while ($feedback = mysqli_fetch_assoc($feedbacksResult)) {
                    echo "<div class='rezervim'>";
                    echo "<p>ID e Feedback-ut: {$feedback['feedback_id']}</p>";
                    echo "<p>Email: {$feedback['email']}</p>";
                    echo "<p>Mesazhi: {$feedback['message']}</p>";
                    echo "<p>Data e Dorezimit: {$feedback['submission_date']}</p>";
                    echo "<a href='historiku.php?remove_feedback={$feedback['feedback_id']}'><b>Fshij Feedback-un</b></a>";
                    echo "</div>";
                }
            } else {
                echo "<div class='rezervim'>";
                echo "<p class='nukkarezervime'>Nuk ka feedback-e.</p>";
                echo "</div>";
            }
            ?>
        </section>
    </div>

    <script>
        function showEditForm(id, fullname, email, phone, destination_id, gender, address, reservation_date, user_id) {
            document.getElementById("editReservationId").value = id;
            document.getElementById("new_fullname").value = fullname;
            document.getElementById("new_email").value = email;
            document.getElementById("new_phone").value = phone;
            document.getElementById("new_destination_id").value = destination_id;
            document.getElementById("new_gender").value = gender;
            document.getElementById("new_address").value = address;
            document.getElementById("new_reservation_date").value = reservation_date;
            document.getElementById("new_user_id").value = user_id;
            document.getElementById("editForm").style.display = "block";
        }

        function hideEditForm() {
            document.getElementById("editForm").style.display = "none";
        }

        function closePopup() {
            var popup = document.getElementById('popup');
            popup.style.display = 'none';
        }

    </script>

</body>

</html>