<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $bio = $_POST['bio'];
    $skills = $_POST['skills'];
    $about_title = $_POST['about_title'];
    $about_description = $_POST['about_description'];
    $experience_title = $_POST['experience_title'];
    $experience = $_POST['experience'];
    $skills_title = $_POST['skills_title'];
    $languages_title = $_POST['languages_title'];
    $languages = $_POST['languages'];
    $social_links = $_POST['social_links'];
    
    $image_filename = null;
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $upload_dir = 'uploads/profile/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $image_filename = time() . '_' . basename($_FILES['profile_image']['name']);
        move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_dir . $image_filename);
    }
    
    $sql = "UPDATE profile SET name = ?, bio = ?, skills = ?, about_title = ?, about_description = ?, 
            experience_title = ?, experience = ?, skills_title = ?, languages_title = ?, languages = ?, 
            social_links = ?";
    $params = [$name, $bio, $skills, $about_title, $about_description, $experience_title, $experience, 
               $skills_title, $languages_title, $languages, $social_links];
    $types = "sssssssssss";
    
    if ($image_filename) {
        $sql .= ", image = ?";
        $params[] = $image_filename;
        $types .= "s";
    }
    
    $sql .= " WHERE id = 1";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    
    if ($stmt->execute()) {
        header('Location: admin.php?success=1');
    } else {
        header('Location: admin.php?error=1');
    }
} 