<?php
include '../includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $stock = $_POST["stock"];
    $category_id = $_POST["category_id"];

    // 1. Map category_id to folder name
    $folder_map = [
        1 => 'smartphone',
        4 => 'tv',
        9 => 'laptop'
        // Add more as needed
    ];

    $folder_name = $folder_map[$category_id] ?? 'misc';
    $upload_dir = "../assets/images/$folder_name/";

    // 2. Create directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // 3. Handle image uploads with full relative path
    function uploadImage($input_name, $upload_dir) {
        if (!empty($_FILES[$input_name]['name'])) {
            $filename = basename($_FILES[$input_name]['name']);
            $target_path = $upload_dir . $filename;
            move_uploaded_file($_FILES[$input_name]['tmp_name'], $target_path);
            return $folder_name.'/'.$filename; // save relative path
        }
        return null;
    }

    $image = uploadImage('image', $upload_dir);
    $image2 = uploadImage('image2', $upload_dir);
    $image3 = uploadImage('image3', $upload_dir);

    // 4. Insert into DB
    $stmt = $con->prepare("INSERT INTO products (name, description, price, stock, image, image2, image3, category_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdisssi", $name, $description, $price, $stock, $image, $image2, $image3, $category_id);
    $stmt->execute();

    header("Location: products.php");
    exit();
}


// Get categories
$categories = $con->query("SELECT * FROM categories");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php include '../includes/mode.php'; ?>
    <link rel="stylesheet" href="../assets/css/admin_bar.css">
    
</head>
<body class="bg-light">

<div class="d-flex">
    <?php include '../includes/admin_sidebar.php'; ?>
    <div class="flex-grow-1 p-4" style="margin-left: 250px;">
        <div class="container mt-5">
            <h2 class="mb-4">Add New Product</h2>
            <form method="post" enctype="multipart/form-data" class="card p-4 shadow-sm bg-white rounded">
                <div class="mb-3">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Price (₹)</label>
                    <input type="number" name="price" step="0.01" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">Select Category</option>
                        <?php while ($row = $categories->fetch_assoc()): ?>
                            <option value="<?= $row['category_id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Image Uploads -->
                <div class="mb-3">
                    <label class="form-label">Main Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Second Image</label>
                    <input type="file" name="image2" class="form-control" accept="image/*">
                </div>

                <div class="mb-3">
                    <label class="form-label">Third Image</label>
                    <input type="file" name="image3" class="form-control" accept="image/*">
                </div>

                <button type="submit" class="btn btn-success">Add Product</button>
                <a href="products.php" class="btn btn-secondary">Cancel</a>
            </form>
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
