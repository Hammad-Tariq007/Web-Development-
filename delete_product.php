<?php
include 'connect.php';
session_start();

// Only admins should access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "Access denied. Admins only.";
    exit();
}

// Get the product ID from the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete the product from the database
    $sql = "DELETE FROM product WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        echo "Product deleted successfully!";
        header('Location: admin.php');
    } else {
        echo "Error deleting product: " . mysqli_error($conn);
    }
}
?>
