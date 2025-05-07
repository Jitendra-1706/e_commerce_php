<?php
session_start();
include 'includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $con->prepare("SELECT name, email, created_at FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="./assets/images/logos/logo2.png" type="image/x-icon">

</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-5">
        <h2 class="mb-4">My Profile</h2>
        <div class="card shadow-sm p-4">
            <div class="mb-3">
                <strong>Name:</strong> <?= htmlspecialchars($user['name']) ?>
            </div>
            <div class="mb-3">
                <strong>Email:</strong> <?= htmlspecialchars($user['email']) ?>
            </div>
            <div>
                <strong>Member Since:</strong> <?= date("F j, Y", strtotime($user['created_at'])) ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
