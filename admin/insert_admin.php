<?php
include '../includes/db_connect.php';

$username = 'admin';
$password = 'admin@123';

// Hash the password before inserting
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO admin (username, password) VALUES (?, ?)";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "ss", $username, $hashedPassword);

if (mysqli_stmt_execute($stmt)) {
    echo "Admin inserted successfully.";
} else {
    echo "Error: " . mysqli_error($con);
}

mysqli_close($con);
