
<?php
// Include database connection file
require_once 'db.php';

// Get product ID from URL
$product_id = $_GET['product_id'];

// Add item to cart for current user
$quantity = 1;
$sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$_SESSION[user_id]', '$product_id', '$quantity')";
mysqli_query($conn, $sql);

// Redirect back to cart page
header('Location: cart.php');
exit;
?>
