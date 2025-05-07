<?php
session_start();
include 'includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $con->prepare("
    SELECT p.product_id, p.name, p.price, p.image 
    FROM wishlist w 
    JOIN products p ON w.product_id = p.product_id 
    WHERE w.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Wishlist</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="./assets/images/logos/logo2.png" type="image/x-icon">

</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-5">
        <h2 class="mb-4">My Wishlist</h2>
        <?php if ($result->num_rows > 0): ?>
            <div class="row">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <img src="assets/images/<?= htmlspecialchars($row['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['name']) ?>" style="height: 200px; object-fit: contain;">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
                                <p class="card-text text-success fw-bold">₹<?= number_format($row['price'], 2) ?></p>
                                <a href="product_details.php?id=<?= $row['product_id'] ?>" class="btn btn-primary btn-sm">View</a>
                                <a href="remove_from_wishlist.php?product_id=<?= $row['product_id'] ?>" class="btn btn-danger btn-sm float-end">Remove</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">Your wishlist is empty.</div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
