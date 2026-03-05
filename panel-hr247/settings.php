<?php
include '../config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

$error = "";
$success = "";

if (isset($_POST['update_password'])) {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    $username = $_SESSION['admin_user'];
    $res = $conn->query("SELECT password FROM users WHERE username = '$username'");
    $user = $res->fetch_assoc();

    if (password_verify($current, $user['password'])) {
        if ($new === $confirm) {
            $hashed = password_hash($new, PASSWORD_DEFAULT);
            $conn->query("UPDATE users SET password = '$hashed' WHERE username = '$username'");
            $success = "Password updated successfully.";
        } else {
            $error = "New passwords do not match.";
        }
    } else {
        $error = "Current password incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings | Admin Panel</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .admin-container { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: #111; color: white; padding: 20px; }
        .sidebar h3 { color: #d4af37; margin-bottom: 30px; }
        .sidebar a { display: block; color: white; text-decoration: none; padding: 10px 0; border-bottom: 1px solid #333; }
        .sidebar a:hover { color: #d4af37; }
        .main-content { flex: 1; padding: 40px; background: #f9f9f9; }
        .settings-card { background: white; padding: 30px; border-radius: 10px; max-width: 500px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        .btn-submit { background: #d4af37; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 5px; width: 100%; }
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
            <h1>Account Settings</h1>
            
            <?php if ($error): ?><p style="color:red;"><?php echo $error; ?></p><?php endif; ?>
            <?php if ($success): ?><p style="color:green;"><?php echo $success; ?></p><?php endif; ?>

            <div class="settings-card">
                <h3>Change Password</h3>
                <form method="POST">
                    <div class="form-group">
                        <label>Current Password</label>
                        <input type="password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input type="password" name="confirm_password" required>
                    </div>
                    <button type="submit" name="update_password" class="btn-submit">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
