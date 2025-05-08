<?php
include 'includes/db_connect.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid product ID.");
}

$product_id = (int) $_GET['id']; // safe conversion to integer

// Get product details
$sql = "SELECT * FROM products WHERE product_id = $product_id";
$result = $con->query($sql);

if (!$result || $result->num_rows === 0) {
    die("Product with ID $product_id not found.");
}

$product = $result->fetch_assoc();

// Get related products
$category_id = $product['category_id'];
$related_sql = "SELECT * FROM products WHERE category_id = $category_id AND product_id != $product_id LIMIT 4";
$related_products = $con->query($related_sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo htmlspecialchars($product['name']); ?> - MyStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php include 'includes/header.php'; ?>
    <link rel="shortcut icon" href="./assets/images/logos/logo2.png" type="image/x-icon">

    <style>
        .carousel-item img {
            max-height: 300px;
            width: auto;
            object-fit: contain;
        }
    </style>
</head>
<body>
<?php include 'includes/navbar.php'; ?>

<div class="container mt-5">
    <div class="row">
        <!-- Carousel -->
        <div class="col-md-5">
            <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php
                    $images = [
                        $product['image'],
                        $product['image2'],
                        $product['image3']
                    ];

                    $first = true;
                    foreach ($images as $img) {
                        if (!empty($img)) {
                            echo '<div class="carousel-item ' . ($first ? 'active' : '') . '">';
                            echo '<img src="assets/images/' . htmlspecialchars($img) . '" class="d-block w-100">';
                            echo '</div>';
                            $first = false;
                        }
                    }
                    ?>
                </div>
                <?php if (count(array_filter($images)) > 1): ?>
                <button class="carousel-control-prev " type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next " type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-md-7">
            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
            <h4 class="text-success">₹<?php echo number_format($product['price'], 2); ?></h4>
            <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
            <a href="add_to_cart.php?id=<?php echo $product['product_id']; ?>" class="btn btn-success">Add to Cart</a>
        </div>
    </div>

    <!-- Related Products -->
    <?php if ($related_products->num_rows > 0): ?>
        <hr class="my-5">
        <h4>Related Products</h4>
        <div class="row">
            <?php while ($rel = $related_products->fetch_assoc()): ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <img src="assets/images/<?php echo htmlspecialchars($rel['image']); ?>" class="card-img-top" style="height: 200px; object-fit: contain;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($rel['name']); ?></h5>
                            <p class="card-text text-success">₹<?php echo number_format($rel['price'], 2); ?></p>
                            <a href="product.php?id=<?php echo $rel['product_id']; ?>" class="btn btn-outline-primary btn-sm">View</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include './includes/footer.php'; ?>
</body>
</html>
