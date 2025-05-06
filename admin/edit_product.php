<?php include('admin_auth.php'); ?>

<?php
include '../includes/db_connect.php';

$id = $_GET['id'] ?? 0;
$product = $con->query("SELECT * FROM products WHERE product_id = $id")->fetch_assoc();
$categories = $con->query("SELECT * FROM categories");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $stock = $_POST["stock"];
    $category_id = $_POST["category_id"];
    
    $image = $product['image'];
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target = "../assets/images/" . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }

    $stmt = $con->prepare("UPDATE products SET name=?, description=?, price=?, stock=?, image=?, category_id=? WHERE product_id=?");
    $stmt->bind_param("ssdisii", $name, $description, $price, $stock, $image, $category_id, $id);
    $stmt->execute();

    header("Location: products.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <?php include '../includes/mode.php'; ?>

</head>
<body>

<div class="d-flex">
    <?php include '../includes/admin_sidebar.php'; ?>
    <div class="flex-grow-1 p-4" style="margin-left: 250px;">
        <!-- Your main content goes here -->
        <div class="d-flex">
    <div class="container p-5">
        <h2 class="mb-4">Edit Product</h2>
        <form method="post" enctype="multipart/form-data" class="card p-4 shadow-sm">
            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" class="form-control" value="<?= $product['name'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3" required><?= $product['description'] ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Price</label>
                <input type="number" name="price" step="0.01" class="form-control" value="<?= $product['price'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Stock</label>
                <input type="number" name="stock" class="form-control" value="<?= $product['stock'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select" required>
                    <?php while ($row = $categories->fetch_assoc()): ?>
                        <option value="<?= $row['category_id'] ?>" <?= $product['category_id'] == $row['category_id'] ? 'selected' : '' ?>>
                            <?= $row['name'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Current Image</label><br>
                <img src="../assets/images/<?= $product['image'] ?>" width="100">
            </div>
            <div class="mb-3">
                <label class="form-label">Change Image</label>
                <input type="file" name="image" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Update Product</button>
            <a href="products.php" class="btn btn-secondary">Cancel</a>
        </form>
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


</body>
</html>
