<?php
// Since you're already in 'includes/', no need to include 'includes/' again
include 'db_connect.php';

$query = "SELECT product_id, image, image2, image3 FROM products";
$result = mysqli_query($con, $query);

echo "<h2>Missing Image Files Report</h2>";
echo "<ul>";

while ($row = mysqli_fetch_assoc($result)) {
    $product_id = $row['product_id'];

    foreach (['image', 'image2', 'image3'] as $field) {
        $imagePath = $row[$field];

        // Normalize path to avoid duplication like assets/images/assets/images/
        $normalizedPath = preg_replace('#^assets/images/assets/images/#', 'assets/images/', $imagePath);
        $normalizedPath = preg_replace('#^/+#', '', $normalizedPath); // remove leading slashes

        // Convert to full path
        $fullPath = __DIR__ . '/../' . $normalizedPath;

        if (!file_exists($fullPath)) {
            echo "<li>Product ID: $product_id – $field: $normalizedPath <span style='color: red'>(Missing)</span></li>";
        }
    }
}

echo "</ul>";
?>
