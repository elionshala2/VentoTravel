<?php

include_once 'config.php';

session_start();

error_reporting(0);

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['username'] !== 'admin') { // me shiku nese eshte ne llogari me perdoruesin admin
    header("Location: index.php");
    exit();
}

// nese eshte per me ndryshu feedback 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_feedback'])) {
    $feedback_id = $_POST['id'];
    $new_email = $_POST['new_email'];
    $new_message = $_POST['new_message'];

    $query = "UPDATE feedbacks 
          SET email='$new_email', message='$new_message' 
          WHERE feedback_id=$feedback_id";

    $result = mysqli_query($conn, $query);
    // nese rezultati eshte me sukses, ose nese ka errore
    if ($result) {
        echo "<script>alert('Feedback-u u ndryshua me sukses!')</script>";
    } else {
        echo "<script>alert('Dicka shkoi gabim! Te lutem provo perseri!')</script>";
    }
}

// per me fshi feedback
if (isset($_GET['remove_feedback'])) {
    $feedback_id = $_GET['remove_feedback'];

    $query = "DELETE FROM feedbacks WHERE feedback_id=$feedback_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>alert('Feedback-u u fshi me sukses!')</script>";
    } else {
        echo "<script>alert('Dicka shkoi gabim! Te lutem provo perseri!')</script>";
    }
}

$query = "SELECT * FROM feedbacks";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Elion Shala">
    <link rel="stylesheet" type="text/css" href="css/adm-destinacionet.css">
    <title>Flutura Tourist - Administrator</title>

    <style>
        /*stili ekstra*/
        input {
            padding: 5px;
            margin-top: 10px;
        }

        .edit-button {
            padding: 5px;
            background: none;
            border: solid 3px #2835af;
            border-radius: 20px;
            font-size: 20px;
            color: white;
            margin-top: 10px;
            padding-left: 60px;
            padding-right: 60px;
            transition: background-color 0.5s;
        }

        .edit-button:hover {
            background-color: #2835af;
        }

        input {
            margin-top: 10px;
        }

    </style>
</head>

<body style="background-image: url(img/bck.jpg)">
    <header>
        <h1>Flutura Reisen</h1>
        <h3>Miresevjen, <?php echo $_SESSION['username']; ?>!</h3>
    </header>

    <nav>
        <ul>
            <li><a href="admin.php">Admin</a></li>
            <li><a href="admin-destinacionet.php">Menaxho Dest.</a></li>
            <li><a href="admin-rezervimet.php">Menaxho Rez.</a></li>
            <li><a href="admin-feedbacks.php" class="active">Menaxho Feedbacks</a></li>
            <li><a href="admin-faq.php">Menaxho FAQ</a></li>
            <li><a href="logout.php">Dil</a></li>
        </ul>
    </nav>

    <div class="container">

        <form style="display: none;" id="editForm" method="post" action="admin-feedbacks.php">
            <h3>Ndrysho Feedback</h3>
            <input type="hidden" id="editFeedbackId" name="id">
            <label for="new_email"><b>Email:</b></label>
            <input type="text" id="new_email" name="new_email" required><br>
            <label for="new_message"><b>Mesazhi:</b></label>
            <input type="text" id="new_message" name="new_message" required><br>
            <button type="submit" name="edit_feedback" class="edit-button" style="margin-top: 10px;">Ndrysho</button>
            <button type="button" onclick="hideEditForm()" class="edit-button" style="margin-top: 10px;">Anulo</button>
        </form>

        <ul class="list">
            <h3>Lista e Feedback-eve</h3>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<li>";
                echo "<b>Email:</b> {$row['email']},<br> <b>Mesazhi:</b> {$row['message']}, <br> <b>Data e Dorezimit:</b> {$row['submission_date']}";
                echo " <br> <a href='#' onclick=\"showEditForm('{$row['feedback_id']}', '{$row['email']}', '{$row['message']}');\"><b>Ndrysho</b></a>";
                echo " - <a href='admin-feedbacks.php?remove_feedback={$row['feedback_id']}'><b>Fshij</b></a>";
                echo "<br>";
                echo "<br>";
                echo "</li>";
            }
            ?>
        </ul>
    </div>

    <script>
        function showEditForm(feedbackId, email, message) {
            document.getElementById("editFeedbackId").value = feedbackId;
            document.getElementById("new_email").value = email;
            document.getElementById("new_message").value = message;
            document.getElementById("editForm").style.display = "block";
        }

        function hideEditForm() {
            document.getElementById("editForm").style.display = "none";
        }

    </script>
</body>

</html>
