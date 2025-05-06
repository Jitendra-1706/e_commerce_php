<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include('../includes/db_connect.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$has_address = false;
$order_placed = false;
$total_price = 0;

// ✅ Check from DB if address exists
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
    <!-- <link rel="stylesheet" href="../assets/css/style.css"> -->
    <?php include('../includes/header.php'); ?>
    <style>
        .step { display: none; }
        .step.active { display: block; }
    </style>
</head>
<body>

<div class="container my-5">
    <h2 class="mb-4">Checkout Process</h2>

    <!-- Step 1: Address Form -->
    <div class="step <?php if (!$has_address) echo 'active'; ?>" id="step1">
        <h4 class="mb-3">Step 1: Enter Shipping Address</h4>
        <form action="save_address.php" method="POST">
            <textarea name="address" placeholder="Full Address" class="form-control mb-2" required></textarea>
            <input type="text" name="city" class="form-control mb-2" placeholder="City" required>
            <input type="text" name="state" class="form-control mb-2" placeholder="State" required>
            <input type="text" name="postal_code" class="form-control mb-2" placeholder="Postal Code" required>
            <input type="text" name="country" class="form-control mb-3" placeholder="Country" required>
            <button type="submit" class="btn btn-primary">Save and Continue</button>
        </form>
    </div>

    <!-- Step 2: Review Cart (Bootstrap Cards) -->
    <div class="step <?php if ($has_address && !$order_placed) echo 'active'; ?>" id="step2">
        <h4 class="mb-4">Step 2: Review Your Order</h4>

        <?php if (!empty($_SESSION['cart'])): ?>
        <div class="row">
            <?php foreach ($_SESSION['cart'] as $id => $item): 
                $subtotal = $item['price'] * $item['quantity'];
                $total_price += $subtotal;
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="<?php echo $item['image']; ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($item['name']); ?></h5>
                            <p class="card-text">Price: ₹<?php echo number_format($item['price'], 2); ?></p>
                            <p class="card-text">Quantity: <?php echo $item['quantity']; ?></p>
                            <p class="card-text fw-bold">Subtotal: ₹<?php echo number_format($subtotal, 2); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <p class="fw-bold">Total: ₹<?php echo number_format($total_price, 2); ?></p>
        <button class="btn btn-warning" onclick="goToStep(3)">Proceed to Payment</button>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>

    <!-- Step 3: Payment -->
    <div class="step" id="step3">
        <h4 class="mb-3">Step 3: Payment</h4>
        <form method="POST" action="place_order.php">
            <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
            <label class="form-label">Choose Payment Method:</label>
            <select name="payment_method" class="form-control mb-3" required>
                <option value="COD">Cash on Delivery</option>
                <option value="Online">Online (Dummy)</option>
            </select>
            <button type="submit" class="btn btn-success">Place Order</button>
        </form>
    </div>

    <!-- Step 4: Confirmation -->
<!-- Step 4: Confirmation -->
<?php if (isset($_GET['order']) && $_GET['order'] == 'success') { ?>
    <div class="step active" id="step4">
        <h4 class="mb-3">Step 4: Confirmation</h4>
        <p class="alert alert-success">Your order has been placed successfully! You will be redirected shortly.</p>
        <a href="../index.php" class="btn btn-primary">Go to Homepage Now</a>
    </div>

    <script>
        // Redirect to homepage after 5 seconds
        setTimeout(() => {
            window.location.href = "../index.php";
        }, 5000);
    </script>
<?php } ?>

</div>

<script>
function goToStep(step) {
    document.querySelectorAll('.step').forEach(el => el.classList.remove('active'));
    document.getElementById('step' + step).classList.add('active');
}
</script>

<?php include('../includes/footer.php'); ?>
</body>
</html>
