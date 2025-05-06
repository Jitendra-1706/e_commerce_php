<?php
session_start();
include 'includes/db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home - MyStore</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <?php include 'includes/header.php'; ?>
    <link rel="shortcut icon" href="./assets/images/pngwing.com (3).png" type="image/x-icon">
    <link rel="stylesheet" href="./assets/css/index.css">

    <script>
    function scrollLeft(containerId) {
        const container = document.getElementById(containerId);
        container.scrollBy({ left: -300, behavior: 'smooth' });
    }

    function scrollRight(containerId) {
        const container = document.getElementById(containerId);
        container.scrollBy({ left: 300, behavior: 'smooth' });
    }
    </script>
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<!-- Carousel -->
<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
  <div class="carousel-indicators">
    <?php for ($i = 0; $i < 5; $i++) : ?>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?= $i ?>" class="<?= $i === 0 ? 'active' : '' ?>" aria-label="Slide <?= $i + 1 ?>"></button>
    <?php endfor; ?>
  </div>
  <div class="carousel-inner">
    <?php
    $carouselImages = [
        "offer.jpg",
        "infinix.jpg",
        "realmep3.jpg",
        "techno.jpg",
        "infinixnote50s.jpg"
    ];
    foreach ($carouselImages as $index => $image) {
        echo '<div class="carousel-item ' . ($index === 0 ? 'active' : '') . '">
                <img src="assets/images/caurosel/' . $image . '" class="d-block w-100" alt="Carousel Image">
              </div>';
    }
    ?>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

<!-- Product Grid -->
<div class="container mt-5">
    <h2 class="mb-4">Available Products</h2>
    <div class="row">

        <?php
        $sql = "SELECT * FROM products";
        $result = $con->query($sql);

        while ($row = $result->fetch_assoc()) {
            // Ensure the image path is relative to the root of the project
            $imagePath = "assets/images/" . ltrim($row['image'], '/'); // Adjusted path
            
            // Check if the image exists before displaying it
            if (!file_exists($imagePath)) {
                $imagePath = 'assets/images/default.jpg'; // Fallback image if original doesn't exist
            }
        ?>
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="<?php echo $imagePath; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['name']); ?>" style="height: 250px; width: 100%; object-fit: contain;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                        <p class="card-text">₹<?php echo number_format($row['price'], 2); ?></p>
                        <a href="product.php?id=<?php echo $row['product_id']; ?>" class="btn btn-primary btn-sm">View</a>
                        <a href="add_to_cart.php?id=<?php echo $row['product_id']; ?>" class="btn btn-success btn-sm">Add to Cart</a>
                    </div>
                </div>
            </div>
        <?php } ?>

    </div>
</div>

<!-- Best of Category -->
<?php
$category_id = 1; // Example category
$cat_res = mysqli_query($con, "SELECT name FROM categories WHERE category_id = $category_id");
$cat_name = mysqli_fetch_assoc($cat_res)['name'] ?? 'Products';

$products = mysqli_query($con, "SELECT * FROM products WHERE category_id = $category_id LIMIT 10");
?>

<?php
$category_sql = "SELECT * FROM categories";
$category_result = mysqli_query($con, $category_sql);

while ($cat = mysqli_fetch_assoc($category_result)) {
    $cat_id = $cat['category_id'];
    $cat_name = $cat['name'];

    $product_result = mysqli_query($con, "SELECT * FROM products WHERE category_id = $cat_id");
    if (mysqli_num_rows($product_result) > 0):
?>
<div id="category-<?php echo $cat_id; ?>" class="container my-5 p-4 shadow-sm bg-white rounded border">
  <h4 class="mb-3"><?php echo htmlspecialchars($cat_name); ?></h4>
  <div class="d-flex overflow-auto gap-3">
    <?php while($product = mysqli_fetch_assoc($product_result)) { 
        // Ensure the image path is relative to the root of the project
        $imagePath = "assets/images/" . ltrim($product['image'], '/');
        
        // Check if the image exists before displaying it
        if (!file_exists($imagePath)) {
            $imagePath = 'assets/images/default.jpg'; // Fallback image if original doesn't exist
        }
    ?>
      <div class="card" style="min-width: 200px; max-width: 200px;">
        <img src="<?php echo $imagePath; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>" style="height: 200px; object-fit: contain;">
        <div class="card-body">
          <h6 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h6>
          <p class="card-text">₹<?php echo number_format($product['price'], 2); ?></p>
          <a href="product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-sm btn-primary">View</a>
        </div>
      </div>
    <?php } ?>
  </div>
</div> 
<?php endif; } ?>

<?php include './includes/footer.php'; ?>
<?php include './includes/footer_part.php'; ?>
</body>
</html>
