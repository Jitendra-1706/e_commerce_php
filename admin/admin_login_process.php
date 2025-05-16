<?php
session_start();
include '../includes/db_connect.php';  // Ensure the database connection is working

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validate input
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Username and password are required.";
        header("Location: admin_login.php");
        exit();
    }

    // SQL query to fetch admin from the database
    $stmt = $con->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists and password matches
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();

        // Debugging output
        echo "Password entered: $password <br>";
        echo "Stored password hash: " . $admin['password'] . "<br>";
        echo "Password verification: " . (password_verify($password, $admin['password']) ? 'true' : 'false') . "<br>";

        // Check if the password matches the hashed password stored in the database
        if (password_verify($password, $admin['password'])) {
            // Successful login
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: index.php");
            exit();
        } else {
            // Incorrect password
            $_SESSION['error'] = "Invalid password.";
            header("Location: admin_login.php");
            exit();
        }
    } else {
        // Admin not found
        $_SESSION['error'] = "Invalid username or password.";
        header("Location: admin_login.php");
        exit();
    }

    // Close the statement and connection
    $stmt->close();
    $con->close();
} else {
    // No form submission
    $_SESSION['error'] = "Please login first.";
    header("Location: admin_login.php");
    exit();
}
