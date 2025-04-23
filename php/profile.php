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
    
    // Get user information with prepared statement
    $sql = "SELECT uid, email, created_at, profile_picture FROM userInfo WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows === 0){
        echo "No User Was Found";
        exit();
    }
    else{
        $row = $result->fetch_assoc();
        $uid = $row["uid"];
        $email = $row["email"];
        $created_at = $row["created_at"];
        $profile_picture = $row["profile_picture"];
    }

    // Get user's artworks with like counts
    $sql = "SELECT a.*, 
            (SELECT COUNT(*) FROM likes WHERE art_id = a.art_id) as likes_count
            FROM artworks a 
            WHERE a.uid = ? 
            ORDER BY a.upload_date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $artworks_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile: <?= htmlspecialchars($username); ?></title>
    <link rel="stylesheet" href="../css/profile_layout.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include("header.php"); ?>
    <div class="profile">
        <?php if(isset($_SESSION["error_message"])): ?>
            <div class="message error-message">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($_SESSION["error_message"]) ?>
            </div>
            <?php unset($_SESSION["error_message"]); ?>
        <?php endif; ?>

        <?php if(isset($_SESSION["success_message"])): ?>
            <div class="message success-message">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($_SESSION["success_message"]) ?>
            </div>
            <?php unset($_SESSION["success_message"]); ?>
        <?php endif; ?>

        <div class="userInfo">
            <div class="profile-header">
                <div class="profile-picture-container">
                    <img src="../uploads/profile_pictures/<?= htmlspecialchars($profile_picture) ?>" alt="Profile Picture" class="profile-picture">
                    <?php if(isset($_SESSION["username"]) && $_SESSION["username"] === $username): ?>
                        <form action="handle_profile_picture.php" method="POST" enctype="multipart/form-data" class="profile-upload-form">
                            <label for="profile-picture-upload" class="upload-overlay">
                                <i class="fas fa-camera"></i>
                            </label>
                            <input type="file" name="profile_picture" id="profile-picture-upload" accept="image/*" onchange="this.form.submit()">
                        </form>
                    <?php endif; ?>
                </div>
                <div class="profile-info">
                    <h1><?= htmlspecialchars($username)?></h1>
                    <div class="user-stats">
                        <div class="stat-item">
                            <span class="stat-number">
                                <?php 
                                $total_likes_sql = "SELECT SUM(likes_count) as total_likes FROM artworks WHERE uid = ?";
                                $stmt = $conn->prepare($total_likes_sql);
                                $stmt->bind_param("i", $uid);
                                $stmt->execute();
                                $total_likes = $stmt->get_result()->fetch_assoc()['total_likes'] ?? 0;
                                echo $total_likes;
                                ?>
                            </span>
                            <span class="stat-label">Total Likes</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number"><?= $artworks_result->num_rows ?></span>
                            <span class="stat-label">Artworks</span>
                        </div>
                    </div>
                    <div class="user-email-joined">
                        <p class="user-email"><i class="fas fa-envelope"></i> Email: <?= htmlspecialchars($email);?></p>
                        <p class="user-joined"><i class="fas fa-calendar-alt"></i> Joined: <?= date('F j, Y', strtotime($created_at));?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="artworks">
            <?php if($artworks_result->num_rows === 0): ?>
                <div class="no-artworks">
                    <i class="fas fa-palette"></i>
                    <p>No artworks to display</p>
                </div>
            <?php else: ?>
                <?php while($row = $artworks_result->fetch_assoc()): ?>
                    <div class="artwork-card">
                        <a href="artwork_details.php?id=<?=$row["art_id"]?>">
                            <img src="<?=htmlspecialchars($row["file_path"]);?>" alt="<?=htmlspecialchars($row["title"]);?>">
                            <div class="artwork-info">
                                <h3><?=htmlspecialchars($row["title"]);?></h3>
                                <div class="artwork-stats">
                                    <span class="likes">
                                        <i class="fas fa-heart"></i>
                                        <?= $row["likes_count"] ?>
                                    </span>
                                    <span class="date">
                                        <?= date('M j, Y', strtotime($row["upload_date"])) ?>
                                    </span>
                                </div>
                            </div>
                        </a>
                        
                        <?php if((isset($_SESSION["username"]) && $_SESSION["username"] === $username) || isset($_SESSION["admin"])) : ?>
                            <form class="delete" method="POST" action="delete_artwork.php" onsubmit="return confirm('Are you sure you want to delete this artwork?');">
                                <input type="hidden" name="art_id" value="<?=$row['art_id']?>">
                                <input type="hidden" name="user" value="<?=htmlspecialchars($username);?>">
                                <button type="submit" class="delete-button">🗑️</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Auto-submit form when file is selected
        document.getElementById('profile-picture-upload')?.addEventListener('change', function() {
            if (this.files.length > 0) {
                // Show loading state
                const form = this.closest('form');
                const loadingEl = form.querySelector('.upload-loading');
                const labelEl = form.querySelector('.upload-label');
                
                labelEl.style.display = 'none';
                loadingEl.style.display = 'flex';
                
                // Submit the form
                form.submit();
            }
        });

        // Auto-hide messages after 5 seconds
        setTimeout(() => {
            const messages = document.querySelectorAll('.message');
            messages.forEach(msg => {
                msg.style.opacity = '0';
                setTimeout(() => msg.remove(), 300);
            });
        }, 5000);
    </script>
</body>
</html>