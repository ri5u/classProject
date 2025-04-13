<?php
    session_start();
    require_once("db_connection.php");
    $sql = "SELECT file_path FROM artworks";
    $result = $conn->query($sql);

    $imagePath = [];
    while($row = $result->fetch_assoc()){
        $imagePath[] = "uploads/".basename($row["file_path"]);
    }

    $imagePathJson = json_encode($imagePath);

    $sql = "SELECT DISTINCT uid FROM artworks ORDER BY RAND() LIMIT 4";

    $result = $conn->query($sql);
    $artists = [];
    while($row = $result->fetch_assoc() ){
        $artists[] = $row["uid"];
    }

    $randomArtist = [];
    foreach($artists as $artist){
        $artistName = "SELECT username FROM userInfo WHERE uid = '$artist'";
        $art = "SELECT file_path FROM artworks WHERE uid = '$artist' ORDER BY RAND() LIMIT 1";

        
        $res1 = $conn->query($artistName);
        $res2 = $conn->query($art);
        if( $res1 && $res2 && $res1->num_rows > 0 && $res2->num_rows > 0){
            $randomArtist[$res1->fetch_assoc()["username"]] = "uploads/".basename($res2->fetch_assoc()["file_path"]);
        }
    }
    $randomArtistJson = json_encode($randomArtist);
?>

