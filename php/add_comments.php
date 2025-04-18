<?php
    error_reporting(E_ALL);
    ini_set("display_errors",1);
    session_start();
    require_once("db_connection.php");

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $comment_text = htmlspecialchars(trim($_POST["comment_text"]));
        $art_id = $_POST["art_id"];
        $uid = $_SESSION["uid"];

        $sql = "INSERT INTO comments (art_id, uid, comment_text) VALUES (?, ? , ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $art_id, $uid, $comment_text);
        
        if($stmt->execute()){
            $stmt->close();
            header("Location: artwork_details.php?id=$art_id");
            exit();
        }
        else{
            echo "Failed to add the comments";
        }
    }