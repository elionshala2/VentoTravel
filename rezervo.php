<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Variables
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']); 
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $destination_id = mysqli_real_escape_string($conn, $_POST['destination_id']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $user_id = $_SESSION['id'];

    $checkDestination = mysqli_query($conn, "SELECT * FROM destinations WHERE id = $destination_id");

    if (mysqli_num_rows($checkDestination) > 0) {
        $query = "INSERT INTO rezervimet (fullname, email, phone, destination_id, gender, address, user_id) VALUES ('$fullname', '$email', '$phone', '$destination_id', '$gender', '$address', '$user_id')";

        if (mysqli_query($conn, $query)) {
            echo "<script>alert('U rezervua me sukses!');</script>";
        } else {
            echo "<script>alert('Diqka shkoi gabim! Te lutem provo perseri.');</script>";
        }
    } else {
        echo "<script>alert('ID-ja e Destinicionit nuk eshte valide!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VentoTravel - Rezervo</title>
    <link rel="stylesheet" type="text/css" href="css/ballina.css">
    <style>
        .reservation-form-container {
            margin: 70px auto;
            display: flex;
            flex-direction: column;
            border: solid 3px #2835af;
            width: 450px;
            padding: 40px;
            border-radius: 20px;
            text-align: end;
            background: #cccaca;
        }

        .reservation-form-container input {
            border: solid 2px #2835af;
            border-radius: 20px;
            background-color: #fff;
            outline: none;
            margin: 5px;
            padding: 5px;
            font-size: 20px;
            margin-top: 5px;
            transition: 0.5s;
        }
        
        select {
            border: solid 2px #2835af;
            border-radius: 20px;
            background-color: #fff;
            outline: none;
            margin: 5px;
            padding: 5px;
            font-size: 20px;
            margin-top: 5px;
            width: 257px;
            transition: 0.5s;
        }
        
        select:hover {
            background-color: #2835af;
            color: white;
        }

        .reservation-form-container input:hover {
            background-color: #2835af;
            color: white;
        }

        .reservation-form-container label {
            font-weight: 700;
        }

        #submitButton {
            width: 260px;
            margin-top: 10px;
            font-weight: 600;
            transition: 0.5s;
        }

        #submitButton:hover {
            background-color: #2835af;
            color: white;
            cursor: pointer;
        }
    </style>
</head>

<body style="background-image: url(img/bck.jpg);">

    <header>
        <h1>VentoTravel</h1>
        <h3>Miresevjen, <?php echo $_SESSION['username']; ?>!</h3>
    </header>

    <nav>
        <ul>
            <li><a href="ballina.php">Reth Nesh</a></li>
            <li><a href="destinacionet.php">Destinacionet</a></li>
            <li><a href="rezervo.php" class="active">Rezervo</a></li>
            <li><a href="feedback.php">Feedback</a></li>
            <li><a href="faq.php">FAQ</a></li>
            <li><a href="historiku.php">Historiku</a></li>
            <li><a href="logout.php">Dil</a></li>
        </ul>
    </nav>

    
    <h2 class="title">Rezervo</h2>

    <div class="reservation-form-container">
        <form action="rezervo.php" method="post">
            <label for="fullname">Emri dhe Mbiemri:</label>
            <input type="text" id="fullname" name="fullname" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>

            <label for="phone">Nr. i Telefonit:</label>
            <input type="tel" id="phone" name="phone" required><br>

            <label for="destination_id">ID-ja e Destinacionit:</label>
            <input type="number" id="destination_id" name="destination_id" required><br>

            <label for="gender">Gjinia:</label>
            <select id="gender" name="gender" required>
                <option value="Mashkull">Mashkull</option>
                <option value="Femer">Femer</option>
                <option value="Tjeter">Tjeter</option>
            </select><br>
            
            <label for="address">Adresa:</label>
            <input type="address" id="address" name="address" required><br>

            <input type="submit" value="Rezervo" id="submitButton">
        </form>
    </div>

</body>

</html>
