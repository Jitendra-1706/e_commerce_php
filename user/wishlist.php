<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Add to wishlist
if (isset($_GET['add_to_wishlist'])) {
    $product_id = $_GET['add_to_wishlist'];
    $stmt = $conn->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $success_message = "Product added to wishlist!";
}

// Fetch wishlist
$wishlist = $conn->query("SELECT p.* FROM wishlist w JOIN products p ON w.product_id = p.product_id WHERE w.user_id = $user_id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Wishlist</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="shortcut icon" href="../assets/images/logos/logo2.png" type="image/x-icon">

</head>
<body>
<div class="container mt-5">
    <h4>My Wishlist</h4>

    <?php if (isset($success_message)) echo "<div class='alert alert-success'>$success_message</div>"; ?>

    <?php if ($wishlist->num_rows > 0): ?>
        <div class="row">
            <?php while ($item = $wishlist->fetch_assoc()): ?>
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <img src="../assets/images/<?= $item['image'] ?>" class="card-img-top" alt="<?= $item['name'] ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($item['name']) ?></h5>
                            <p class="card-text">₹<?= number_format($item['price'], 2) ?></p>
                            <a href="product_detail.php?id=<?= $item['product_id'] ?>" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>Your wishlist is empty.</p>
    <?php endif; ?>
</div>
</body>
</html>
