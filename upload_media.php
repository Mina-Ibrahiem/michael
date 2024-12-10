<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $media_type = $_POST['media_type'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    
    if ($media_type === 'video' && isset($_POST['video_source']) && $_POST['video_source'] === 'youtube') {
        // Handle YouTube URL
        $youtube_url = $_POST['youtube_url'];
        $youtube_id = getYoutubeId($youtube_url);
        
        if ($youtube_id) {
            $sql = "INSERT INTO media (type, filename, title, description, is_youtube) VALUES (?, ?, ?, ?, 1)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $media_type, $youtube_id, $title, $description);
            
            if ($stmt->execute()) {
                header('Location: admin.php?success=1');
            } else {
                header('Location: admin.php?error=db');
            }
        } else {
            header('Location: admin.php?error=invalid_url');
        }
    } else {
        // Handle file upload
        $file = $_FILES['media_file'];
        $upload_dir = 'uploads/' . $media_type . 's/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $filename = time() . '_' . basename($file['name']);
        $target_path = $upload_dir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $target_path)) {
            $sql = "INSERT INTO media (type, filename, title, description, is_youtube) VALUES (?, ?, ?, ?, 0)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $media_type, $filename, $title, $description);
            
            if ($stmt->execute()) {
                header('Location: admin.php?success=1');
            } else {
                header('Location: admin.php?error=db');
            }
        } else {
            header('Location: admin.php?error=upload');
        }
    }
}

function getYoutubeId($url) {
    $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';
    if (preg_match($pattern, $url, $matches)) {
        return $matches[1];
    }
    // Try to handle direct video ID input
    if (strlen($url) === 11) {
        return $url;
    }
    return false;
} 