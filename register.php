<?php

include 'config.php';

error_reporting(0);

session_start();

if (isset($_SESSION['username'])) {
    header("Location: index.php");
}

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $cpassword = md5($_POST['cpassword']);

    // per ta shikuar nese don te regjistrohet me emailin admin@gmail.com (emaili i administratorit) ku nuk lejohet
    if ($email === 'admin@gmail.com') {
        echo "<script>alert('Te lutem provo nje email tjeter!')</script>";
    } else {
        if ($username === 'admin') { // me shiku nese don me u regjistru me username 'admin', ku nuk lejohet pasi me ket username, eshte administratori
            echo "<script>alert('Te lutem provo nje perdorues tjeter!')</script>";
        } else {
            if ($password === $cpassword) { // me shiku nese pasworded jan te njejta
                $sql = "SELECT * FROM regjistrimi WHERE email='$email'";
                $result = mysqli_query($conn, $sql);

                if (!$result->num_rows > 0) {
                    $sql = "INSERT INTO regjistrimi (username, email, password) VALUES ('$username', '$email', '$password')";
                    $result = mysqli_query($conn, $sql);

                    if ($result) {
                        echo "<script>alert('Urime, ju jeni regjistruar me sukses!')</script>";
                        $username = "";
                        $email = "";
                        $_POST['password'] = "";
                        $_POST['cpassword'] = "";
                    } else {
                        echo "<script>alert('Ups! Dicka shkoj gabim, provo perseri!')</script>";
                    }
                } else {
                echo "<script>alert('Ups! Ky email ekziston, provo nje tjeter!')</script>";
                }
            } else {
                echo "<script>alert('Ups! Fjalekalimet nuk perputhen!')</script>";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Flutura Reisen - Regjistrohu</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body style="background-image: url(img/bck.jpg)">
    <div class="container">
        <form action="" method="post" class="form">

            <p class="title">Flutura Reisen - Regjistrohu</p>

            <div class="group">
                <input type="text" placeholder="Perdoruesi" name="username" required>
            </div>

            <div class="group">
                <input type="email" placeholder="Email" name="email" required>
            </div>

            <div class="group">
                <input type="password" placeholder="Fjalekalimi" name="password" required>
            </div>

            <div class="group">
                <input type="password" placeholder="Perserit Fjalekalimin" name="cpassword" required>
            </div>

            <div class="group">
                <button name="submit" class="button">Regjistrohu</button>
            </div>

            <p class="textbelowform">Ke llogari? <a href="index.php">Kyqu tani!</a></p>

        </form>
    </div>
</body>

</html>
