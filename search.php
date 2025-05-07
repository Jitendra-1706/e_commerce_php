<?php
session_start();
include 'includes/db_connect.php'; // Adjust the path if needed
include 'includes/header.php';
include 'includes/navbar.php';

$searchTerm = isset($_GET['q']) ? trim($_GET['q']) : '';
$searchTermSafe = mysqli_real_escape_string($con, $searchTerm);
$products = [];

if ($searchTerm !== '') {
    $sql = "SELECT * FROM products WHERE name LIKE '%$searchTermSafe%'";
    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
    }
}
?>

<div class="container mt-5">
    <h3>Search Results for: <strong><?php echo htmlspecialchars($searchTerm); ?></strong></h3>
    <div class="row mt-4">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <img src="assets/images/<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top" alt="Product Image">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text">₹<?php echo number_format($product['price'], 2); ?></p>
                            <a href="product_details.php?id=<?php echo $product['product_id']; ?>" class="btn btn-primary btn-sm">View</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning text-center">
                    No products found matching "<strong><?php echo htmlspecialchars($searchTerm); ?></strong>"
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
