<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$orders = [];

$order_stmt = $con->prepare("SELECT id, created_at, status FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$order_stmt->bind_param("i", $user_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();

while ($order = $order_result->fetch_assoc()) {
    $order_id = $order['id'];
    $items_stmt = $con->prepare("
        SELECT p.name, p.price, oi.quantity
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        WHERE oi.order_id = ?");
    $items_stmt->bind_param("i", $order_id);
    $items_stmt->execute();
    $items_result = $items_stmt->get_result();
    $order['items'] = $items_result->fetch_all(MYSQLI_ASSOC);
    $orders[] = $order;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Orders</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2>Order History</h2>
        <?php foreach ($orders as $order): ?>
            <div class="card mb-3 p-3">
                <h5>Order #<?= $order['id'] ?> | <?= $order['created_at'] ?> | Status: <?= $order['status'] ?></h5>
                <ul>
                    <?php foreach ($order['items'] as $item): ?>
                        <li><?= htmlspecialchars($item['name']) ?> x <?= $item['quantity'] ?> – ₹<?= $item['price'] * $item['quantity'] ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
