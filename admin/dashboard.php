<?php include('../includes/db_connect.php'); ?>
<?php include('../includes/header.php'); ?>
<?php include('admin_auth.php'); ?>
<?php include '../includes/mode.php'; ?>


<div class="dashboard">
    <h1>Unicart Admin Dashboard</h1>
    <div class="stats-grid">
        <div class="card">
            <h2>Total Products</h2>
            <p>
                <?php
                    $result = $con->query("SELECT COUNT(*) AS total FROM products");
                    $data = $result->fetch_assoc();
                    echo $data['total'];
                ?>
            </p>
        </div>
        <div class="card">
            <h2>Total Orders</h2>
            <p>
                <?php
                    $result = $con->query("SELECT COUNT(*) AS total FROM orders");
                    $data = $result->fetch_assoc();
                    echo $data['total'];
                ?>
            </p>
        </div>
        <div class="card">
            <h2>Total Users</h2>
            <p>
                <?php
                    $result = $con->query("SELECT COUNT(*) AS total FROM users");
                    $data = $result->fetch_assoc();
                    echo $data['total'];
                ?>
            </p>
        </div>
        <div class="card">
            <h2>Total Income</h2>
            <p>
                <?php
                    $result = $con->query("SELECT SUM(total_price) AS total_income FROM orders");
                    $data = $result->fetch_assoc();
                    echo '₹' . number_format($data['total_income'], 2);
                ?>
            </p>
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

<?php include('../includes/footer.php'); ?>
