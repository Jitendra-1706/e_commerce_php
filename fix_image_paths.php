<?php
include 'includes/db_connect.php';

$query = "SELECT product_id, image, image2, image3 FROM products";
$result = mysqli_query($con, $query);

$fixCount = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $product_id = $row['product_id'];
    $updates = [];

    foreach (['image', 'image2', 'image3'] as $field) {
        $originalPath = $row[$field];

        // Remove repeated or leading slashes
        $fixedPath = preg_replace('#(assets/images/)+#', 'assets/images/', $originalPath);
        $fixedPath = ltrim($fixedPath, '/');

        if ($originalPath !== $fixedPath) {
            $updates[$field] = $fixedPath;
        }
    }

    if (!empty($updates)) {
        $setParts = [];
        foreach ($updates as $field => $value) {
            $setParts[] = "$field = '" . mysqli_real_escape_string($con, $value) . "'";
        }

        $updateQuery = "UPDATE products SET " . implode(', ', $setParts) . " WHERE product_id = $product_id";
        mysqli_query($con, $updateQuery);
        echo "✔️ Fixed Product ID $product_id: " . implode(', ', $updates) . "<br>";
        $fixCount++;
    }
}

echo "<hr><strong>Total Products Updated: $fixCount</strong>";
?>
