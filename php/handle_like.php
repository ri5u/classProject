<?php
session_start();
require_once("db_connection.php");

// Check if user is logged in
if (!isset($_SESSION['uid'])) {
    echo json_encode(['status' => 'error', 'message' => 'Please log in to like artworks']);
    exit();
}

// Check if art_id is provided
if (!isset($_POST['art_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Artwork ID is required']);
    exit();
}

$art_id = (int)$_POST['art_id'];
$uid = (int)$_SESSION['uid'];

try {
    // Start transaction
    $conn->begin_transaction();

    // Check if user has already liked the artwork
    $check_sql = "SELECT like_id FROM likes WHERE art_id = ? AND uid = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $art_id, $uid);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // User has already liked - remove like
        $delete_sql = "DELETE FROM likes WHERE art_id = ? AND uid = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("ii", $art_id, $uid);
        $delete_stmt->execute();

        // Update likes count in artworks table
        $update_sql = "UPDATE artworks SET likes_count = likes_count - 1 WHERE art_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $art_id);
        $update_stmt->execute();

        $action = 'unliked';
    } else {
        // User hasn't liked - add like
        $insert_sql = "INSERT INTO likes (art_id, uid) VALUES (?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("ii", $art_id, $uid);
        $insert_stmt->execute();

        // Update likes count in artworks table
        $update_sql = "UPDATE artworks SET likes_count = likes_count + 1 WHERE art_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $art_id);
        $update_stmt->execute();

        $action = 'liked';
    }

    // Get updated like count
    $count_sql = "SELECT likes_count FROM artworks WHERE art_id = ?";
    $count_stmt = $conn->prepare($count_sql);
    $count_stmt->bind_param("i", $art_id);
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
    $likes_count = $count_result->fetch_assoc()['likes_count'];

    // Commit transaction
    $conn->commit();

    echo json_encode([
        'status' => 'success',
        'action' => $action,
        'likes_count' => $likes_count
    ]);

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    echo json_encode(['status' => 'error', 'message' => 'Database error occurred']);
} 