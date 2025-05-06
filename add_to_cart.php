<?php
session_start();
include 'includes/db_connect.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$product_id = $_GET['id'];

// Fetch product
$sql = "SELECT * FROM products WHERE product_id = $product_id";
$result = $con->query($sql);

if ($result->num_rows == 0) {
    header("Location: index.php");
    exit();
}

$product = $result->fetch_assoc();

// Add to cart session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += 1;
} else {
    $_SESSION['cart'][$product_id] = [
        'name' => $product['name'],
        'price' => $product['price'],
        'image' => $product['image'],
        'quantity' => 1
    ];
}

header("Location: cart.php");
exit();
