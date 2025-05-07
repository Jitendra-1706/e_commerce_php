<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$base_url = "/E_commerce/";
?>


<nav class="navbar navbar-expand-lg bg-body-tertiary px-3 shadow-sm">
  <div class="container-fluid">
    <!-- LOGO -->
    <a class="navbar-brand" href="../E_commerce/index.php">
    <img src="/E_commerce/assets/images/logos/logo2.png" alt="logo" style="height: 40px;">
    </a>
    <!-- TOGGLER -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- NAV CONTENT -->
    <div class="collapse navbar-collapse" id="navbarContent">
      <!-- LEFT: Categories -->
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="../E_commerce/index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#about-us">about</a>
        </li>


<?php
require_once(__DIR__ . '/db_connect.php');
$category_query = "SELECT * FROM categories";
$category_result = mysqli_query($con, $category_query);
?>

<li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Categories</a>
  <ul class="dropdown-menu">
    <?php while($row = mysqli_fetch_assoc($category_result)) { ?>
      <li>
        <a class="dropdown-item" href="index.php#category-<?php echo $row['category_id']; ?>">
          <?php echo htmlspecialchars($row['name']); ?>
        </a>
      </li>
    <?php } ?>
  </ul>
</li>


      </ul>

      <!-- RIGHT: Search -->
      <form class="d-flex flex-grow-1 mx-3" role="search" method="GET" action="/E_commerce/search.php">
    <input class="form-control me-2 w-100" style="max-width: 600px;" type="search" name="q" placeholder="Search" aria-label="Search" required>
    <button class="btn btn-outline-success" type="submit">Search</button>
</form>

<!-- User Dropdown -->
<ul class="navbar-nav mb-2 mb-lg-0 me-3">
  <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="dropdown">
      <i class='bx bx-user-circle' style="font-size: 24px;"></i>
      <span class="ms-1">
        <?= isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Login'; ?>
      </span>
    </a>
    <ul class="dropdown-menu dropdown-menu-end">
      <?php if (isset($_SESSION['user_id'])): ?>
        <li><a class="dropdown-item" href="<?= $base_url ?>profile.php">My Profile</a></li>
        <li><a class="dropdown-item" href="<?= $base_url ?>orders.php">Orders</a></li>
        <li><a class="dropdown-item" href="<?= $base_url ?>wishlist.php">Wishlist</a></li>
        <li><a class="dropdown-item" href="<?= $base_url ?>cart.php">Cart</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item text-danger" href="<?= $base_url ?>logout.php">Logout</a></li>
      <?php else: ?>
        <li><a class="dropdown-item" href="<?= $base_url ?>login.php">Login</a></li>
        <li><a class="dropdown-item" href="<?= $base_url ?>login.php">Sign Up</a></li>
      <?php endif; ?>
    </ul>
  </li>
</ul>



      <!-- Cart -->
      <a href="cart.php" class="btn position-relative">
        <i class='bx bx-cart' style="font-size: 24px;"></i>
        <?php
        $cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
        if ($cart_count > 0): ?>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            <?php echo $cart_count; ?>
          </span>
        <?php endif; ?>
      </a>
    </div>
  </div>
</nav>