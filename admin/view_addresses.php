<?php include('admin_auth.php'); ?>
<?php

include('../includes/db_connect.php');


$user_id = $_GET['user_id'] ?? 0;
$query = "SELECT * FROM user_addresses WHERE user_id = $user_id";
$result = $con->query($query);
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="../assets/css/admin_bar.css">
<link rel="shortcut icon" href="../assets/images/logos/logo2.png" type="image/x-icon">

<?php include '../includes/mode.php'; ?>

<div class="d-flex">
    <?php include '../includes/admin_sidebar.php'; ?>
    <div class="flex-grow-1 p-4" style="margin-left: 250px;">
        <!-- Your main content goes here -->

        <div class="container mt-4">
            <h3>User Address History</h3>
            <a href="users.php" class="btn btn-secondary mb-3">Back to Users</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Address</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Postal Code</th>
                        <th>Country</th>
                        <th>Default</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['address']) ?></td>
                            <td><?= htmlspecialchars($row['city']) ?></td>
                            <td><?= htmlspecialchars($row['state']) ?></td>
                            <td><?= htmlspecialchars($row['postal_code']) ?></td>
                            <td><?= htmlspecialchars($row['country']) ?></td>
                            <td><?= $row['is_default'] ? 'Yes' : 'No' ?></td>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>