<?php
    error_reporting(E_ALL);
    ini_set("display_errors",1);
    session_start();
    require_once("db_connection.php");

    if(!isset($_GET["id"])){
        exit("NO ARTWORK SELECTED");
    }

    //querying for the art details.
    $id = $_GET["id"];
    $sql = "SELECT * FROM artworks WHERE art_id = '$id'";
    $result = $conn->query($sql);

    if($result->num_rows === 0){
        exit("NO ART FOUND");
    }
    else{
        $artwork = $result->fetch_assoc();
        $uid = $artwork["uid"];
        $title = $artwork["title"];
        $description = $artwork["description"];
        $upload_date = $artwork["upload_date"];
        $file_path = $artwork["file_path"];

        //FETCHING artist name
        $sql = "SELECT username FROM userInfo WHERE uid = '$uid'";
        $result = $conn->query($sql);
        
        $artist = $result->fetch_assoc()["username"];
    }

    //querying for the comments
    $sql = "SELECT c.comment_text, c.created_at, u.username
            FROM comments c
            JOIN userInfo u ON c.uid = u.uid
            WHERE c.art_id = '$id'
            ORDER BY c.created_at DESC";
        
    $comments_result = $conn->query($sql); 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="../css/artwork_details.css">
</head>
<body>
    <div class="details">
        <div class="art-container">
            <div class="art-image">
                <img src="<?=htmlspecialchars($file_path);?>" alt="<?=htmlspecialchars($title);?>">
            </div>

            <div class="sidebar">
                
                <p class="title" style="font-size:35px"><?=htmlspecialchars($title)?></p>
                <p class="artist">Artist:
                    <a href="profile.php?user=<?=urlencode($artist);?>"> 
                    <?=htmlspecialchars($artist)?>
                    </a>
                </p>

                <p class="description">Description: <?=htmlspecialchars($description)?></p>
                <p class="upload_date">Uploaded On: <?=htmlspecialchars($upload_date)?></p>
            </div>
        </div>

        <div class="comments">
            <?php if($comments_result->num_rows > 0) {
                while($comment = $comments_result->fetch_assoc()){
                    $comment_text = $comment["comment_text"];
                    $comment_author = $comment["username"];
                    $comment_date = $comment["created_at"];

                    echo "<p><strong>".htmlspecialchars($comment_author)."</strong><span class='comment_time'>(".htmlspecialchars($comment_date).")</span></p>";
                    echo "<p>".nl2br(htmlspecialchars($comment_text))."</p>";
                }
            } 
            else{
                echo "<p>No Comments To Display</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>