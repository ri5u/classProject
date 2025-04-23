<?php
    session_start();
    require_once("db_connection.php");

    // Get sort parameter from URL, default to newest
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
    
    // Build the SQL query based on sort parameter
    $sql = "SELECT a.*, u.username, 
            (SELECT COUNT(*) FROM likes WHERE art_id = a.art_id) as likes_count,
            (SELECT COUNT(*) FROM likes WHERE art_id = a.art_id AND uid = ?) as user_liked
            FROM artworks a 
            JOIN userInfo u ON a.uid = u.uid 
            ORDER BY ";

    switch($sort) {
        case 'oldest':
            $sql .= "a.upload_date ASC";
            break;
        default:
            $sql .= "a.upload_date DESC";
    }

    $stmt = $conn->prepare($sql);
    $user_id = isset($_SESSION['uid']) ? $_SESSION['uid'] : 0;
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 0){
        echo '<div class="no-art">NO ART TO DISPLAY</div>';
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include("header.php"); ?>
    
    <div class="explore-container">
        <!-- Sort Section -->
        <div class="sort-section">
            <div class="sort-options">
                <label for="sort">Sort by:</label>
                <select id="sort" onchange="updateSort(this.value)">
                    <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest First</option>
                    <option value="oldest" <?= $sort === 'oldest' ? 'selected' : '' ?>>Oldest First</option>
                </select>
            </div>
        </div>

        <!-- Artworks Grid -->
        <div class="artworks">
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="artwork-card">
                    <a href="artwork_details.php?id=<?=$row["art_id"]?>">
                        <img src="<?=htmlspecialchars($row["file_path"]);?>" alt="<?=htmlspecialchars($row["title"]);?>">
                    </a>
                    <div class="artwork-info">
                        <h3><?=htmlspecialchars($row["title"]);?></h3>
                        <p class="artist-name">By <a href="profile.php?user=<?=urlencode($row["username"]);?>"><?=htmlspecialchars($row["username"]);?></a></p>
                    </div>
                    <button class="like-button <?= $row['user_liked'] ? 'liked' : ''; ?>" 
                            data-art-id="<?=$row["art_id"];?>"
                            onclick="handleLike(this)">
                        <i class="fas fa-heart"></i>
                        <span class="like-count"><?=$row["likes_count"];?></span>
                    </button>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script>
        function updateSort(sort) {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', sort);
            window.location.href = url.toString();
        }

        function handleLike(button) {
            const artId = button.getAttribute('data-art-id');
            const isLiked = button.classList.contains('liked');
            
            fetch('handle_like.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `art_id=${artId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    button.classList.toggle('liked');
                    const likeCount = button.querySelector('.like-count');
                    likeCount.textContent = data.likes_count;
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while processing your like.');
            });
        }
    </script>
</body>
</html> 