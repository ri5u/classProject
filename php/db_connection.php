<?php
$servername = "localhost";
$user = "r15u";
$pass = "1234";
$db_name = "projectDB";

$conn = new mysqli($servername, $user, $pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
