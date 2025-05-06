<?php
session_start();
include 'includes/db_connect.php'; // make sure this exists

// Registration
if (isset($_POST['register'])) {
    $name = trim($_POST['reg_name']);
    $email = trim($_POST['reg_email']);
    $password = password_hash($_POST['reg_password'], PASSWORD_DEFAULT);

    // Check if email already exists
    $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Email already registered!";
        header("Location: login.php");
        exit();
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sss", $name, $email, $password);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Registered successfully! Please login.";
            $_SESSION['switch_to_login'] = true;
            header("Location: login.php");
            exit();
        } else {
            $_SESSION['error'] = "Registration failed!";
            header("Location: login.php");
            exit();
        }
    }
}

// Login
if (isset($_POST['login'])) {
    $email = trim($_POST['login_email']);
    $password = $_POST['login_password'];

    $stmt = $con->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['name'];
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error'] = "Invalid login credentials!";
        header("Location: login.php");
        exit();
    }
}
?>
