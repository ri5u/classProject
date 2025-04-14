<?php
    error_reporting(E_ALL);
    ini_set("display_errors","1");
    session_start();
    var_dump($_SESSIONS);
    $username = $_SESSION["username"];
    require_once("db_connection.php");
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        if (!isset($_SESSION["username"])) {
            echo "THE FUCK ARE YOU TRYING TO DELETE?";
        }
        else{
            $aid = $_POST["art_id"];
            $sql = "DELETE FROM artworks WHERE art_id = '$aid'";
            $conn->query($sql);

            if ($conn->affected_rows > 0) {
                header("Location: profile.php?user=$username");
                exit();
            }
        }
    }
?>