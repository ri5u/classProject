<?php

error_reporting(E_ALL);
ini_set("display_errors","1");

session_start();
require_once("db_connection.php");

if (!isset($_GET["query"]) || empty(trim($_GET["query"]))) {
    echo "Please enter an artist name.";
    exit();
}

$search = trim($_GET["query"]);
$search = $conn->real_escape_string($search); // basic sanitization

$sql = "SELECT * FROM userInfo WHERE username = '$search'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    // Artist exists, redirect to profile
    header("Location: profile.php?user=$search");
    exit();
} else {
    // Artist not found
    echo "<h2>No artist found with the username '$search'</h2>";
    echo "<a href='../index.php'>Back to Home</a>";
}
?>
