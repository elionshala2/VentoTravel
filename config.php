<?php

$server = "localhost";
$user = "root";
$pass = "";
$database = "VentoTravel";

$conn = mysqli_connect($server, $user, $pass, $database);
if ($conn) { mysqli_set_charset($conn, "utf8mb4"); }

if (!$conn) {
    die('Database connection failed.');
}
?>
