<?php

include 'config.php';

error_reporting(0);

session_start();

if (isset($_SESSION['username'])) {
    header("location: ballina.php");
}

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    if ($email == 'admin@gmail.com' && $password == md5('admin123')) { // me shiku nese don me u konektu si administrator
        $_SESSION['username'] = 'admin'; //i duhet per emrin e label-it
        header("Location: admin.php"); // redirekton te admin.php - faqja e administratorave
    } else {
        $sql = "SELECT * FROM regjistrimi WHERE email='$email' AND password='$password'"; // nese nuk eshte admin e kerkon ne databaz
        $result = mysqli_query($conn, $sql);

        if ($result->num_rows > 0) { // me shiku nese ka gjet perdorues me qato te dhena
            $row = mysqli_fetch_assoc($result);
            $_SESSION['username'] = $row['username']; // username-i duhet per me shiku nese eshte i kyqun
            $_SESSION['id'] = $row['id'];  // id-ja eshte qelsi primar, nevoitet
            header("Location: ballina.php");
        } else {
            echo "<script>alert('Email ose Fjalekalimi janë gabim, provo perseri!')</script>"; // nese nuk ka gjer perdorues me qato te dhana, me bo me alert
        }
    }
}
?>

<!DOCTYPE HTML>
<html>

<head>
    <meta name="author" content="Elion Shala">
    <title>Flutura Reisen - Kyqu</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body style="background-image: url(img/bck2.jpg)">
    <div class="container">
        <h3 class="title">Flutura Reisen - Kyqu</h3>
        <form action="" method="post" class="login">
            <div class="group">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="group">
                <input type="password" name="password" placeholder="Fjalekalimi" required>
            </div>
            <div class="group">
                <button type="submit" name="submit">Kyqu</button>
            </div>
        </form>
        <p class="textbelowform">Nuk ke llogari? <a href="register.php">Regjistrohu!</a></p>
    </div>
</body>

</html>
