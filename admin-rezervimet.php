<?php
include_once 'config.php';
session_start();
error_reporting(0);

// per me shiku nese osht i kyqun
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// me shiku nese perdoruesi nuk eshte 'admin' (perdoruesi), nese nuk eshte me hjek
if ($_SESSION['username'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_reservation'])) { // shikon nese linku eshte per te ndryshuar destinacion
    $reservation_id = $_POST['id'];
    $new_fullname = $_POST['new_fullname'];
    $new_email = $_POST['new_email'];
    $new_phone = $_POST['new_phone'];
    $new_destination_id = $_POST['new_destination_id'];
    $new_gender = $_POST['new_gender'];
    $new_address = $_POST['new_address'];
    $new_reservation_date = $_POST['new_reservation_date'];
    $new_user_id = $_POST['new_user_id'];

    $query = "UPDATE rezervimet 
          SET fullname='$new_fullname', email='$new_email', 
          phone='$new_phone', destination_id='$new_destination_id', gender='$new_gender', address='$new_address', reservation_date='$new_reservation_date', user_id='$new_user_id' 
          WHERE id=$reservation_id";


    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>alert('Rezervimi u ndryshua me sukses!')</script>";
    } else {
        echo "<script>alert('Dicka shkoi gabim! Te lutem provo perseri!')</script>";
    }
}

if (isset($_GET['remove_reservation'])) { // per te fshire destinacion
    $reservation_id = $_GET['remove_reservation'];

    $query = "DELETE FROM rezervimet WHERE id=$reservation_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>alert('Rezervimi u fshi me sukses!')</script>";
    } else {
        echo "<script>alert('Dicka shkoi gabim! Te lutem provo perseri!')</script>";
    }
}

$query = "SELECT * FROM rezervimet";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE HTML>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Elion Shala">
    <link rel="stylesheet" type="text/css" href="css/adm-destinacionet.css">
    <title>Flutura Tourist - Administrator</title>

    <style>
        /*stili ekstra*/
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
            padding: 5px;
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
            <li><a href="admin-rezervimet.php" class="active">Menaxho Rez.</a></li>
            <li><a href="admin-feedbacks.php">Menaxho Feedbacks</a></li>
            <li><a href="admin-faq.php">Menaxho FAQ</a></li>
            <li><a href="logout.php">Dil</a></li>
        </ul>
    </nav>

    <div class="container">

        <form id="editForm" style="display:none;" method="post" action="admin-rezervimet.php">
            <h3>Ndrysho Rezervim</h3>
            <input type="hidden" id="editReservationId" name="id">
            <label for="new_fullname"><b>Emri dhe Mbiemri: </b></label>
            <input type="text" id="new_fullname" name="new_fullname" required><br>
            <label for="new_email"><b>Email: </b></label>
            <input type="text" id="new_email" name="new_email" required><br>
            <label for="new_phone"><b>Nr. i telefonit: </b></label>
            <input type="text" id="new_phone" name="new_phone" required><br>
            <label for="new_destination_id"><b>ID-ja e Destinacionit: </b></label>
            <input type="text" id="new_destination_id" name="new_destination_id" required><br>
            <label for="new_gender"><b>Gjinia: </b></label>
            <input type="text" id="new_gender" name="new_gender" required><br>
            <label for="new_address"><b>Adresa: </b></label>
            <input type="text" id="new_address" name="new_address"><br>
            <label for="new_reservation_date"><b>Data e Rezervimit: </b></label>
            <input type="text" id="new_reservation_date" name="new_reservation_date" required><br>
            <label for="new_user_id"><b>ID-ja e Perdoruesit: </b></label>
            <input type="text" id="new_user_id" name="new_user_id"><br>
            <button type="submit" name="edit_reservation" class="edit-button" style="margin-top: 10px;">Ndrysho</button>
            <button type="button" onclick="hideEditForm()" class="edit-button" style="margin-top: 10px;">Anulo</button>

        </form>

        <ul class="list">
            <h3>Lista e Rezervimeve</h3>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<li>";
                echo "<b>Emri dhe Mbiemri: </b> {$row['fullname']},<br> <b>Email: </b> {$row['email']},<br> <b>Nr. i Telefonit: </b>  {$row['phone']},<br> <b>ID-ja e destinacionit: </b> {$row['destination_id']}, <br> <b>Adresa: </b> {$row['address']}, <br> <b>Gjinia: </b> {$row['gender']}, <br> <b>Data e Rezervimit: </b>{$row['reservation_date']}, <br><b>ID-ja e Perdoruesit: </b> {$row['user_id']}";
                echo " <br> <a href='#' onclick=\"showEditForm('{$row['id']}', '{$row['fullname']}', '{$row['email']}', '{$row['phone']}', '{$row['destination_id']}', '{$row['address']}', '{$row['gender']}', '{$row['reservation_date']}', '{$row['user_id']}');\"><b>Ndrysho</b></a>";

                echo " - <a href='admin-rezervimet.php?remove_reservation={$row['id']}'><b>Fshij</b></a>";
                echo "<br>";
                echo "<br>";
                echo "</li>";

            }
            ?>
        </ul>
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

    </script>


</body>

</html>
