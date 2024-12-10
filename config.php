<?php
// Database configuration
$servername = "localhost";
$username = "root";  // Default XAMPP username
$password = "";      // Default XAMPP password
$dbname = "sound_engineer_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4
$conn->set_charset("utf8mb4");

// Set timezone
date_default_timezone_set('UTC');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define upload directories
define('UPLOAD_IMAGE_PATH', 'uploads/images/');
define('UPLOAD_VIDEO_PATH', 'uploads/videos/');
define('UPLOAD_AUDIO_PATH', 'uploads/audio/');
define('UPLOAD_PROFILE_PATH', 'uploads/profile/');

// Create upload directories if they don't exist
$upload_dirs = [
    UPLOAD_IMAGE_PATH,
    UPLOAD_VIDEO_PATH,
    UPLOAD_AUDIO_PATH,
    UPLOAD_PROFILE_PATH
];

foreach ($upload_dirs as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
}
?>