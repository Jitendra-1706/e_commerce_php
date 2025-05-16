<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Get cart items
$user_id = $_SESSION['user_id'];
$cart = $con->query("SELECT c.*, p.name, p.price FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.user_id = $user_id");

$total = 0;
$items = [];
while ($row = $cart->fetch_assoc()) {
    $subtotal = $row['price'] * $row['quantity'];
    $total += $subtotal;
    $items[] = $row;
}

// Place order
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $card_no = $_POST['card_no'];
    $expiry = $_POST['expiry'];
    $cvv = $_POST['cvv'];

    // Simplified - in real life validate properly
    if (strlen($card_no) < 12 || strlen($cvv) != 3) {
        $error = "Invalid card details.";
    } else {
        $conn->begin_transaction();

        $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, payment_method, status) VALUES (?, ?, 'Card', 'Pending')");
        $stmt->bind_param("id", $user_id, $total);
        $stmt->execute();
        $order_id = $con->insert_id;

        $stmt_items = $con->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        foreach ($items as $item) {
            $stmt_items->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
            $stmt_items->execute();
        }

        $conn->query("DELETE FROM cart WHERE user_id = $user_id");
        $conn->commit();

        header("Location: orders.php?success=1");
        exit();
    }
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
    <title>Pay by Card</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h4>Card Payment</h4>
        <p>Total: ₹<?= number_format($total, 2) ?></p>

        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

        <form method="post">
            <input type="text" name="card_no" class="form-control mb-2" placeholder="Card Number" required>
            <input type="text" name="expiry" class="form-control mb-2" placeholder="MM/YY" required>
            <input type="text" name="cvv" class="form-control mb-2" placeholder="CVV" required>
            <button type="submit" class="btn btn-primary">Pay & Place Order</button>
        </form>
    </div>
</body>

</html>