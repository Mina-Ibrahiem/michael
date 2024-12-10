-- Create database
CREATE DATABASE sound_engineer_db;
USE sound_engineer_db;

-- Create profile table
CREATE TABLE profile (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    image VARCHAR(255),
    bio TEXT,
    skills TEXT,
    experience TEXT,
    languages TEXT,
    about_title VARCHAR(255) DEFAULT 'About Me',
    experience_title VARCHAR(255) DEFAULT 'Experience',
    skills_title VARCHAR(255) DEFAULT 'Skills',
    languages_title VARCHAR(255) DEFAULT 'Languages',
    about_description TEXT,
    social_links TEXT
);

-- Create media table
CREATE TABLE media (
    id INT PRIMARY KEY AUTO_INCREMENT,
    type ENUM('image', 'video', 'audio') NOT NULL,
    filename VARCHAR(255) NOT NULL,
    title VARCHAR(255),
    description TEXT,
    category VARCHAR(100),
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_youtube TINYINT(1) DEFAULT 0
);

-- Create admin table
CREATE TABLE admin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Add default admin account (admin/admin)
INSERT INTO admin (username, password) VALUES ('admin', '$2y$10$8K1p/bFhF0RB.dgAKO1GUu7T7g6HHi5VeKwF9pcXGkHMexNyPEjm.');

-- Add initial profile data
INSERT INTO profile (name, bio) VALUES ('Sound Engineer Name', 'Professional Sound Engineer with extensive experience...'); 