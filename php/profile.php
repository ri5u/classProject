<?php
    session_start();
    require_once("db_connection.php");
    if(!isset($_GET["user"])){
        header("Location: login.php");
        exit();
    }

    if(isset($_GET["user"])){
        $username = $_GET["user"];
    }
    

    $sql = "SELECT uid, email, created_at FROM userInfo WHERE userName = '$username'";
    $result = $conn->query($sql);
    
    if($result->num_rows === 0){
        echo "No User Was Found";
        exit();
    }
    else{
        $row = $result->fetch_assoc();
        $uid = $row["uid"];
        $email = $row["email"];
        $created_at = $row["created_at"];
    }

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile: <?= htmlspecialchars($username); ?></title>
    <link rel="stylesheet" href="../css/profile_layout.css">
</head>
<body>
    <div class="profile">
        <div class="userInfo">
            <h1>Welcome, <?= htmlspecialchars($username)?></h1>
            <p>UID: <?= htmlspecialchars($uid); ?></p>
            <p>Email: <?= htmlspecialchars($email);?></p>
            <p>Account Created At: <?= htmlspecialchars($created_at);?></p>
        </div>


    <!-- Querying and displaying the artworks -->
        <?php
            $sql = "SELECT * FROM artworks WHERE uid = '$uid'";
            $result = $conn->query($sql);
            if( $result->num_rows === 0){
                echo "NO ART TO DISPLAY";
                exit();
            }

        ?>
        <div class="artworks">
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="artwork-card">
                    <a href="artwork_details.php?id=<?=$row["art_id"]?>">
                        <img src="<?=htmlspecialchars($row["file_path"]);?>" alt="<?=htmlspecialchars($row["title"]);?>">
                    </a>
                    
                    <?php if((isset($_SESSION["username"]) && $_SESSION["username"] === $username) ||  isset($_SESSION["admin"])) : ?>
                        <form class="delete" method="POST" action="delete_artwork.php" onsubmit="return confirm('Are you sure you want to delete this artwork?');">
                            <input type="hidden" name="art_id" value="<?=$row['art_id']?>">
                            <input type="hidden" name="user" value="<?=htmlspecialchars($username);?>">
                            <button type="submit" class="delete-button">🗑️</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>  
</body>
</html>