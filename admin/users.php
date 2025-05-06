<?php include('admin_auth.php'); ?>
<?php
include('../includes/db_connect.php');

// Fetch users and one address per user
$query = "
SELECT 
    u.user_id,
    u.name,
    u.email,
    u.created_at,
    addr.address,
    addr.city,
    addr.state,
    addr.postal_code,
    addr.country,
    (SELECT COUNT(*) FROM user_addresses WHERE user_id = u.user_id) AS address_count
FROM users u
LEFT JOIN (
    SELECT * FROM user_addresses GROUP BY user_id
) AS addr ON u.user_id = addr.user_id
ORDER BY u.created_at DESC";


$result = $con->query($query);
?>

<!-- Styles -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<?php include '../includes/mode.php'; ?>

<div class="d-flex">
    <?php include '../includes/admin_sidebar.php'; ?>
    <div class="flex-grow-1 p-4" style="margin-left: 250px;">
        <div class="container-fluid">
            <h1 class="mt-4">Users</h1>

            <?php if (isset($_GET['deleted'])): ?>
                <div class="alert alert-success">User deleted successfully.</div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-bordered table-hover mt-3">
                    <thead class="table-dark">
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Registered At</th>
                            <th>Address (Default or First)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['user_id'] ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                    <td><?= $row['created_at'] ?></td>
                                    <td>
                                        <?= htmlspecialchars($row['address'] ?? '-') ?>
                                        <?php if ($row['address_count'] > 1): ?>
                                            <br>
                                            <a href="view_addresses.php?user_id=<?= $row['user_id'] ?>" class="btn btn-sm btn-info mt-1">View All</a>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="user_orders.php?user_id=<?= $row['user_id'] ?>" class="btn btn-sm btn-primary">Orders</a>
                                        <a href="delete_user.php?user_id=<?= $row['user_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6">No users found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
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

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
