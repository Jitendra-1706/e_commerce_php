<?php
session_start();
include('../includes/db_connect.php');

if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
    header("Location: checkout.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'];
$payment_method = $_POST['payment_method'] ?? 'COD';
$total_price = $_POST['total_price'] ?? 0;

// Insert order into `orders` table
$stmt = $con->prepare("INSERT INTO orders (user_id, total_price, payment_method, status, order_date) VALUES (?, ?, ?, 'Pending', NOW())");
$stmt->bind_param("ids", $user_id, $total_price, $payment_method);
$stmt->execute();

$order_id = $stmt->insert_id;

// Insert each item into `order_items` table
foreach ($cart as $product_id => $item) {
    $quantity = $item['quantity'];

    $stmt_item = $con->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
    $stmt_item->bind_param("iii", $order_id, $product_id, $quantity);
    $stmt_item->execute();
}

// OPTIONAL: Reduce stock
foreach ($cart as $product_id => $item) {
    $quantity = $item['quantity'];
    $con->query("UPDATE products SET stock = stock - $quantity WHERE product_id = $product_id");
}

// Clear session cart and address flag
unset($_SESSION['cart']);
$_SESSION['address_saved'] = false;

// Redirect to confirmation
header("Location: checkout.php?order=success");
exit();
?>
