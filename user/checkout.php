<?php
session_start();
include('../includes/db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$has_address = false;
$order_placed = false;
$total_price = 0;

// Check if address exists
$check = $con->query("SELECT * FROM user_addresses WHERE user_id = $user_id LIMIT 1");
if ($check && $check->num_rows > 0) {
    $has_address = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <?php include('../includes/header.php'); ?>
    <link rel="shortcut icon" href="../assets/images/logos/logo2.png" type="image/x-icon">


    <style>
        .step { display: none; }
        .step.active { display: block; }
    </style>
</head>
<body>

<?php include('../includes/navbar.php'); ?>

<div class="container my-5">
    <h2 class="mb-4">Checkout Process</h2>

    <!-- Step 1 -->
    <div class="step <?php if (!$has_address) echo 'active'; ?>" id="step1">
        <h4>Step 1: Enter Shipping Address</h4>
        <form action="save_address.php" method="POST">
            <textarea name="address" class="form-control mb-2" placeholder="Full Address" required></textarea>
            <input type="text" name="city" class="form-control mb-2" placeholder="City" required>
            <input type="text" name="state" class="form-control mb-2" placeholder="State" required>
            <input type="text" name="postal_code" class="form-control mb-2" placeholder="Postal Code" required>
            <input type="text" name="country" class="form-control mb-3" placeholder="Country" required>
            <button type="submit" class="btn btn-primary">Save and Continue</button>
        </form>
    </div>

    <!-- Step 2 -->
    <div class="step <?php if ($has_address && !$order_placed) echo 'active'; ?>" id="step2">
        <h4>Step 2: Review Your Order</h4>

        <?php if (!empty($_SESSION['cart'])): ?>
            <div class="row">
                <?php foreach ($_SESSION['cart'] as $id => $item): 
                    $subtotal = $item['price'] * $item['quantity'];
                    $total_price += $subtotal;
                ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <img src="../assets/images/<?php echo htmlspecialchars($item['image']); ?>" class="card-img-top" style="height: 180px; object-fit:contain;">
                        <div class="card-body">
                            <h5><?php echo htmlspecialchars($item['name']); ?></h5>
                            <p>Price: ₹<?php echo number_format($item['price'], 2); ?></p>
                            <p>Qty: <?php echo $item['quantity']; ?></p>
                            <p><strong>Subtotal: ₹<?php echo number_format($subtotal, 2); ?></strong></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <p class="fw-bold">Total: ₹<?php echo number_format($total_price, 2); ?></p>
            <button class="btn btn-warning" onclick="goToStep(3)">Proceed to Payment</button>
        <?php else: ?>
            <div class="alert alert-warning">Your cart is empty.</div>
        <?php endif; ?>
    </div>

    <!-- Step 3 -->
    <div class="step" id="step3">
        <h4>Step 3: Payment</h4>
        <form method="POST" action="place_order.php">
            <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
            <label>Select Payment Method:</label>
            <select name="payment_method" class="form-control mb-3" required>
                <option value="COD">Cash on Delivery</option>
                <option value="Online">Online (Dummy)</option>
            </select>
            <button type="submit" class="btn btn-success">Place Order</button>
        </form>
    </div>

    <!-- Step 4 -->
    <?php if (isset($_GET['order']) && $_GET['order'] == 'success'): ?>
        <div class="step active" id="step4">
            <h4>Step 4: Confirmation</h4>
            <div class="alert alert-success">Order placed successfully! Redirecting...</div>
            <a href="../index.php" class="btn btn-primary">Go to Homepage</a>
            <script>
                setTimeout(() => {
                    window.location.href = "../index.php";
                }, 5000);
            </script>
        </div>
    <?php endif; ?>
</div>

<script>
function goToStep(step) {
    document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
    document.getElementById('step' + step).classList.add('active');
}
</script>

<?php include('../includes/footer.php'); ?>
</body>
</html>
