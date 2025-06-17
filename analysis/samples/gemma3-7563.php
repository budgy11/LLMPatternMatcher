

<?php
session_start();

// Implement logic to remove a specific item from the cart.
// (This is a simplified example - error handling, etc. is needed)

// Get the product ID to remove from the URL
$product_id_to_remove = $_GET['product_id'];

// Remove the item from the cart array
unset($_SESSION['cart'][$product_id_to_remove]);

// Redirect back to the cart page
header("Location: cart.php");
exit();
?>
