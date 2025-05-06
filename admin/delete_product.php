<?php include('admin_auth.php'); ?>

<?php
include '../includes/db_connect.php';

$id = $_GET['id'] ?? 0;
$con->query("DELETE FROM products WHERE product_id = $id");

header("Location: products.php");
exit();

