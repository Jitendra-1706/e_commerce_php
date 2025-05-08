<?php include('admin_auth.php'); ?>
<?php
include '../includes/db_connect.php';
include '../includes/header.php';

// Fetch products with category name
$sql = "SELECT p.*, c.name AS category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.category_id 
        ORDER BY p.created_at DESC";
$result = mysqli_query($con, $sql);
?>
<?php include '../includes/mode.php'; ?>
<link rel="stylesheet" href="../assets/css/admin_bar.css">

<div class="d-flex">
    <?php include '../includes/admin_sidebar.php'; ?>
    <div class="flex-grow-1 p-4" style="margin-left: 250px;">
        <div class="container mt-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Manage Products</h2>
                <a href="add_product.php" class="btn btn-primary">Add New Product</a>
            </div>

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>S/N</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $serial = 1; // Start serial number
                    while ($row = mysqli_fetch_assoc($result)): 
                        $imagePath = "../assets/images/" . htmlspecialchars($row['image']);
                        $imageExists = file_exists($imagePath);
                    ?>
                        <tr>
                            <td><?= $serial++ ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['category_name']) ?></td>
                            <td>₹<?= number_format($row['price'], 2) ?></td>
                            <td><?= (int)$row['stock'] ?></td>
                            <td>
                                <?php if ($imageExists): ?>
                                    <img src="<?= $imagePath ?>" width="60" height="60" alt="Product Image">
                                <?php else: ?>
                                    <span class="text-danger">Missing Image</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="edit_product.php?id=<?= $row['product_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="delete_product.php?id=<?= $row['product_id'] ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this product?');">
                                   Delete
                                </a>
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

<?php include '../includes/footer.php'; ?>
