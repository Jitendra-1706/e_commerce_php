<?php
session_start();
include 'includes/db_connect.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$product_id = (int)$_GET['id'];

$sql = "SELECT * FROM products WHERE product_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: index.php");
    exit();
}

$product = $result->fetch_assoc();

// Start cart session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += 1;
} else {
    $_SESSION['cart'][$product_id] = [
        'name' => $product['name'],
        'price' => $product['price'],
        'image' => basename($product['image']), // Just filename
        'quantity' => 1
    ];
}

header("Location: cart.php");
exit();
