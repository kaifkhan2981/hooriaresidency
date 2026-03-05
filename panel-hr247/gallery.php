<?php
include '../config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

$error = "";
$success = "";

// Handle Upload
if (isset($_POST['upload_gallery'])) {
    $target_dir = "../uploads/gallery/";
    $title = $conn->real_escape_string($_POST['title']);
    $file_name = time() . "_" . basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $conn->query("INSERT INTO gallery (image_path, title) VALUES ('$file_name', '$title')");
            $success = "Image uploaded successfully.";
        } else {
            $error = "Sorry, there was an error uploading your file.";
        }
    } else {
        $error = "File is not an image.";
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $res = $conn->query("SELECT image_path FROM gallery WHERE id = $id");
    if ($res->num_rows > 0) {
        $img = $res->fetch_assoc();
        unlink("../uploads/gallery/" . $img['image_path']);
        $conn->query("DELETE FROM gallery WHERE id = $id");
        $success = "Image deleted.";
    }
}

$gallery = $conn->query("SELECT * FROM gallery ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Gallery | Admin Panel</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .admin-container { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: #111; color: white; padding: 20px; }
        .sidebar h3 { color: #d4af37; margin-bottom: 30px; }
        .sidebar a { display: block; color: white; text-decoration: none; padding: 10px 0; border-bottom: 1px solid #333; }
        .sidebar a:hover { color: #d4af37; }
        .main-content { flex: 1; padding: 40px; background: #f9f9f9; }
        .gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; margin-top: 30px; }
        .gallery-item { background: white; padding: 10px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center; }
        .gallery-item img { width: 100%; height: 150px; object-fit: cover; border-radius: 5px; }
        .gallery-item .btn-delete { color: red; text-decoration: none; font-size: 0.9em; margin-top: 10px; display: inline-block; }
        .upload-form { background: white; padding: 20px; border-radius: 10px; margin-bottom: 30px; }
        .btn-submit { background: #d4af37; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <h3>Admin Panel</h3>
            <a href="dashboard.php">Dashboard</a>
            <a href="leads.php">Leads</a>
            <a href="gallery.php">Gallery</a>
            <a href="floorplans.php">Floor Plans</a>
            <a href="settings.php">Settings</a>
            <a href="logout.php" style="color: #ff4444; border-top: 1px solid #333; margin-top: 20px;">Logout</a>
        </div>
        <div class="main-content">
            <h1>Gallery Management</h1>
            
            <?php if ($error): ?><p style="color:red;"><?php echo $error; ?></p><?php endif; ?>
            <?php if ($success): ?><p style="color:green;"><?php echo $success; ?></p><?php endif; ?>

            <div class="upload-form">
                <h3>Upload New Image</h3>
                <form method="POST" enctype="multipart/form-data">
                    <input type="text" name="title" placeholder="Image Title" required style="width: 200px; margin-right: 10px;">
                    <input type="file" name="image" required style="margin-right: 10px;">
                    <button type="submit" name="upload_gallery" class="btn-submit">Upload</button>
                </form>
            </div>

            <div class="gallery-grid">
                <?php while($row = $gallery->fetch_assoc()): ?>
                <div class="gallery-item">
                    <img src="../uploads/gallery/<?php echo $row['image_path']; ?>" alt="">
                    <p><?php echo $row['title']; ?></p>
                    <a href="?delete=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Delete this image?')">Delete</a>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</body>
</html>
