<?php include('admin_auth.php'); ?>

<?php
include('../includes/db_connect.php');

$user_id = $_GET['user_id'];

// Delete addresses first due to foreign key constraint
$con->query("DELETE FROM user_addresses WHERE user_id = $user_id");
$con->query("DELETE FROM users WHERE user_id = $user_id");

header("Location: users.php?deleted=1");
exit();
