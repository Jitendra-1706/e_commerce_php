<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch orders for this user
$orders = $conn->query("
    SELECT * FROM orders 
    WHERE user_id = $user_id 
    ORDER BY order_date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Orders</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h4 class="mb-4">My Orders</h4>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Order placed successfully!</div>
    <?php endif; ?>

    <?php if ($orders->num_rows > 0): ?>
        <?php while ($order = $orders->fetch_assoc()): ?>
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <strong>Order #<?= $order['order_id'] ?></strong> |
                    ₹<?= number_format($order['total_amount'], 2) ?> |
                    <?= $order['order_date'] ?>
                </div>
                <div class="card-body">
                    <p><strong>Payment:</strong> <?= $order['payment_method'] ?></p>
                    <p><strong>Status:</strong> <?= $order['status'] ?></p>

                    <h6>Items:</h6>
                    <ul>
                    <?php
                        $order_id = $order['order_id'];
                        $items = $conn->query("
                            SELECT oi.*, p.name 
                            FROM order_items oi 
                            JOIN products p ON oi.product_id = p.product_id 
                            WHERE oi.order_id = $order_id
                        ");
                        while ($item = $items->fetch_assoc()) {
                            echo "<li>{$item['name']} × {$item['quantity']} (₹" . number_format($item['price'], 2) . ")</li>";
                        }
                    ?>
                    </ul>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="alert alert-info">You haven't placed any orders yet.</div>
    <?php endif; ?>
</div>
</body>
</html>
