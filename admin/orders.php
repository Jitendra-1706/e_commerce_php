<?php include('admin_auth.php'); ?>
<?php
include '../includes/db_connect.php';

if (isset($_POST['update_status'])) {
    $orderId = intval($_POST['order_id']);
    $status = $_POST['status'];
    $stmt = $con->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    $stmt->bind_param("si", $status, $orderId);
    $stmt->execute();
    $stmt->close();
    header("Location: orders.php?status=" . $status);
    exit;
}

$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'All';
$statusSafe = $con->real_escape_string($statusFilter);
$statusCondition = ($statusFilter !== 'All') ? "WHERE orders.status = '$statusSafe'" : "";

$totalOrders = $con->query("SELECT COUNT(*) FROM orders")->fetch_row()[0];
$pendingOrders = $con->query("SELECT COUNT(*) FROM orders WHERE status = 'Pending'")->fetch_row()[0];
$deliveredOrders = $con->query("SELECT COUNT(*) FROM orders WHERE status = 'Delivered'")->fetch_row()[0];

$query = "SELECT orders.*, users.name AS username 
          FROM orders 
          JOIN users ON orders.user_id = users.user_id 
          $statusCondition 
          ORDER BY orders.order_date DESC";

$result = $con->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Orders - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php include '../includes/mode.php'; ?>

</head>
<body>

<div class="d-flex">
    <?php include '../includes/admin_sidebar.php'; ?>
    <div class="flex-grow-1 p-4" style="margin-left: 250px;">
        <div class="container mt-5">
            <h2>Order Management</h2>

            <div class="row mb-4">
                <div class="col-md-4">
                    <a href="orders.php?status=All" class="card text-white bg-primary text-decoration-none">
                        <div class="card-body">
                            <h5>Total</h5>
                            <h3><?= $totalOrders ?></h3>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="orders.php?status=Pending" class="card text-white bg-warning text-decoration-none">
                        <div class="card-body">
                            <h5>Pending</h5>
                            <h3><?= $pendingOrders ?></h3>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="orders.php?status=Delivered" class="card text-white bg-success text-decoration-none">
                        <div class="card-body">
                            <h5>Delivered</h5>
                            <h3><?= $deliveredOrders ?></h3>
                        </div>
                    </a>
                </div>
            </div>

            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>Username</th>
                        <th>Total Price</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Order Date</th>
                        <th>Action</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['order_id'] ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td>₹<?= number_format($row['total_price'], 2) ?></td>
                            <td><?= htmlspecialchars($row['payment_method']) ?></td>
                            <td>
                                <?php if ($row['status'] === 'Pending'): ?>
                                    <form method="post" class="d-flex">
                                        <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
                                        <select name="status" class="form-select me-2">
                                            <option value="Pending" selected>Pending</option>
                                            <option value="Delivered">Delivered</option>
                                        </select>
                                        <button type="submit" name="update_status" class="btn btn-outline-primary btn-sm">Update</button>
                                    </form>
                                <?php else: ?>
                                    <span class="badge bg-success">Delivered</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $row['order_date'] ?></td>
                            <td>
                                <a href="order_details.php?order_id=<?= $row['order_id'] ?>" class="btn btn-sm btn-info">Details</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
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
