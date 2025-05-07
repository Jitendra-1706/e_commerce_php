<?php
session_start();
include 'includes/db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Your Cart</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <?php include 'includes/header.php'; ?>
    <link rel="shortcut icon" href="./assets/images/logos/logo2.png" type="image/x-icon">

</head>
<body>

<?php include 'includes/navbar.php'; ?>

<div class="container mt-5">
    <h2>Your Shopping Cart</h2>

    <?php if (empty($_SESSION['cart'])): ?>
        <div class="alert alert-info">Your cart is empty. <a href="index.php">Shop now</a></div>
    <?php else: ?>
        <table class="table table-bordered mt-4">
            <thead class="table-dark">
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $grandTotal = 0;
                foreach ($_SESSION['cart'] as $id => $item):
                    $subtotal = $item['price'] * $item['quantity'];
                    $grandTotal += $subtotal;
                ?>
                <tr>
                    <td><img src="assets/images/<?php echo htmlspecialchars($item['image']); ?>" width="60" height="60" style="object-fit:cover;"></td>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td>₹<?php echo number_format($item['price'], 2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>₹<?php echo number_format($subtotal, 2); ?></td>
                    <td><a href="remove_from_cart.php?id=<?php echo $id; ?>" class="btn btn-danger btn-sm">Remove</a></td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="4" class="text-end"><strong>Total:</strong></td>
                    <td colspan="2"><strong>₹<?php echo number_format($grandTotal, 2); ?></strong></td>
                </tr>
            </tbody>
        </table>
        <a href="user/checkout.php" class="btn btn-primary">Proceed to Checkout</a>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
<script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
