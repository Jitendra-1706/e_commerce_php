<?php include('admin_auth.php'); ?>
<?php
include '../includes/db_connect.php';

if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
    die("Invalid order ID.");
}

$orderId = intval($_GET['order_id']);

// Fetch order and user info
$orderStmt = $con->prepare("
    SELECT orders.*, users.name AS username
    FROM orders
    JOIN users ON orders.user_id = users.user_id
    WHERE orders.order_id = ?
");
$orderStmt->bind_param("i", $orderId);
$orderStmt->execute();
$orderResult = $orderStmt->get_result();
$order = $orderResult->fetch_assoc();
$orderStmt->close();

if (!$order) {
    die("Order not found.");
}

// Fetch order items
$itemStmt = $con->prepare("
    SELECT order_items.*, products.name AS product_name, products.price
    FROM order_items
    JOIN products ON order_items.product_id = products.product_id
    WHERE order_items.order_id = ?
");
$itemStmt->bind_param("i", $orderId);
$itemStmt->execute();
$itemsResult = $itemStmt->get_result();
$itemStmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php include '../includes/mode.php'; ?>
    <link rel="stylesheet" href="../assets/css/admin_bar.css">
    <link rel="shortcut icon" href="../assets/images/logos/logo2.png" type="image/x-icon">

</head>

<body>

    <div class="d-flex">
        <?php include '../includes/admin_sidebar.php'; ?>
        <div class="flex-grow-1 p-4" style="margin-left: 250px;">
            <div class="container mt-5">
                <h2>Order Details - ID #<?= $order['order_id'] ?></h2>

                <div class="mb-4">
                    <p><strong>Customer:</strong> <?= htmlspecialchars($order['username']) ?></p>
                    <p><strong>Order Date:</strong> <?= $order['order_date'] ?></p>
                    <p><strong>Total Price:</strong> ₹<?= number_format($order['total_price'], 2) ?></p>
                    <p><strong>Payment Method:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>
                    <p><strong>Status:</strong>
                        <span class="badge <?= $order['status'] === 'Delivered' ? 'bg-success' : 'bg-warning' ?>">
                            <?= $order['status'] ?>
                        </span>
                    </p>
                </div>

                <h4>Products in this Order</h4>
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Price (each)</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        while ($item = $itemsResult->fetch_assoc()):
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($item['product_name']) ?></td>
                                <td>₹<?= number_format($item['price'], 2) ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td>₹<?= number_format($subtotal, 2) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Total</th>
                            <th>₹<?= number_format($total, 2) ?></th>
                        </tr>
                    </tfoot>
                </table>

                <a href="orders.php" class="btn btn-secondary mt-3">← Back to Orders</a>
            </div>
        </div>
    </div>






    <script>
        if (localStorage.getItem("theme") === "dark") {
            document.body.classList.add("dark-mode");
        }

        if (localStorage.getItem("primaryColor")) {
            document.documentElement.style.setProperty('--primary-color', localStorage.getItem("primaryColor"));
        }
    </script>

</body>

</html>