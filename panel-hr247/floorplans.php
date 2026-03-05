<?php
include '../config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

$error = "";
$success = "";

// Handle Upload
if (isset($_POST['upload_plan'])) {
    $target_dir = "../uploads/plans/";
    $title = $conn->real_escape_string($_POST['title']);
    $desc = $conn->real_escape_string($_POST['description']);
    $file_name = time() . "_" . basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $conn->query("INSERT INTO floorplans (image_path, title, description) VALUES ('$file_name', '$title', '$desc')");
        $success = "Floor plan uploaded.";
    } else {
        $error = "Upload failed.";
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $res = $conn->query("SELECT image_path FROM floorplans WHERE id = $id");
    if ($res->num_rows > 0) {
        $img = $res->fetch_assoc();
        unlink("../uploads/plans/" . $img['image_path']);
        $conn->query("DELETE FROM floorplans WHERE id = $id");
        $success = "Plan deleted.";
    }
}

$plans = $conn->query("SELECT * FROM floorplans ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Floor Plans | Admin Panel</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .admin-container { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: #111; color: white; padding: 20px; }
        .sidebar h3 { color: #d4af37; margin-bottom: 30px; }
        .sidebar a { display: block; color: white; text-decoration: none; padding: 10px 0; border-bottom: 1px solid #333; }
        .sidebar a:hover { color: #d4af37; }
        .main-content { flex: 1; padding: 40px; background: #f9f9f9; }
        .plans-list { margin-top: 30px; }
        .plan-card { background: white; padding: 20px; border-radius: 10px; display: flex; align-items: center; gap: 20px; margin-bottom: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .plan-card img { width: 100px; height: 100px; object-fit: cover; border-radius: 5px; }
        .plan-card div { flex: 1; }
        .plan-card h4 { margin: 0 0 5px 0; }
        .btn-delete { color: red; text-decoration: none; }
        .upload-form { background: white; padding: 20px; border-radius: 10px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group input, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
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
            <h1>Floor Plans</h1>
            
            <?php if ($success): ?><p style="color:green;"><?php echo $success; ?></p><?php endif; ?>

            <div class="upload-form">
                <h3>Add New Floor Plan</h3>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Title (e.g., A1 Type)</label>
                        <input type="text" name="title" required>
                    </div>
                    <div class="form-group">
                        <label>Description (e.g., 2 Bedrooms | Lounge ...)</label>
                        <textarea name="description" rows="2" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Image</label>
                        <input type="file" name="image" required>
                    </div>
                    <button type="submit" name="upload_plan" class="btn-submit">Add Plan</button>
                </form>
            </div>

            <div class="plans-list">
                <?php while($row = $plans->fetch_assoc()): ?>
                <div class="plan-card">
                    <img src="../uploads/plans/<?php echo $row['image_path']; ?>" alt="">
                    <div>
                        <h4><?php echo $row['title']; ?></h4>
                        <p><?php echo $row['description']; ?></p>
                    </div>
                    <a href="?delete=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Delete this plan?')">Delete</a>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</body>
</html>
