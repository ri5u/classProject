<?php
    error_reporting(E_ALL);
    ini_set("display_errors","1");
    session_start();
    require_once("db_connection.php");
   
    $username = $_SESSION["username"]; //current user who is logged in
    $user = $_POST["user"]; //The user whose profile we are viewing.
    
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        if (!isset($_SESSION["username"]) || !isset($_SESSION["admin"])) {
            echo "THE FUCK ARE YOU TRYING TO DELETE?";
        }
        else{
            $aid = $_POST["art_id"];
            $sql = "DELETE FROM artworks WHERE art_id = '$aid'";
            $conn->query($sql);

            if ($conn->affected_rows > 0) {
                header("Location: profile.php?user=$user");
                exit();
            }
        }
    }
?>