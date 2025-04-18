<?php
    error_reporting(E_ALL);
    ini_set("display_errors",1);
    session_start();
    require_once("db_connection.php");

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if (isset($_SESSION["username"]) || isset($_SESSION["admin"])) {
                $comment_id = $_POST["comment_id"];
                $art_id = $_POST["art_id"];
        // Get the username who made the comment
        $sql_check = "SELECT u.userName FROM comments c 
                    JOIN userInfo u ON c.uid = u.uid 
                    WHERE c.comment_id = '$comment_id'";
        $result_check = $conn->query($sql_check);
        if ($result_check && $result_check->num_rows > 0) {
            $row = $result_check->fetch_assoc();
            $comment_author = $row['userName'];

            if ($_SESSION["username"] === $comment_author || isset($_SESSION["admin"])) {
                // Safe to delete
                $sql = "DELETE FROM comments WHERE comment_id = '$comment_id'";
                $result = $conn->query($sql);
                if ($result) {
                    header("Location: artwork_details.php?id=$art_id");
                    exit();
                } else {
                    echo "Failed to delete the comment.";
                }
            } else {
                echo "You do not have permission to delete this comment.";
            }
        } else {
            echo "Comment not found.";
        }
    }
}
            