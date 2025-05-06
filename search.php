<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_GET['query'])) {
    header("Location: index.php"); // No search query provided
    exit();
}

$query = $_GET['query'];
$search_results = $conn->query("
    SELECT * FROM products 
    WHERE name LIKE '%$query%' 
    OR description LIKE '%$query%'
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h4>Search Results for "<?= htmlspecialchars($query) ?>"</h4>

    <?php if ($search_results->num_rows > 0): ?>
        <div class="row">
            <?php while ($product = $search_results->fetch_assoc()): ?>
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <img src="../assets/images/<?= $product['image'] ?>" class="card-img-top" alt="<?= $product['name'] ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                            <p class="card-text">₹<?= number_format($product['price'], 2) ?></p>
                            <a href="product_detail.php?id=<?= $product['product_id'] ?>" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No results found.</p>
    <?php endif; ?>
</div>
</body>
</html>
