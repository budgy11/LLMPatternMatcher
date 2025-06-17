

<?php
session_start();

// You would fetch product details (ID, name, price) from a database or other source.
// For this example, we'll assume the product_id is passed as a GET parameter.

$product_id = $_GET['product_id'];
$quantity = $_GET['quantity'];

// Validate the quantity (e.g., ensure it's a positive integer)
if (is_numeric($quantity) && $quantity > 0) {
    add_to_cart($product_id, $quantity);
} else {
    // Handle invalid quantity (e.g., display an error message)
    echo "Invalid quantity.  Please enter a positive integer.";
}
?>
