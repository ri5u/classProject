<?php
session_start();
require_once("db_connection.php");

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["profile_picture"])) {
    $username = $_SESSION["username"];
    $file = $_FILES["profile_picture"];
    
    // Check for errors
    if ($file["error"] !== UPLOAD_ERR_OK) {
        $_SESSION["error_message"] = "Upload failed with error code: " . $file["error"];
        header("Location: profile.php?user=" . urlencode($username));
        exit();
    }

    // Validate file type
    $allowed_types = ["image/jpeg", "image/jpg", "image/png", "image/gif"];
    if (!in_array($file["type"], $allowed_types)) {
        $_SESSION["error_message"] = "Only JPG, PNG and GIF files are allowed";
        header("Location: profile.php?user=" . urlencode($username));
        exit();
    }

    // Validate file size (max 5MB)
    if ($file["size"] > 5 * 1024 * 1024) {
        $_SESSION["error_message"] = "File size must be less than 5MB";
        header("Location: profile.php?user=" . urlencode($username));
        exit();
    }

    // Create uploads directory if it doesn't exist
    $upload_dir = "../uploads/profile_pictures/";
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Generate unique filename
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $new_filename = $username . "_" . uniqid() . "." . $file_extension;
    $upload_path = $upload_dir . $new_filename;

    // Move uploaded file
    if (!move_uploaded_file($file["tmp_name"], $upload_path)) {
        $_SESSION["error_message"] = "Failed to save the uploaded file";
        header("Location: profile.php?user=" . urlencode($username));
        exit();
    }

    // Get old profile picture before updating
    $stmt = $conn->prepare("SELECT profile_picture FROM userInfo WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $old_picture = $result->fetch_assoc()["profile_picture"];

    // Update database
    $stmt = $conn->prepare("UPDATE userInfo SET profile_picture = ? WHERE username = ?");
    $stmt->bind_param("ss", $new_filename, $username);
    
    if (!$stmt->execute()) {
        $_SESSION["error_message"] = "Failed to update profile picture in database";
        unlink($upload_path); // Delete the uploaded file
        header("Location: profile.php?user=" . urlencode($username));
        exit();
    }

    // Delete old profile picture if it exists and isn't the default
    if ($old_picture && $old_picture !== "default_avatar.jpg" && file_exists($upload_dir . $old_picture)) {
        unlink($upload_dir . $old_picture);
    }

    $_SESSION["success_message"] = "Profile picture updated successfully";
    header("Location: profile.php?user=" . urlencode($username));
    exit();
}

// If we get here, something went wrong
$_SESSION["error_message"] = "Invalid request";
header("Location: profile.php?user=" . urlencode($_SESSION["username"]));
exit(); 