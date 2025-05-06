<?php
session_start();
include '../includes/db_connect.php';  // Ensure the database connection is working

// Check if the login form is submitted
if (isset($_POST['login'])) {
    // Get the input data from the form
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // SQL query to find the admin by username
    $sql = "SELECT * FROM admin WHERE username = '$username'";
    $result = mysqli_query($con, $sql);

    // Check if the query returned any result
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Check if the password matches the hashed password stored in the database
        if (password_verify($password, $row['password'])) {
            // Successful login: Set session variables
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $row['admin_id'];  // Store the admin ID
            $_SESSION['username'] = $row['username'];  // Optionally store the username

            // Redirect to the admin dashboard or home page (index.php)
            header("Location: index.php");
            exit();
        } else {
            // Incorrect password
            $_SESSION['error'] = 'Invalid username or password.';
            header("Location: admin_login.php");
            exit();
        }
    } else {
        // Admin not found
        $_SESSION['error'] = 'Invalid username or password.';
        header("Location: admin_login.php");
        exit();
    }
} else {
    // No login attempt made, redirect to login page
    $_SESSION['error'] = 'Please login first.';
    header("Location: admin_login.php");
    exit();
}
?>
