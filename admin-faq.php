<?php

include "config.php";

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_question'])) { // shikon nese linku eshte per te shtuar pyetje
    
    //variablat e nevojshme
    $question = $_POST["question"];
    $answer = $_POST["answer"];

    $query = "INSERT INTO faq (question, answer) VALUES ('$question', '" . addslashes($answer) . "')"; // duhet me bo addslashes($answer) pasi qet errore nese ka karaktere speciale psh ëç etj, veq per siguri

    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>alert('U shtua nje pyetje e re me sukses!')</script>";
    } else {
        echo "<script>alert('Dicka shkoi gabim! Te lutem provo perseri!');</script>";

    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_question'])) { // shikon nese linku eshte per te ndryshuar pyetje
    
    //variablat qe duhen
    $question_id = $_POST['id'];
    $new_question = $_POST['new_question'];
    $new_answer = $_POST['new_answer']; 

    //queri per me editu ose me bo update databazen faq
    $query = "UPDATE faq SET question='$new_question', answer='$new_answer' WHERE id=$question_id";

    $result = mysqli_query($conn, $query);
    // me shiku nese rezulatati ka sukses ose ka problem
    if ($result) {
        echo "<script>alert('Pyetja dhe pergjigja jane ndryshuar me sukses!')</script>";
    } else {
        echo "<script>alert('Dicka shkoi gabim! Te lutem provo perseri!');</script>";
    }
}

if (isset($_GET['remove_question'])) { // shikon nese eshte per te fshire destinacion
    $question_id = $_GET['remove_question'];

    $query = "DELETE FROM faq WHERE id=$question_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>alert('Pyetja dhe pergjigja jane fshire me sukses!')</script>";
    } else {
        echo "<script>alert('Dicka shkoi gabim! Te lutem provo perseri!')</script>";
    }
}

$query = "SELECT * FROM faq";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VentoTravel - Administrator</title>
    <link rel="stylesheet" type="text/css" href="css/adm-destinacionet.css">
    
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
        
        .shto input {
            color: white;
            background-color: #4361ee;
            border: solid 2px #2835af;
            border-radius: 20px;
            font-size: 20px;
            padding-left: 10px;
            padding-right: 10px;
            outline: none;
            transition: 0.5s;
        }

        .shto input:hover {
            background-color: #2835af;
        }

    </style>
</head>

<body style="background-image: url(img/bck.jpg)"> <!--per me ndryshu ma leht-->

    <header>
        <h1>VentoTravel</h1>
        <h3>Miresevjen, <?php echo $_SESSION['username']; ?>!</h3>
    </header>

    <nav>
        <ul>
            <li><a href="admin.php">Admin</a></li>
            <li><a href="admin-destinacionet.php">Menaxho Dest.</a></li>
            <li><a href="admin-rezervimet.php">Menaxho Rez.</a></li>
            <li><a href="admin-feedbacks.php">Menaxho Feedbacks</a></li>
            <li><a href="admin-faq.php" class="active">Menaxho FAQ</a></li>
            <li><a href="logout.php">Dil</a></li>
        </ul>
    </nav>

    <form id="editForm" style="display:none; margin: 20px; text-align: center;" method="post" action="admin-faq.php" class="editForm">
        <!--Forma per te ndryshuar destinicionin-->
        <h3>Ndrysho Pyetjen dhe Pergjigjen</h3>
        <input type="hidden" id="editQuestionId" name="id">
        <label for="new_question"><b>Pyetja:</b></label>
        <input type="text" id="new_question" name="new_question" required><br>
        <label for="new_answer"><b>Pergjigja:</b></label>
        <input type="text" id="new_answer" name="new_answer" required><br>
        <button type="submit" name="edit_question" class="edit-button" style="margin-top: 10px;">Ndrysho</button>

        <button type="button" onclick="hideEditForm()" class="edit-button" style="margin-top: 10px;">Anulo</button>

    </form>

    <form method="post" action="admin-faq.php" class="shto" style="margin: 20px; text-align: center;">
        <!--Metoda per te shtuar destinicion-->
        <h3>Shto Pyetje dhe Pergjigje</h3>
        <label for="question">Pyetja:</label>
        <input type="text" name="question" required><br>
        <label for="answer">Pergjigja:</label>
        <input type="text" name="answer" required><br>
        <button type="submit" name="add_question" style="margin-top: 10px;">Shto Pyetjen</button>
    </form>

    <ul class="list" style="margin: 20px; text-align: center;">
        <h3>Lista e pyetjeve</h3>
        <!--lista -->
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<li style='margin: 40px;'>";
            echo "<b>Pyetja:</b> {$row['question']},<br> <b>Pergjigja:</b> {$row['answer']}";
            echo " <br> <a href='#' onclick='showEditForm({$row['id']}, \"{$row['question']}\", \"{$row['answer']}\");'><b>Ndrysho</b></a>";
            echo " - <a href='admin-faq.php?remove_question={$row['id']}'><b>Fshij</b></a>";
            echo "<br>";
            echo "<br>";
            echo "</li>";
        }
        ?>
    </ul>

    <script>
        function showEditForm(questionId, question, answer) {
            document.getElementById("editQuestionId").value = questionId;
            document.getElementById("new_question").value = question;
            document.getElementById("new_answer").value = answer;
            document.getElementById("editForm").style.display = "block";
        }

        function hideEditForm() {
            document.getElementById("editForm").style.display = "none";
        }

    </script>
</body>

</html>
