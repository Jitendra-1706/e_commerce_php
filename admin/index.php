<?php include('../includes/db_connect.php'); ?>
<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unicart Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --bg-dark: #121212;
            --bg-light: #f5f5f5;
            --text-dark: #f5f5f5;
            --text-light: #121212;
            --accent: #ff5722;
        }

        [data-theme="dark"] {
            background-color: var(--bg-dark);
            color: var(--text-dark);
        }

        [data-theme="light"] {
            background-color: var(--bg-light);
            color: var(--text-light);
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
        }

        .sidebar {
            width: 240px;
            background: #1e1e2f;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px;
        }

        .sidebar h2 {
            color: #fff;
            text-align: center;
            margin-bottom: 40px;
        }

        .sidebar a {
            display: block;
            color: #ccc;
            text-decoration: none;
            padding: 12px 0;
            transition: 0.3s;
        }

        .sidebar a:hover {
            color: #fff;
            background-color: #333;
        }

        .main-content {
            margin-left: 240px;
            padding: 20px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar img {
            height: 40px;
        }

        .theme-toggle {
            cursor: pointer;
            background: none;
            border: none;
            font-size: 20px;
            color: var(--accent);
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 40px;
        }

        .card {
            background-color: #2a2a3b;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            color: #fff;
        }

    </style>
 

<?php include('../includes/header.php'); ?>
</head>
<body>
    <div class="sidebar">
        <h2>Unicart</h2>
        <a href="#admin_dashboard"><i class="fas fa-home"></i> Dashboard</a>
        <a href="products.php"><i class="fas fa-box"></i> Products</a>
        <a href="categories.php"><i class="fas fa-tags"></i> Categories</a>
        <a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a>
        <a href="users.php"><i class="fas fa-users"></i> Users</a>
        <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
        <a href="admin_logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <div class="topbar">
            <img src="../assets/images/logos/logo2.png" alt="Unicart Logo">
            <button class="theme-toggle" onclick="toggleTheme()">
                <i class="fas fa-adjust"></i>
            </button>
        </div>



<div class="dashboard" id="admin_dashboard">
    <h1 class="mb-4">Unicart Admin Dashboard</h1>
    <div class="stats-grid">
        <div class="card bg-primary text-white">
            <h2>Total Products</h2>
            <p>
                <?php
                    $result = $con->query("SELECT COUNT(*) AS total FROM products");
                    $data = $result->fetch_assoc();
                    echo $data['total'];
                ?>
            </p>
        </div>
        <div class="card bg-secondary text-white">
            <h2>Total Orders</h2>
            <p>
                <?php
                    $result = $con->query("SELECT COUNT(*) AS total FROM orders");
                    $data = $result->fetch_assoc();
                    echo $data['total'];
                ?>
            </p>
        </div>
        <div class="card bg-success text-white">
            <h2>Total Users</h2>
            <p>
                <?php
                    $result = $con->query("SELECT COUNT(*) AS total FROM users");
                    $data = $result->fetch_assoc();
                    echo $data['total'];
                ?>
            </p>
        </div>
        <div class="card bg-info text-white">
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

<canvas id="incomeChart" style="max-width: 600px; margin-top: 40px;"></canvas>


    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const newTheme = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        }

        // Load theme from localStorage
        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', savedTheme);
        });

        
    </script>

<?php
$monthlyData = $con->query("SELECT DATE_FORMAT(order_date, '%M') AS month, SUM(total_price) AS income FROM orders GROUP BY MONTH(order_date)");
$months = [];
$income = [];
while ($row = $monthlyData->fetch_assoc()) {
    $months[] = $row['month'];
    $income[] = $row['income'];
}
?>

<script>
const ctx = document.getElementById('incomeChart').getContext('2d');
const incomeChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($months); ?>,
        datasets: [{
            label: 'Monthly Income (₹)',
            data: <?php echo json_encode($income); ?>,
            backgroundColor: '#ff6600',
            borderRadius: 5
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>






<script>
    if (localStorage.getItem("theme") === "dark") {
        document.body.classList.add("dark-mode");
    }

    if (localStorage.getItem("primaryColor")) {
        document.documentElement.style.setProperty('--primary-color', localStorage.getItem("primaryColor"));
    }
</script>

<?php include('../includes/footer.php'); ?>

</body>
</html>
