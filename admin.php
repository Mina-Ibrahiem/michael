<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

include 'config.php';

// Fetch current profile data
$sql = "SELECT * FROM profile LIMIT 1";
$result = $conn->query($sql);
$profile = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/admin.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Admin Panel</a>
            <a class="btn btn-danger" href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="container mt-5">
        <!-- Profile Settings -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5>Profile Settings</h5>
            </div>
            <div class="card-body">
                <form action="update_profile.php" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" value="<?php echo $profile['name']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label>Bio</label>
                                <textarea name="bio" class="form-control" rows="3"><?php echo $profile['bio']; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label>About Section Title</label>
                                <input type="text" name="about_title" class="form-control" value="<?php echo $profile['about_title']; ?>">
                            </div>
                            <div class="mb-3">
                                <label>About Description</label>
                                <textarea name="about_description" class="form-control" rows="3"><?php echo $profile['about_description']; ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Profile Picture</label>
                                <input type="file" name="profile_image" class="form-control" accept="image/*">
                            </div>
                            <div class="mb-3">
                                <label>Experience Title</label>
                                <input type="text" name="experience_title" class="form-control" value="<?php echo $profile['experience_title']; ?>">
                            </div>
                            <div class="mb-3">
                                <label>Experience</label>
                                <textarea name="experience" class="form-control" rows="3"><?php echo $profile['experience']; ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Skills Title</label>
                                <input type="text" name="skills_title" class="form-control" value="<?php echo $profile['skills_title']; ?>">
                            </div>
                            <div class="mb-3">
                                <label>Skills</label>
                                <textarea name="skills" class="form-control" rows="3"><?php echo $profile['skills']; ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Languages Title</label>
                                <input type="text" name="languages_title" class="form-control" value="<?php echo $profile['languages_title']; ?>">
                            </div>
                            <div class="mb-3">
                                <label>Languages</label>
                                <textarea name="languages" class="form-control" rows="3"><?php echo $profile['languages']; ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="mb-3">
                                <label>Social Links (JSON format)</label>
                                <textarea name="social_links" class="form-control" rows="3" placeholder='{"facebook": "url", "twitter": "url"}'><?php echo $profile['social_links']; ?></textarea>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>

        <!-- Media Upload -->
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5>Upload Media</h5>
            </div>
            <div class="card-body">
                <form action="upload_media.php" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Media Type</label>
                                <select name="media_type" class="form-control" id="mediaType">
                                    <option value="image">Image</option>
                                    <option value="video">Video</option>
                                    <option value="audio">Audio</option>
                                </select>
                            </div>
                            <div class="mb-3" id="videoOptions" style="display: none;">
                                <label>Video Source</label>
                                <select name="video_source" class="form-control" id="videoSource">
                                    <option value="file">Upload File</option>
                                    <option value="youtube">YouTube Link</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Title</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3" id="fileUpload">
                                <label>File</label>
                                <input type="file" name="media_file" class="form-control" required>
                            </div>
                            <div class="mb-3" id="youtubeLink" style="display: none;">
                                <label>YouTube URL</label>
                                <input type="url" name="youtube_url" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                            </div>
                            <div class="mb-3">
                                <label>Description</label>
                                <textarea name="description" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Upload</button>
                </form>
            </div>
        </div>

        <!-- Media Management -->
        <div class="card mt-4">
            <div class="card-header bg-info text-white">
                <h5>Media Management</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Upload Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM media ORDER BY upload_date DESC";
                            $result = $conn->query($sql);
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$row['title']}</td>
                                        <td>{$row['type']}</td>
                                        <td>{$row['upload_date']}</td>
                                        <td>
                                            <a href='#' class='btn btn-primary btn-sm me-2' onclick='editMedia({$row['id']}, this)'>Edit</a>
                                            <a href='delete_media.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                                        </td>
                                    </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Media Modal -->
    <div class="modal fade" id="editMediaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Media</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editMediaForm" action="edit_media.php" method="POST">
                        <input type="hidden" name="media_id" id="editMediaId">
                        <div class="mb-3">
                            <label>Title</label>
                            <input type="text" name="title" id="editMediaTitle" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" id="editMediaDescription" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3" id="editYoutubeUrl" style="display: none;">
                            <label>YouTube URL</label>
                            <input type="url" name="youtube_url" id="editMediaYoutubeUrl" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('mediaType').addEventListener('change', function() {
            const videoOptions = document.getElementById('videoOptions');
            const fileUpload = document.getElementById('fileUpload');
            const youtubeLink = document.getElementById('youtubeLink');
            const mediaFileInput = fileUpload.querySelector('input[name="media_file"]');
            
            if (this.value === 'video') {
                videoOptions.style.display = 'block';
            } else {
                videoOptions.style.display = 'none';
                fileUpload.style.display = 'block';
                youtubeLink.style.display = 'none';
                mediaFileInput.required = true;
            }
        });

        document.getElementById('videoSource').addEventListener('change', function() {
            const fileUpload = document.getElementById('fileUpload');
            const youtubeLink = document.getElementById('youtubeLink');
            const mediaFileInput = fileUpload.querySelector('input[name="media_file"]');
            const youtubeLinkInput = youtubeLink.querySelector('input[name="youtube_url"]');
            
            if (this.value === 'youtube') {
                fileUpload.style.display = 'none';
                youtubeLink.style.display = 'block';
                mediaFileInput.required = false;
                youtubeLinkInput.required = true;
            } else {
                fileUpload.style.display = 'block';
                youtubeLink.style.display = 'none';
                mediaFileInput.required = true;
                youtubeLinkInput.required = false;
            }
        });

        function editMedia(id, button) {
            const row = button.closest('tr');
            const title = row.cells[0].textContent;
            const type = row.cells[1].textContent;
            
            // Get media details via AJAX
            fetch('get_media.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('editMediaId').value = id;
                    document.getElementById('editMediaTitle').value = data.title;
                    document.getElementById('editMediaDescription').value = data.description;
                    
                    if (data.is_youtube) {
                        document.getElementById('editYoutubeUrl').style.display = 'block';
                        document.getElementById('editMediaYoutubeUrl').value = 'https://youtube.com/watch?v=' + data.filename;
                    } else {
                        document.getElementById('editYoutubeUrl').style.display = 'none';
                    }
                    
                    new bootstrap.Modal(document.getElementById('editMediaModal')).show();
                });
        }
    </script>
</body>
</html> 