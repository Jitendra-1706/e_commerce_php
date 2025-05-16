<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$cart = $conn->query("SELECT c.*, p.name, p.price FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.user_id = $user_id");

$total = 0;
$items = [];
while ($row = $cart->fetch_assoc()) {
    $subtotal = $row['price'] * $row['quantity'];
    $total += $subtotal;
    $items[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn->begin_transaction();
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, payment_method, status) VALUES (?, ?, 'Cash on Delivery', 'Pending')");
    $stmt->bind_param("id", $user_id, $total);
    $stmt->execute();
    $order_id = $conn->insert_id;

    $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($items as $item) {
        $stmt_items->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
        $stmt_items->execute();
    }

    $conn->query("DELETE FROM cart WHERE user_id = $user_id");
    $conn->commit();

    header("Location: orders.php?success=1");
    exit();
}


// Send email confirmation after order is placed
$to = $user['email'];
$subject = "Order Confirmation - Your Order #$order_id";
$message = "
    <html>
    <head><title>Order Confirmation</title></head>
    <body>
        <p>Dear {$user['username']},</p>
        <p>Thank you for your order! Your Order ID is #$order_id.</p>
        <p>Payment Method: {$order['payment_method']}</p>
        <p>Total: ₹$total</p>
        <p>We will notify you once your order is shipped.</p>
        <p>Best regards,</p>
        <p>Your Online Store</p>
    </body>
    </html>
";

// To send HTML mail, the Content-type header must be set
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";

// Additional headers
$headers .= "From: no-reply@yourstore.com" . "\r\n";

mail($to, $subject, $message, $headers);

?>

<!DOCTYPE html>
<html>

<head>
    <title>Cash on Delivery</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h4>Cash on Delivery</h4>
        <p>Total: ₹<?= number_format($total, 2) ?></p>
        <form method="post">
            <button type="submit" class="btn btn-warning">Place Order with COD</button>
        </form>
    </div>
</body>

</html>