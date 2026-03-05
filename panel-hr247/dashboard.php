<?php
include '../config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Fetch Leads Count
$leads_count = $conn->query("SELECT COUNT(*) as total FROM leads")->fetch_assoc()['total'];
// Fetch Gallery Count
$gallery_count = $conn->query("SELECT COUNT(*) as total FROM gallery")->fetch_assoc()['total'];
// Fetch Plans Count
$plans_count = $conn->query("SELECT COUNT(*) as total FROM floorplans")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Admin Panel</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .admin-container { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: #111; color: white; padding: 20px; }
        .sidebar h3 { color: #d4af37; margin-bottom: 30px; }
        .sidebar a { display: block; color: white; text-decoration: none; padding: 10px 0; border-bottom: 1px solid #333; }
        .sidebar a:hover { color: #d4af37; }
        .main-content { flex: 1; padding: 40px; background: #f9f9f9; }
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .stat-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-align: center; }
        .stat-card h4 { margin: 0; color: #666; }
        .stat-card p { font-size: 2em; font-weight: bold; margin: 10px 0; color: #d4af37; }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <h3>Admin Panel</h3>
            <a href="dashboard.php">Dashboard</a>
            <a href="leads.php">Leads (<?php echo $leads_count; ?>)</a>
            <a href="gallery.php">Gallery</a>
            <a href="floorplans.php">Floor Plans</a>
            <a href="settings.php">Settings</a>
            <a href="logout.php" style="color: #ff4444; border-top: 1px solid #333; margin-top: 20px;">Logout</a>
        </div>
        <div class="main-content">
            <h1>Welcome, <?php echo $_SESSION['admin_user']; ?></h1>
            <div class="stats-grid">
                <div class="stat-card">
                    <h4>Total Leads</h4>
                    <p><?php echo $leads_count; ?></p>
                </div>
                <div class="stat-card">
                    <h4>Gallery Images</h4>
                    <p><?php echo $gallery_count; ?></p>
                </div>
                <div class="stat-card">
                    <h4>Floor Plans</h4>
                    <p><?php echo $plans_count; ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
