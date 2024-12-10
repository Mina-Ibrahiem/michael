<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin'])) {
    header('HTTP/1.1 403 Forbidden');
    exit('Access denied');
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $sql = "SELECT * FROM media WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($media = $result->fetch_assoc()) {
        header('Content-Type: application/json');
        echo json_encode($media);
    } else {
        header('HTTP/1.1 404 Not Found');
        echo json_encode(['error' => 'Media not found']);
    }
} 