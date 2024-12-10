<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int)$_POST['media_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    
    // Check if it's a YouTube video and URL is provided
    if (isset($_POST['youtube_url']) && !empty($_POST['youtube_url'])) {
        $youtube_id = getYoutubeId($_POST['youtube_url']);
        if ($youtube_id) {
            $sql = "UPDATE media SET filename = ?, title = ?, description = ? WHERE id = ? AND is_youtube = 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $youtube_id, $title, $description, $id);
        }
    } else {
        $sql = "UPDATE media SET title = ?, description = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $title, $description, $id);
    }
    
    if ($stmt->execute()) {
        header('Location: admin.php?success=updated');
    } else {
        header('Location: admin.php?error=update_failed');
    }
}

function getYoutubeId($url) {
    $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';
    if (preg_match($pattern, $url, $matches)) {
        return $matches[1];
    }
    if (strlen($url) === 11) {
        return $url;
    }
    return false;
} 