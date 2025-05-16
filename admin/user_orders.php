<?php include('admin_auth.php'); ?>
<?php
include('../includes/db_connect.php');

if (!isset($_GET['user_id'])) {
    echo "User ID missing.";
    exit();
}

$user_id = intval($_GET['user_id']);

// Fetch user info
$user_query = $con->query("SELECT name FROM users WHERE user_id = $user_id");
$user = $user_query->fetch_assoc();

// Fetch all orders for the user
$order_query = $con->query("SELECT * FROM orders WHERE user_id = $user_id ORDER BY order_date DESC");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Orders for <?= htmlspecialchars($user['name']) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css">
    <?php include '../includes/mode.php'; ?>
    <link rel="stylesheet" href="../assets/css/admin_bar.css">
    <link rel="shortcut icon" href="../assets/images/logos/logo2.png" type="image/x-icon">

</head>

<body>
    <div class="d-flex">
        <?php include '../includes/admin_sidebar.php'; ?>
        <div class="flex-grow-1 p-4" style="margin-left: 250px;">
            <!-- Your main content goes here -->
            <div class="container mt-5">
                <h2>Orders for <strong><?= htmlspecialchars($user['name']) ?></strong></h2>
                <a href="users.php" class="btn btn-secondary mb-3">← Back to Users</a>

                <?php if ($order_query->num_rows > 0): ?>
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Order ID</th>
                                <th>Total Price</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th>Order Date</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($order = $order_query->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $order['order_id'] ?></td>
                                    <td>₹<?= number_format($order['total_price'], 2) ?></td>
                                    <td><?= $order['payment_method'] ?></td>
                                    <td><?= $order['status'] ?></td>
                                    <td><?= $order['order_date'] ?></td>
                                    <td><a href="order_details.php?order_id=<?= $order['order_id'] ?>" class="btn btn-sm btn-primary">View</a></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-info">This user has not placed any orders.</div>
                <?php endif; ?>
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