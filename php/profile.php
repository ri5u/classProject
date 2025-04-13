<?php
    session_start();
    require_once("db_connection.php");
    if(!isset($_SESSION["username"])){
        header("Location: login.php");
        exit();
    }

    $username = $_SESSION["username"];

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
                    <img src="<?=htmlspecialchars($row["file_path"]);?>" alt="<?=htmlspecialchars($row["file_name"]);?>">
                </div>
            <?php endwhile; ?>
        </div>
    </div>  
</body>
</html>