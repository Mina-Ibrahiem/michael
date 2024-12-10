<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sound Engineer Portfolio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'config.php'; ?>
    
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand animate__animated animate__fadeIn" href="#">Sound Engineer</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#gallery">Gallery</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <?php
            $sql = "SELECT * FROM profile LIMIT 1";
            $result = $conn->query($sql);
            $profile = $result->fetch_assoc();
        ?>
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-md-6" data-aos="fade-right">
                    <h1 class="display-1"><?php echo $profile['name']; ?></h1>
                    <p class="lead"><?php echo $profile['bio']; ?></p>
                </div>
                <div class="col-md-6" data-aos="fade-left">
                    <img src="uploads/profile/<?php echo $profile['image']; ?>" 
                         class="profile-image floating-animation">
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="section-padding">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up"><?php echo $profile['about_title']; ?></h2>
            <p class="text-center mb-5" data-aos="fade-up"><?php echo $profile['about_description']; ?></p>
            <div class="row">
                <div class="col-md-6" data-aos="fade-right">
                    <h3><?php echo $profile['experience_title']; ?></h3>
                    <p><?php echo $profile['experience']; ?></p>
                    <h3><?php echo $profile['skills_title']; ?></h3>
                    <p><?php echo $profile['skills']; ?></p>
                </div>
                <div class="col-md-6" data-aos="fade-left">
                    <h3><?php echo $profile['languages_title']; ?></h3>
                    <p><?php echo $profile['languages']; ?></p>
                    
                    <?php if ($profile['social_links']): ?>
                    <div class="social-links mt-4">
                        <h3>Follow Me</h3>
                        <?php
                            $social_links = json_decode($profile['social_links'], true);
                            if ($social_links) {
                                foreach ($social_links as $platform => $url) {
                                    echo '<a href="'.$url.'" target="_blank" class="btn btn-outline-primary me-2 mb-2">'.ucfirst($platform).'</a>';
                                }
                            }
                        ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section id="gallery" class="section-padding">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">My Work</h2>
            
            <!-- Media Filters -->
            <div class="filters mb-4" data-aos="fade-up">
                <button class="btn btn-outline-primary active" data-filter="all">All</button>
                <button class="btn btn-outline-primary" data-filter="image">Images</button>
                <button class="btn btn-outline-primary" data-filter="video">Videos</button>
                <button class="btn btn-outline-primary" data-filter="audio">Audio</button>
            </div>

            <div class="row gallery-container">
                <?php
                    $sql = "SELECT * FROM media ORDER BY upload_date DESC";
                    $result = $conn->query($sql);
                    while($row = $result->fetch_assoc()) {
                        echo '<div class="col-md-4 gallery-item" data-aos="zoom-in" data-type="'.$row['type'].'" onclick="openMedia(this)">';
                        if($row['type'] == 'image') {
                            echo '<img src="uploads/images/'.$row['filename'].'" class="img-fluid">';
                        } elseif($row['type'] == 'video') {
                            if ($row['is_youtube']) {
                                echo '<div class="video-container">
                                    <iframe 
                                        width="100%" 
                                        height="200" 
                                        src="https://www.youtube.com/embed/'.$row['filename'].'" 
                                        frameborder="0" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                        allowfullscreen>
                                    </iframe>
                                </div>';
                            } else {
                                echo '<div class="video-container">
                                    <video controls width="100%" height="200">
                                        <source src="uploads/videos/'.$row['filename'].'" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>';
                            }
                        } else {
                            echo '<audio controls><source src="uploads/audio/'.$row['filename'].'" type="audio/mpeg"></audio>';
                        }
                        echo '<h5>'.$row['title'].'</h5>';
                        echo '<p>'.$row['description'].'</p>';
                        echo '<input type="hidden" class="media-data" value="'.htmlspecialchars(json_encode($row)).'">';
                        echo '</div>';
                    }
                ?>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p>&copy; <?php echo date('Y'); ?> Sound Engineer. All rights reserved.</p>
        </div>
    </footer>

    <!-- Modal for Media -->
    <div class="modal fade" id="mediaModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="mediaContent" class="text-center"></div>
                    <p id="mediaDescription" class="mt-3"></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="js/main.js"></script>
    <script>
    function openMedia(element) {
        const mediaData = JSON.parse(element.querySelector('.media-data').value);
        const modal = new bootstrap.Modal(document.getElementById('mediaModal'));
        const modalTitle = document.querySelector('#mediaModal .modal-title');
        const mediaContent = document.getElementById('mediaContent');
        const mediaDescription = document.getElementById('mediaDescription');

        modalTitle.textContent = mediaData.title;
        mediaDescription.textContent = mediaData.description;

        if (mediaData.type === 'image') {
            mediaContent.innerHTML = `<img src="uploads/images/${mediaData.filename}" class="img-fluid">`;
        } else if (mediaData.type === 'video') {
            if (parseInt(mediaData.is_youtube)) {
                mediaContent.innerHTML = `<div class="video-container">
                    <iframe 
                        src="https://www.youtube.com/embed/${mediaData.filename}?autoplay=1" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                    </iframe>
                </div>`;
            } else {
                mediaContent.innerHTML = `<div class="video-container">
                    <video controls>
                        <source src="uploads/videos/${mediaData.filename}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>`;
            }
        } else if (mediaData.type === 'audio') {
            mediaContent.innerHTML = `<audio controls class="w-100">
                <source src="uploads/audio/${mediaData.filename}" type="audio/mpeg">
            </audio>`;
        }

        modal.show();
    }
    </script>
</body>
</html> 