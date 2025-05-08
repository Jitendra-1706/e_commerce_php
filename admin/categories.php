<?php include('admin_auth.php'); ?>

<?php
include '../includes/db_connect.php';

// Handle Add Category
if (isset($_POST['add_category'])) {
    $name = trim($_POST['category_name']);
    if (!empty($name)) {
        $stmt = $con->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: categories.php");
    exit;
}

// Handle Delete Category
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $con->query("DELETE FROM categories WHERE category_id = $id");
    header("Location: categories.php");
    exit;
}

// Handle Edit Category
if (isset($_POST['edit_category'])) {
    $id = intval($_POST['category_id']);
    $name = trim($_POST['category_name']);
    if (!empty($name)) {
        $stmt = $con->prepare("UPDATE categories SET name = ? WHERE category_id = ?");
        $stmt->bind_param("si", $name, $id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: categories.php");
    exit;
}

// Fetch categories
$query = "SELECT * FROM categories ORDER BY category_id DESC";
$result = $con->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Categories - Unicart Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php include '../includes/mode.php'; ?>
    <link rel="stylesheet" href="../assets/css/admin_bar.css">


</head>
<body>

<div class="d-flex">
    <?php include '../includes/admin_sidebar.php'; ?>
    <div class="flex-grow-1 p-4" style="margin-left: 250px;">
        <div class="container mt-5">
            <h2 class="mb-4">Manage Categories</h2>

            <!-- Add Category -->
            <form method="post" class="row g-3 mb-4">
                <div class="col-md-6">
                    <input type="text" name="category_name" class="form-control" placeholder="Enter category name" required>
                </div>
                <div class="col-auto">
                    <button type="submit" name="add_category" class="btn btn-primary">Add Category</button>
                </div>
            </form>

            <!-- Category Table -->
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Sl. No</th>
                        <th>Category Name</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php $serial = 1; ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $serial++ ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#editModal<?= $row['category_id'] ?>">Edit</button>
                            <a href="?delete=<?= $row['category_id'] ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('Are you sure you want to delete this category?')">Delete</a>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal<?= $row['category_id'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="post" class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Category</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="category_id" value="<?= $row['category_id'] ?>">
                                    <div class="mb-3">
                                        <label>Category Name</label>
                                        <input type="text" name="category_name" class="form-control"
                                               value="<?= htmlspecialchars($row['name']) ?>" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="edit_category" class="btn btn-success">Save Changes</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
