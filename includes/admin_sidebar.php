<!-- includes/admin_sidebar.php -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
.sidebar {
    height: 100vh;
    background-color: #1a1b2e;
    color: #fff;
    width: 250px;
    padding: 20px;
    position: fixed;
}
.sidebar h2 {
    font-size: 24px;
    margin-bottom: 30px;
    color: #fff;
}
.sidebar a {
    color: #cfd8dc;
    text-decoration: none;
    display: block;
    padding: 12px 20px;
    margin-bottom: 10px;
    border-radius: 8px;
    transition: background 0.3s ease;
}
.sidebar a:hover,
.sidebar a.active {
    background-color: #34344e;
    color: #fff;
}
.sidebar a i {
    margin-right: 10px;
}
</style>

<div class="sidebar">
    <h2>Unicart</h2>
    <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>"><i class="fas fa-home"></i> Dashboard</a>
    <a href="products.php" class="<?= basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : '' ?>"><i class="fas fa-box"></i> Products</a>
    <a href="categories.php" class="<?= basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'active' : '' ?>"><i class="fas fa-tags"></i> Categories</a>
    <a href="orders.php" class="<?= basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active' : '' ?>"><i class="fas fa-shopping-cart"></i> Orders</a>
    <a href="users.php" class="<?= basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : '' ?>"><i class="fas fa-users"></i> Users</a>
    <a href="../admin/admin_logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>
