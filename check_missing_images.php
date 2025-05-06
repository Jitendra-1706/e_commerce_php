<?php
include 'includes/db_connect.php';

echo "<h2>Missing Image Files Report</h2>";

$sql = "SELECT product_id, image, image2, image3 FROM products";
$result = $con->query($sql);

while ($row = $result->fetch_assoc()) {
    $productId = $row['product_id'];
    $images = ['image', 'image2', 'image3'];

    foreach ($images as $imgKey) {
        $rawPath = $row[$imgKey];

        // Normalize and prevent double 'assets/images/'
        $path = preg_replace('#/+#','/', $rawPath); // remove double slashes
        if (!str_starts_with($path, 'assets/images/')) {
            $path = 'assets/images/' . ltrim($path, '/');
        }

        if (!file_exists($path)) {
            echo "Product ID: $productId – $imgKey: $path<br>";
        }
    }
}
?>
