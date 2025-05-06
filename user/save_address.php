<?php
session_start();
include('../includes/db_connect.php');

$user_id = $_SESSION['user_id'];

// Sanitize inputs
$address = $con->real_escape_string($_POST['address']);
$city = $con->real_escape_string($_POST['city']);
$state = $con->real_escape_string($_POST['state']);
$postal_code = $con->real_escape_string($_POST['postal_code']);
$country = $con->real_escape_string($_POST['country']);

// Check if address already exists
$check = $con->query("SELECT * FROM user_addresses WHERE user_id = $user_id");

if ($check->num_rows == 0) {
    $insert = $con->query("INSERT INTO user_addresses 
        (user_id, address, city, state, postal_code, country, is_default) 
        VALUES ($user_id, '$address', '$city', '$state', '$postal_code', '$country', 1)");
}

// Redirect to checkout again (address saved)
header("Location: checkout.php");
exit();
