<?php
    session_start();
    require_once("db_connection.php");

    if(!isset($_GET["id"])){
        exit("NO ARTWORK SELECTED");
    }

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
    <div class="art-container">
        <div class="art-image">
            <img src="<?=htmlspecialchars($file_path);?>" alt="<?=htmlspecialchars($title);?>">
        </div>
        <div class="sidebar">
            
            <p class="title">Title: <?=htmlspecialchars($title)?></p>
            <p class="artist">Artist: <?=htmlspecialchars($artist)?></p>
            <p class="description">Description: <?=htmlspecialchars($description)?></p>
            <p class="upload_date">Uploaded On: <?=htmlspecialchars($upload_date)?></p>
        </div>
    </div>
</body>
</html>