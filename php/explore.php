<?php
    session_start();
    require_once("db_connection.php");


    $sql = "SELECT * FROM artworks";
    $result = $conn->query($sql);
    if( $result->num_rows === 0){
        echo "NO ART TO DISPLAY";
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore</title>
    <link rel="stylesheet" href="../css/explore_layout.css">
</head>
<body>
    <!-- Querying and displaying the artworks -->

        <div class="artworks">
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="artwork-card">
                        <a href="artwork_details.php?id=<?=$row["art_id"]?>">
                            <img src="<?=htmlspecialchars($row["file_path"]);?>" alt="<?=htmlspecialchars($row["title"]);?>">
                        </a>
                    </div>
                <?php endwhile; ?>
        </div>
</body>
</html>