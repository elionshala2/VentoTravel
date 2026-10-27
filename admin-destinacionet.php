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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_destination'])) { // shikon nese linku eshte per te shtuar destinacion
    
    //variablat e nevojshme
    $title = $_POST['title'];
    $photo_url = $_POST['photo_url'];
    $small_description = $_POST['small_description'];
    $big_description = $_POST['big_description'];
    $price = $_POST['price'];
    
    // per me shtu destinicionin ne databaz
    $query = "INSERT INTO destinations (title, photo_url, small_description, big_description, price) 
              VALUES ('$title', '$photo_url', '$small_description', '$big_description', '$price')";
    // me ekzekutu
    $result = mysqli_query($conn, $query);
    // nese ekzekutohet me sukses me tregu, nese nuk ekzekutohet me sukses me tregu
    if ($result) {
        echo "<script>alert('Destinacioni u shtua me sukses!')</script>";
    } else {
        echo "<script>alert('Dicka shkoi gabim! Te lutem provo perseri!')</script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_destination'])) { // shikon nese linku eshte per te ndryshuar destinacion
    $destination_id = $_POST['id'];
    $new_title = $_POST['new_title'];
    $new_photo_url = $_POST['new_photo_url']; 
    $new_small_description = $_POST['new_small_description'];
    $new_big_description = $_POST['new_big_description'];
    $new_price = $_POST['new_price'];

    $query = "UPDATE destinations 
          SET title='$new_title', photo_url='$new_photo_url', 
          small_description='$new_small_description', big_description='$new_big_description', price='$new_price' 
          WHERE id=$destination_id";

    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>alert('Destinacioni u ndryshua me sukses!')</script>";
    } else {
        echo "<script>alert('Dicka shkoi gabim! Te lutem provo perseri!')</script>";
    }
}

if (isset($_GET['remove_destination'])) { // shikon nese eshte per te fshire destinacion
    $destination_id = $_GET['remove_destination'];

    $query = "DELETE FROM destinations WHERE id=$destination_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>alert('Destinacioni u fshi me sukses!')</script>";
    } else {
        echo "<script>alert('Dicka shkoi gabim! Te lutem provo perseri!')</script>";
    }
}

$query = "SELECT * FROM destinations";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE HTML>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Elion Shala">
    <link rel="stylesheet" type="text/css" href="css/adm-destinacionet.css">
    <title>VentoTravel - Administrator</title>

    <style>
        /* stil ekstra*/
        input {
            margin-top: 10px;
            padding: 5px;
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

    </style>
</head>

<body style="background-image: url(img/bck.jpg)">
    <!--per me pas ma leht per me ndryshu foton e backgroundit-->
    <header>
        <h1>VentoTravel</h1>
        <h3>Miresevjen, <?php echo $_SESSION['username']; ?>!</h3>
    </header>

    <nav>
        <ul>
            <li><a href="admin.php">Admin</a></li>
            <li><a href="admin-destinacionet.php" class="active">Menaxho Dest.</a></li>
            <li><a href="admin-rezervimet.php">Menaxho Rez.</a></li>
            <li><a href="admin-feedbacks.php">Menaxho Feedbacks</a></li>
            <li><a href="admin-faq.php">Menaxho FAQ</a></li>
            <li><a href="logout.php">Dil</a></li>
        </ul>
    </nav>

    <div class="container">

        <form id="editForm" style="display:none; margin-bottom: 20px;" method="post" action="admin-destinacionet.php" class="editForm">
            <!--Forma per te ndryshuar destinicionin-->
            <h3>Ndrysho Destinacion</h3>
            <input type="hidden" id="editDestinationId" name="id">
            <label for="new_title"><b>Titulli:</b></label>
            <input type="text" id="new_title" name="new_title" required><br>
            <label for="new_photo_url"><b>Photo Url:</b></label>
            <input type="text" id="new_photo_url" name="new_photo_url" required><br>
            <label for="new_small_description"><b>Pershkrimi i Vogel:</b></label>
            <input type="text" id="new_small_description" name="new_small_description" required><br>
            <label for="new_big_description"><b>Pershkrimi i Gjate:</b></label>
            <input type="text" id="new_big_description" name="new_big_description" required><br>
            <label for="new_price"><b>Cmimi:</b></label>
            <input type="text" id="new_price" name="new_price" required><br>
            <button type="submit" name="edit_destination" class="edit-button" style="margin-top: 10px;">Ndrysho</button>
            <!--Stili i tij gjendet ketu pasi ne adm-destinacionet.css nuk lejojke-->
            <button type="button" onclick="hideEditForm()" class="edit-button" style="margin-top: 10px;">Anulo</button>

        </form>

        <form method="post" action="admin-destinacionet.php" class="shto">
            <!--Metoda per te shtuar destinicion-->
            <h3>Shto Destinacion</h3>
            <label for="title">Titulli:</label>
            <input type="text" name="title" required><br>
            <label for="photo_url">Photo Url:</label>
            <input type="text" name="photo_url" required><br>
            <label for="small_description">Pershkrimi i vogel:</label>
            <input type="text" name="small_description" required><br>
            <label for="big_description">Pershkrimi i gjate:</label>
            <input type="text" name="big_description" required><br>
            <label for="price">Cmimi:</label>
            <input type="text" name="price" required><br>
            <button type="submit" name="add_destination" style="margin-top: 10px;">Shto Destinacionin</button>
        </form>

        <ul class="list">
            <h3>Lista e destinacioneve</h3>
            <!--lista -->
            <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<li>";
            echo "<b>Titulli:</b> {$row['title']},<br> <b>Photo URL:</b> {$row['photo_url']},<br> <b>Pershkrimi i Vogel:</b>  {$row['small_description']},<br> <b>Pershkrimi i Gjate:</b> {$row['big_description']}, <br> <b>Cmimi:</b> {$row['price']}";
            echo " <br> <a href='#' onclick='showEditForm({$row['id']}, \"{$row['title']}\", \"{$row['photo_url']}\", \"{$row['small_description']}\", \"{$row['big_description']}\", \"{$row['price']}\");'><b>Ndrysho</b></a>";
            echo " - <a href='admin-destinacionet.php?remove_destination={$row['id']}'><b>Fshij</b></a>";
            echo "<br>";
            echo "<br>";
            echo "</li>";
        }
        ?>
        </ul>
    </div>

    <script>
        function showEditForm(destinationId, title, photo_url, small_description, big_description, price) {
            /* per me i mbush inputet me vlerat aktuale */
            document.getElementById("editDestinationId").value = destinationId;
            document.getElementById("new_title").value = title;
            document.getElementById("new_photo_url").value = photo_url;
            document.getElementById("new_small_description").value = small_description;
            document.getElementById("new_big_description").value = big_description;
            document.getElementById("new_price").value = price;
            document.getElementById("editForm").style.display = "block";
        }

        function hideEditForm() {
            document.getElementById("editForm").style.display = "none";
        }

    </script>

</body>

</html>
