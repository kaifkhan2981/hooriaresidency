<?php
include 'config.php';

$message_sent = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_lead'])) {
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $email = $conn->real_escape_string($_POST['email']);
    $unit_type = $conn->real_escape_string($_POST['unit_type']);
    $message = $conn->real_escape_string($_POST['message']);

    $sql = "INSERT INTO leads (full_name, phone, email, unit_type, message) VALUES ('$full_name', '$phone', '$email', '$unit_type', '$message')";
    if ($conn->query($sql) === TRUE) {
        $message_sent = true;
    }
}

// Fetch Gallery
$gallery_query = "SELECT * FROM gallery ORDER BY created_at DESC";
$gallery_result = $conn->query($gallery_query);

// Fetch Floor Plans
$plans_query = "SELECT * FROM floorplans ORDER BY created_at DESC";
$plans_result = $conn->query($plans_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Hooria Residency | Premium Living</title>
<link rel="stylesheet" href="css/style.css">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

<?php if ($message_sent): ?>
<div style="background: #d4af37; color: white; text-align: center; padding: 15px; position: sticky; top: 0; z-index: 1000;">
    Thank you! Our team will contact you soon.
</div>
<?php endif; ?>

<header>
    <div class="container">
        <h1 class="logo">Hooria Residency</h1>
        <a href="#contact" class="btn">Book Now</a>
    </div>
</header>

<section class="hero">
    <div class="overlay">
        <h2>Experience Premium Living</h2>
        <p>Modern Apartments | Flexible Installments</p>
        <a href="#contact" class="btn-primary">Schedule Visit</a>
    </div>
</section>

<section class="about">
    <div class="container">
        <h2>About The Project</h2>
        <p>Hooria Residency offers modern architecture, smart layouts and secure living environment ideal for families and investors.</p>
    </div>
</section>

<?php if ($plans_result->num_rows > 0): ?>
<section class="floor-plans">
    <div class="container">
        <h2>Floor Plans</h2>
        <?php while($plan = $plans_result->fetch_assoc()): ?>
        <div class="plan">
            <img src="uploads/plans/<?php echo $plan['image_path']; ?>" alt="<?php echo $plan['title']; ?>" style="max-width: 100%; border-radius: 10px; margin-bottom: 10px;">
            <h3><?php echo $plan['title']; ?></h3>
            <p><?php echo $plan['description']; ?></p>
        </div>
        <?php endwhile; ?>
    </div>
</section>
<?php else: ?>
<section class="floor-plans">
    <div class="container">
        <h2>Floor Plans</h2>
        <div class="plan">
            <h3>A1 Type</h3>
            <p>2 Bedrooms | Lounge | Kitchen | Balcony</p>
        </div>
        <div class="plan">
            <h3>B Type</h3>
            <p>1 Bedroom | Lounge | Kitchen | Balcony</p>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="payment">
    <div class="container">
        <h2>Flexible Payment Plan</h2>
        <p>30% Down Payment | Easy Monthly Installments</p>
    </div>
</section>

<section id="contact" class="contact">
    <div class="container">
        <h2>Book Your Unit</h2>
        <form id="leadForm" method="POST">
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="tel" name="phone" placeholder="Phone Number" required>
            <input type="email" name="email" placeholder="Email">
            <select name="unit_type">
                <option value="">Select Unit Type</option>
                <option value="A1 Type">A1 Type</option>
                <option value="B Type">B Type</option>
            </select>
            <textarea name="message" placeholder="Message"></textarea>
            <button type="submit" name="submit_lead">Submit</button>
        </form>
    </div>
</section>

<script src="js/script.js"></script>
</body>
</html>
