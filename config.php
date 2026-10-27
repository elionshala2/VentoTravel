<?php

$server = "localhost";
$user = "root";
$pass = "";
$database = "VentoTravel";

$conn = mysqli_connect($server, $user, $pass, $database);

if (!$conn) {
    die("<script>alert('Dicka shkoj gabim te lutem provo perseri!')</script>");
}
?>
