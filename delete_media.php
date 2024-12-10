<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Get media info before deleting
    $sql = "SELECT * FROM media WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $media = $result->fetch_assoc();
    
    if ($media) {
        // Delete file
        $file_path = 'uploads/' . $media['type'] . 's/' . $media['filename'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        
        // Delete from database
        $sql = "DELETE FROM media WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            header('Location: admin.php?success=deleted');
        } else {
            header('Location: admin.php?error=delete');
        }
    }
}

header('Location: admin.php'); 